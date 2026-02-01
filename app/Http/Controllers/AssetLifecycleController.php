<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class AssetLifecycleController extends Controller
{
    public function index(Request $request): View
    {
        $inventory = Inventory::whereNot('qty', 0)
            ->when($request->query('partName'), function ($query) use ($request) {
                return $query->where('part_name', $request->query('partName'));
            })
            ->when($request->query('partNumber'), function ($query) use ($request) {
                return $query->where('part_number', $request->query('partNumber'));
            })
            ->when($request->query('serialNumber'), function ($query) use ($request) {
                return $query->where('serial_number', $request->query('serialNumber'));
            })
            ->when($request->query('status'), function ($query) use ($request) {
                $status = $request->query('status');
                $now = Carbon::now();
                $sixMonthsLater = $now->copy()->addMonths(6);

                if ($status === 'active') {
                    // EOS date > 6 bulan dari sekarang
                    return $query->where('eos_date', '>', $sixMonthsLater);
                } elseif ($status === 'near_eos') {
                    // EOS date belum lewat tapi ≤ 6 bulan dari sekarang
                    return $query->where('eos_date', '>', $now)
                        ->where('eos_date', '<=', $sixMonthsLater);
                } elseif ($status === 'eos') {
                    // EOS date sudah lewat
                    return $query->where('eos_date', '<=', $now);
                } elseif ($status === 'unknown') {
                    // EOS date kosong
                    return $query->whereNull('eos_date');
                }
            })
            ->paginate(10);

        $title = "Asset Lifecycle";
        return view('asset-lifecycle.index', compact('title', 'inventory'));
    }

    public function detail(Request $request): View
    {
        $inventory = Inventory::find($request->query('id'));

        $title = "Asset Lifecycle";
        return view('asset-lifecycle.detail', compact('title', 'inventory'));
    }

    public function update(Request $request)
    {
        Inventory::where('id', $request->post('id'))
            ->update([
                'manufacture_date'  => $request->post('manufactureDate'),
                'warranty_end_date' => $request->post('warrantyEndDate'),
                'eos_date'          => $request->post('eosDate'),
                'remark'            => $request->post('remark'),
            ]);

        InventoryHistory::create([
            'inventory_id'  => $request->post('id'),
            'type'          => 'edit asset lifecycle',
            'description'   => 'Edit Asset Lifecycle',
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function massEdit()
    {
        $title = "Asset Lifecycle";
        return view('asset-lifecycle.mass-edit', compact('title'));
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Serial Number');
        $sheet->setCellValue('B1', 'Manufacture Date (YYYY-MM-DD)');
        $sheet->setCellValue('C1', 'Warranty End Date (YYYY-MM-DD)');
        $sheet->setCellValue('D1', 'EOS Date (YYYY-MM-DD)');

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_mass_edit_asset_lifecycle.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function uploadExcel(Request $request)
    {
        if (!$request->hasFile('excel_file')) {
            return response()->json(['success' => false, 'message' => 'File not found']);
        }

        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Remove header row
        array_shift($rows);

        $data = [];
        foreach ($rows as $rowIndex => $row) {
            if (empty($row[0])) continue;

            $sn = $row[0];
            $exists = Inventory::where('serial_number', $sn)->exists();

            // Function to format date safely
            $formatDate = function ($value, $cellCoordinate) use ($sheet) {
                if (empty($value)) return null;

                // If PhpSpreadsheet detected it as a date cell, it might be a number
                if (is_numeric($value)) {
                    try {
                        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return $value;
                    }
                }
                return $value;
            };

            $data[] = [
                'serial_number' => $sn,
                'manufacture_date' => $formatDate($row[1], 'B' . ($rowIndex + 2)),
                'warranty_end_date' => $formatDate($row[2], 'C' . ($rowIndex + 2)),
                'eos_date' => $formatDate($row[3], 'D' . ($rowIndex + 2)),
                'exists' => $exists
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function processMassEdit(Request $request)
    {
        $data = $request->post('data');

        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'No data to process']);
        }

        $updatedCount = 0;
        foreach ($data as $item) {
            if ($item['exists']) {
                $inventory = Inventory::where('serial_number', $item['serial_number'])->first();

                if ($inventory) {
                    $inventory->update([
                        'manufacture_date'  => $item['manufacture_date'] ?: $inventory->manufacture_date,
                        'warranty_end_date' => $item['warranty_end_date'] ?: $inventory->warranty_end_date,
                        'eos_date'          => $item['eos_date'] ?: $inventory->eos_date,
                    ]);

                    InventoryHistory::create([
                        'inventory_id'  => $inventory->id,
                        'type'          => 'mass edit asset lifecycle',
                        'description'   => 'Mass Edit Asset Lifecycle via Excel',
                    ]);

                    $updatedCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully updated $updatedCount assets."
        ]);
    }
}
