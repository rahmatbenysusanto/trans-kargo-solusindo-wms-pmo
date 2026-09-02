<?php

namespace App\Http\Controllers;

use App\Models\BackToWh;
use App\Models\BackToWhDetail;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

class BackToWhController extends Controller
{
    public function index(Request $request): View
    {
        $serialNumbers = collect($request->query('serialNumbers', []))
            ->map(fn($s) => trim($s))->filter()->unique()->values()->all();

        $backToWh = BackToWh::with('user')
            ->withCount('details')
            ->when($request->query('received_at'), function ($query) use ($request) {
                return $query->whereDate('received_at', $request->query('received_at'));
            })
            ->when($request->query('received_by'), function ($query) use ($request) {
                return $query->where('received_by', 'LIKE', '%' . $request->query('received_by') . '%');
            })
            ->when($request->query('reason'), function ($query) use ($request) {
                return $query->where('reason', 'LIKE', '%' . $request->query('reason') . '%');
            })
            ->when($serialNumbers, function ($query) use ($serialNumbers) {
                $query->whereHas('details', function ($q) use ($serialNumbers) {
                    $q->whereIn('serial_number', $serialNumbers);
                });
            })
            ->latest()
            ->paginate(10)
            ->appends([
                'received_at'  => $request->query('received_at'),
                'received_by'  => $request->query('received_by'),
                'reason'       => $request->query('reason'),
                'serialNumbers' => $serialNumbers,
            ]);

        $title = 'Back To WH';
        return view('back-to-wh.index', compact('title', 'backToWh'));
    }

    public function create(): View
    {
        $title = 'Back To WH';
        return view('back-to-wh.create', compact('title'));
    }

    public function detail(Request $request): View
    {
        $backToWh = BackToWh::with('user')->where('id', $request->query('id'))->first();
        $backToWhDetail = BackToWhDetail::with('inventory')->where('back_to_wh_id', $request->query('id'))->get();

        $title = 'Back To WH';
        return view('back-to-wh.detail', compact('title', 'backToWh', 'backToWhDetail'));
    }

    public function searchInventory(Request $request)
    {
        $search = $request->query('search');
        $inventory = Inventory::with('client:id,name')
            ->where('qty', 0)
            ->where('status', 'in use')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('part_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('part_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('serial_number', 'LIKE', '%' . $search . '%');
                });
            })
            ->select('id', 'product_id', 'part_name', 'part_number', 'part_description', 'serial_number', 'client_id')
            ->paginate(15);

        return response()->json($inventory);
    }

    public function searchBySerialNumbers(Request $request)
    {
        $serialNumbers = $request->post('serial_numbers', []);

        if (empty($serialNumbers)) {
            return response()->json([
                'found'     => [],
                'not_found' => [],
            ]);
        }

        $serialNumbers = array_values(array_filter(array_map('trim', $serialNumbers)));

        $found = Inventory::with('client:id,name')
            ->where('qty', 0)
            ->where('status', 'in use')
            ->whereIn('serial_number', $serialNumbers)
            ->select('id', 'product_id', 'part_name', 'part_number', 'part_description', 'serial_number', 'client_id')
            ->get();

        $foundSNs = $found->pluck('serial_number')->toArray();
        $notFound = array_values(array_diff($serialNumbers, $foundSNs));

        return response()->json([
            'found'     => $found,
            'not_found' => $notFound,
        ]);
    }

    private function backToWhNumber()
    {
        $prefix = 'BWH-' . date('Ym') . '-';

        $last = BackToWh::where('number', 'like', $prefix . '%')
            ->orderBy('number', 'desc')
            ->first();

        $nextNumber = $last ? str_pad((int)substr($last->number, -4) + 1, 4, '0', STR_PAD_LEFT) : '0001';

        return $prefix . $nextNumber;
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $backToWh = BackToWh::create([
                'number'      => $this->backToWhNumber(),
                'reason'      => $request->post('reason'),
                'received_at' => $request->post('received_at'),
                'received_by' => $request->post('received_by'),
                'remarks'     => $request->post('remarks') ?? null,
                'created_by'  => 1
            ]);

            foreach ($request->post('products') as $product) {
                $inventory = Inventory::findOrFail($product['id']);

                BackToWhDetail::create([
                    'back_to_wh_id' => $backToWh->id,
                    'inventory_id'  => $product['id'],
                    'product_id'    => $inventory->product_id,
                    'serial_number' => $inventory->serial_number,
                    'part_name'     => $inventory->part_name,
                    'part_number'   => $inventory->part_number,
                    'condition'     => $product['condition'] ?? 'Good',
                    'reason'        => $product['reason'] ?? $request->post('reason'),
                ]);

                Inventory::where('id', $product['id'])->update([
                    'qty'    => 1,
                    'status' => 'available'
                ]);

                InventoryHistory::create([
                    'inventory_id' => $product['id'],
                    'type'         => 'Back To WH',
                    'description'  => 'Back to WH with number ' . $backToWh->number . '. Reason: ' . ($product['reason'] ?? $request->post('reason')),
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => true,
            ]);
        } catch (Throwable $err) {
            DB::rollBack();
            Log::info($err->getMessage());
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function downloadExcel(Request $request)
    {
        $backToWh = BackToWh::with('user')->where('id', $request->query('id'))->first();
        $backToWhDetail = BackToWhDetail::with('inventory')->where('back_to_wh_id', $request->query('id'))->get();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'Number');
        $activeWorksheet->setCellValue('A2', 'Reason');
        $activeWorksheet->setCellValue('A3', 'Received At');
        $activeWorksheet->setCellValue('A4', 'Received By');
        $activeWorksheet->setCellValue('A5', 'Remarks');
        $activeWorksheet->setCellValue('A6', 'Created By');
        $activeWorksheet->setCellValue('B1', $backToWh->number);
        $activeWorksheet->setCellValue('B2', $backToWh->reason);
        $activeWorksheet->setCellValue('B3', $backToWh->received_at);
        $activeWorksheet->setCellValue('B4', $backToWh->received_by);
        $activeWorksheet->setCellValue('B5', $backToWh->remarks);
        $activeWorksheet->setCellValue('B6', $backToWh->user->name);

        $activeWorksheet->setCellValue('A8', 'Part Name');
        $activeWorksheet->setCellValue('B8', 'Part Number');
        $activeWorksheet->setCellValue('C8', 'Serial Number');
        $activeWorksheet->setCellValue('D8', 'Condition');
        $activeWorksheet->setCellValue('E8', 'Reason');

        $column = 9;
        foreach ($backToWhDetail as $product) {
            $activeWorksheet->setCellValue('A' . $column, $product->part_name);
            $activeWorksheet->setCellValue('B' . $column, $product->part_number);
            $activeWorksheet->setCellValue('C' . $column, ' ' . $product->serial_number);
            $activeWorksheet->setCellValue('D' . $column, $product->condition);
            $activeWorksheet->setCellValue('E' . $column, $product->reason);
            $column++;
        }

        $fileName = 'BackToWH ' . $backToWh->number . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function downloadPDF(Request $request)
    {
        $backToWh = BackToWh::with('user')->where('id', $request->query('id'))->first();
        $backToWhDetail = BackToWhDetail::with('inventory')->where('back_to_wh_id', $request->query('id'))->get();

        $pdf = Pdf::loadView('pdf.back-to-wh', compact('backToWh', 'backToWhDetail'))->setPaper('A4', 'landscape');
        $fileName = 'BackToWH_' . $backToWh->number . '.pdf';
        return $pdf->stream($fileName);
    }
}
