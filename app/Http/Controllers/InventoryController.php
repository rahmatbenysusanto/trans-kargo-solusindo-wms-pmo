<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\StorageArea;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $inventory = Inventory::with('bin', 'bin.storageArea', 'bin.storageRak', 'bin.storageLantai', 'inboundDetail.inbound.client')
            ->when($request->query('partName'), function ($query) use ($request) {
                return $query->where('part_name', 'like', '%' . $request->query('partName') . '%');
            })
            ->when($request->query('partNumber'), function ($query) use ($request) {
                return $query->where('part_number', 'like', '%' . $request->query('partNumber') . '%');
            })
            ->when($request->query('serialNumber'), function ($query) use ($request) {
                return $query->where('serial_number', 'like', '%' . $request->query('serialNumber') . '%');
            })
            ->when($request->query('client'), function ($query) use ($request) {
                return $query->where('client_id', $request->query('client'));
            })
            ->when($request->query('status'), function ($query) use ($request) {
                return $query->where('status', 'like', '%' . $request->query('status') . '%');
            })
            ->paginate(10)
            ->appends([
                'partName'      => $request->query('partName'),
                'partNumber'    => $request->query('partNumber'),
                'serialNumber'  => $request->query('serialNumber'),
                'client'        => $request->query('client'),
                'status'        => $request->query('status'),
            ]);

        $client = Client::all();

        $title = 'Inventory List';
        return view('inventory.inventory-list.index', compact('title', 'inventory', 'client'));
    }

    public function history(Request $request): View
    {
        $inventory = Inventory::with('inboundDetail.inbound', 'bin', 'bin.storageArea', 'bin.storageRak', 'bin.storageLantai')->where('id', $request->query('id'))->first();
        $history = InventoryHistory::where('inventory_id', $request->query('id'))->get();

        $title = 'Inventory List';
        return view('inventory.inventory-list.history', compact('title', 'inventory', 'history'));
    }

    public function stockMovement(): View
    {
        $title = 'Stock Movement';
        return view('inventory.stock-movement.index', compact('title'));
    }

    public function create(): View
    {
        $storageArea = StorageArea::all();
        $inventory = Inventory::with('inboundDetail.inbound.client:id,name')
            ->whereNot('qty', 0)
            ->select('id', 'part_name', 'part_number', 'serial_number', 'inbound_detail_id')
            ->get();

        $title = 'Stock Movement';
        return view('inventory.stock-movement.create', compact('title', 'storageArea', 'inventory'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            

            DB::commit();
            return response()->json([
                'status' => true,
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function downloadExcel(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'Storage');
        $activeWorksheet->setCellValue('B1', 'Client');
        $activeWorksheet->setCellValue('C1', 'Part Name');
        $activeWorksheet->setCellValue('D1', 'Part Number');
        $activeWorksheet->setCellValue('E1', 'Serial Number');
        $activeWorksheet->setCellValue('F1', 'Owner Status');
        $activeWorksheet->setCellValue('G1', 'PIC');
        $activeWorksheet->setCellValue('H1', 'Status');
        $activeWorksheet->setCellValue('I1', 'Remarks');

        $inventory = Inventory::with('bin', 'bin.storageArea', 'bin.storageRak', 'bin.storageLantai', 'inboundDetail.inbound.client')->whereNot('qty', 0)->get();
        $column = 2;
        foreach ($inventory as $product) {
            $activeWorksheet->setCellValue('A'. $column, $product->bin->storageArea->name.' - '.$product->bin->storageRak->name.' - '.$product->bin->storageLantai->name.' - '.$product->bin->name);
            $activeWorksheet->setCellValue('B'. $column, $product->inboundDetail->inbound->client->name);
            $activeWorksheet->setCellValue('C'. $column, $product->part_name);
            $activeWorksheet->setCellValue('D'. $column, $product->part_number);
            $activeWorksheet->setCellValue('E'. $column, $product->serial_number);
            $activeWorksheet->setCellValue('F'. $column, $product->inboundDetail->inbound->owner_status);
            $activeWorksheet->setCellValue('G'. $column, $product->pic);
            $activeWorksheet->setCellValue('H'. $column, $product->status);
            $activeWorksheet->setCellValue('I'. $column, $product->remark);

            $column++;
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'Report Inventory.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function downloadPDF(Request $request)
    {
        $inventory = Inventory::with('bin', 'bin.storageArea', 'bin.storageRak', 'bin.storageLantai', 'inboundDetail.inbound.client')->whereNot('qty', 0)->get();

        $pdf = Pdf::loadView('pdf.inventory', compact('inventory'))->setPaper('A4', 'landscape');
        $fileName = 'Inventory.pdf';
        return $pdf->stream($fileName);
    }
}
