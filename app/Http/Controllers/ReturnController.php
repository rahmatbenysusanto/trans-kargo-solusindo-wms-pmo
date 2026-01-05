<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Outbound;
use App\Models\OutboundDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

class ReturnController extends Controller
{
    public function index(Request $request): View
    {
        $outbound = Outbound::with('client', 'user')
            ->where('type', 'return')
            ->when($request->query('client'), function ($query) use ($request) {
                return $query->where('client_id', $request->query('client'));
            })
            ->when($request->query('delivery_date'), function ($query) use ($request) {
                return $query->whereDate('delivery_date', $request->query('delivery_date'));
            })
            ->when($request->query('courier'), function ($query) use ($request) {
                return $query->where('courier', $request->query('courier'));
            })
            ->when($request->query('awb'), function ($query) use ($request) {
                return $query->where('tracking_number', 'LIKE', '%'.$request->query('awb').'%');
            })
            ->when($request->query('received_by'), function ($query) use ($request) {
                return $query->where('received_by', $request->query('received_by'));
            })
            ->latest()
            ->paginate(10)
            ->appends([
                'client'        => $request->query('client'),
                'delivery_date' => $request->query('delivery_date'),
                'courier'       => $request->query('courier'),
                'awb'           => $request->query('awb'),
                'received_by'   => $request->query('received_by'),
            ]);

        $client = Client::all();

        $title = 'Return';
        return view('return.index', compact('title', 'outbound', 'client'));
    }

    public function create(): View
    {
        $client = Client::all();
        $inventory = Inventory::with('inboundDetail.inbound.client:id,name')
            ->whereNot('qty', 0)
            ->select('id', 'part_name', 'part_number', 'serial_number', 'inbound_detail_id')
            ->get();

        $title = 'Return';
        return view('return.create', compact('title', 'client', 'inventory'));
    }

    private function returnNumber()
    {
        $prefix = 'RTC-' . date('Ym') . '-';

        $last = Outbound::where('number', 'like', $prefix.'%')
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

            $outbound = Outbound::create([
                'number'        => $this->returnNumber(),
                'client_id'     => $request->post('client'),
                'site_location' => $request->post('siteLocation'),
                'type'          => 'return',
                'qty'           => count($request->post('products')),
                'delivery_date' => $request->post('deliveryDate'),
                'received_by'   => $request->post('receivedBy'),
                'courier'       => $request->post('courier'),
                'tracking_number' => $request->post('trackingNumber'),
                'remarks'       => $request->post('remarks') ?? null,
                'created_by'    => 1
            ]);

            foreach ($request->post('products') as $product) {
                Inventory::where('id', $product['id'])->update([
                    'qty'       => 0,
                    //'status'    => 'in use'
                ]);

                InventoryHistory::create([
                    'inventory_id'  => $product['id'],
                    'type'          => 'Return To Client',
                    'description'   => 'Return To Client with number ' . $outbound->number,
                ]);

                OutboundDetail::create([
                    'outbound_id'   => $outbound->id,
                    'inventory_id'  => $product['id'],
                    'condition'     => $request->post('condition'),
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
        $outbound = Outbound::with('client', 'user')->where('id', $request->query('id'))->first();
        $outboundDetail = OutboundDetail::with('inventory')->where('outbound_id', $request->query('id'))->get();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'Number');
        $activeWorksheet->setCellValue('A2', 'Client');
        $activeWorksheet->setCellValue('A3', 'Site Location');
        $activeWorksheet->setCellValue('A4', 'Type');
        $activeWorksheet->setCellValue('A5', 'Delivery Date');
        $activeWorksheet->setCellValue('A6', 'Received By');
        $activeWorksheet->setCellValue('A7', 'Courier');
        $activeWorksheet->setCellValue('A8', 'Tracking Number');
        $activeWorksheet->setCellValue('A9', 'Remarks');
        $activeWorksheet->setCellValue('A10', 'Created By');
        $activeWorksheet->setCellValue('B1', $outbound->number);
        $activeWorksheet->setCellValue('B2', $outbound->client->name);
        $activeWorksheet->setCellValue('B3', $outbound->site_location);
        $activeWorksheet->setCellValue('B4', $outbound->type);
        $activeWorksheet->setCellValue('B5', $outbound->delivery_date);
        $activeWorksheet->setCellValue('B6', $outbound->received_date);
        $activeWorksheet->setCellValue('B7', $outbound->courier);
        $activeWorksheet->setCellValue('B8', $outbound->tracking_number);
        $activeWorksheet->setCellValue('B9', $outbound->remarks);
        $activeWorksheet->setCellValue('B10', $outbound->user->name);

        $activeWorksheet->setCellValue('A12', 'Part Name');
        $activeWorksheet->setCellValue('B12', 'Part Number');
        $activeWorksheet->setCellValue('C12', 'Serial Number');
        $activeWorksheet->setCellValue('D12', 'Condition');

        $column = 13;
        foreach ($outboundDetail as $product) {
            $activeWorksheet->setCellValue('A'.$column, $product->inventory->part_name);
            $activeWorksheet->setCellValue('B'.$column, $product->inventory->part_number);
            $activeWorksheet->setCellValue('C'.$column, ' '.$product->inventory->serial_number);
            $activeWorksheet->setCellValue('C'.$column, $product->condition);

            $column++;
        }

        $fileName = 'Return '.$outbound->number.'.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function downloadPDF(Request $request)
    {
        $outbound = Outbound::with('client', 'user')->where('id', $request->query('id'))->first();
        $outboundDetail = OutboundDetail::with('inventory')->where('outbound_id', $request->query('id'))->get();

        $pdf = Pdf::loadView('pdf.return', compact('outbound', 'outboundDetail'))->setPaper('A4', 'landscape');
        $fileName = 'Return'.$outbound->number.'.pdf';
        return $pdf->stream($fileName);
    }
}
