<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Pic;
use App\Models\Product;
use App\Models\StorageArea;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

class InboundController extends Controller
{
    public function index(Request $request): View
    {
        $inbound = Inbound::with('client', 'user', 'pic')
            ->when($request->query('number'), function ($query, $value) {
                $query->where('number', $value);
            })
            ->when($request->query('ownership'), function ($query, $value) {
                $query->where('ownership_status', $value);
            })
            ->when($request->query('inbound_type'), function ($query, $value) {
                $query->where('inbound_type', $value);
            })
            ->when($request->query('received'), function ($query, $value) {
                $query->whereDate('received_at', $value);
            })
            ->whereHas('client', function ($query) use ($request) {
                if ($request->query('client') != null) {
                    $query->where('name', $request->query('client'));
                }
            })
            ->latest()
            ->paginate(10)
            ->appends([
                'number'        => $request->query('number'),
                'ownership'     => $request->query('ownership'),
                'inbound_type'  => $request->query('inbound_type'),
                'received'      => $request->query('received'),
                'client'        => $request->query('client'),
            ]);

        $title = 'Purchase Order';
        return view('inbound.purchaseOrder.index', compact('title', 'inbound'));
    }

    public function detail(Request $request): View
    {
        $inbound = Inbound::with('pic')->where('number', $request->query('number'))->first();
        $inboundDetail = InboundDetail::where('inbound_id', $inbound->id)->get();

        $title = 'Purchase Order';
        return view('inbound.purchaseOrder.detail', compact('title', 'inbound', 'inboundDetail'));
    }

    public function create(): View
    {
        $client = Client::all();
        $pic = Pic::all();

        $title = 'Purchase Order';
        return view('inbound.purchaseOrder.create', compact('title', 'client', 'pic'));
    }

    private function inboundNumber()
    {
        $prefix = 'INB-' . date('Ym') . '-';

        $last = Inbound::where('number', 'like', $prefix . '%')
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

            $inbound = Inbound::create([
                'number'        => $this->inboundNumber(),
                'client_id'     => $request->post('client'),
                'pic_id'        => $request->post('pic'),
                'site_location' => $request->post('siteLocation'),
                'inbound_type'  => $request->post('inboundType'),
                'owner_status'  => $request->post('ownershipStatus'),
                'quantity'      => count($request->post('products')),
                'status'        => 'new',
                'remarks'       => $request->post('remarks'),
                'created_by'    => 1
            ]);

            foreach ($request->post('products') as $product) {
                $checkProduct = Product::where('part_name', $product['partName'])->first();
                if ($checkProduct != null) {
                    $productId = $checkProduct->id;
                } else {
                    $createProduct = Product::create(['part_name' => $product['partName']]);
                    $productId = $createProduct->id;
                }

                InboundDetail::create([
                    'inbound_id'    => $inbound->id,
                    'product_id'    => $productId,
                    'qty'           => 1,
                    'qty_pa'        => 0,
                    'part_name'     => $product['partName'],
                    'part_number'   => $product['partNumber'],
                    'serial_number' => $product['serialNumber'],
                    'condition'     => $product['condition'] ?? null,
                    'manufacture_date'  => $product['manufactureDate'] ?? null,
                    'warranty_end_date' => $product['warrantyEndDate'] ?? null,
                    'eos_date'          => $product['eosDate'] ?? null,
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => true
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        Inbound::where('number', $request->post('number'))->update([
            'status' => $request->post('status')
        ]);

        return response()->json([
            'status' => true
        ]);
    }

    public function putAway(Request $request): View
    {
        $inbound = Inbound::with('client', 'user')
            ->when($request->query('number'), function ($query, $value) {
                $query->where('number', $value);
            })
            ->when($request->query('ownership'), function ($query, $value) {
                $query->where('ownership_status', $value);
            })
            ->when($request->query('inbound_type'), function ($query, $value) {
                $query->where('inbound_type', $value);
            })
            ->when($request->query('received'), function ($query, $value) {
                $query->whereDate('received_at', $value);
            })
            ->whereHas('client', function ($query) use ($request) {
                if ($request->query('client') != null) {
                    $query->where('name', $request->query('client'));
                }
            })
            ->where('status', 'open')
            ->latest()
            ->paginate(10)
            ->appends([
                'number'        => $request->query('number'),
                'ownership'     => $request->query('ownership'),
                'inbound_type'  => $request->query('inbound_type'),
                'received'      => $request->query('received'),
                'client'        => $request->query('client'),
            ]);

        $title = 'Put Away';
        return view('inbound.put-away.index', compact('title', 'inbound'));
    }

    public function putAwayDetail(Request $request): View
    {
        $inbound = Inbound::with('client', 'user')->where('number', $request->query('number'))->first();
        $inboundDetail = InboundDetail::with('inventory.bin.storageArea', 'inventory.bin.storageRak', 'inventory.bin.storageLantai')
            ->where('inbound_id', $inbound->id)
            ->get();

        $title = 'Put Away';
        return view('inbound.put-away.detail', compact('title', 'inbound', 'inboundDetail'));
    }

    public function putAwayProcess(Request $request): View
    {
        $inbound = Inbound::with('client')->where('number', $request->query('number'))->first();
        $inboundDetail = InboundDetail::where('inbound_id', $inbound->id)->where('qty_pa', 0)->get();
        $storageArea = StorageArea::all();

        $title = 'Put Away';
        return view('inbound.put-away.process', compact('title', 'inbound', 'inboundDetail', 'storageArea'));
    }

    /**
     * @throws Throwable
     */
    public function putAwayStore(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->post('productPA') as $product) {
                InboundDetail::where('id', $product['id'])->update([
                    'qty_pa'    => 1
                ]);

                $inboundDetail = InboundDetail::find($product['id']);
                $inbound = Inbound::find($inboundDetail->inbound_id);
                $inventory = Inventory::create([
                    'product_id'        => $inboundDetail->product_id,
                    'client_id'         => $inbound->client_id,
                    'inbound_detail_id' => $inboundDetail->id,
                    'bin_id'            => $request->post('binId'),
                    'qty'               => 1,
                    'status'            => 'available',
                    'part_name'         => $inboundDetail->part_name,
                    'part_number'       => $inboundDetail->part_number,
                    'serial_number'     => $inboundDetail->serial_number,
                    'manufacture_date'  => $inboundDetail->manufacture_date,
                    'warranty_end_date' => $inboundDetail->warranty_end_date,
                    'eos_date'          => $inboundDetail->eos_date,
                    'pic_id'            => $inbound->pic_id,
                    'pic'               => '',
                    'condition'         => 'good'
                ]);

                InventoryHistory::create([
                    'inventory_id'      => $inventory->id,
                    'type'              => 'inbound',
                    'description'       => 'Inbound Process',
                ]);
            }

            // Cek apakah semua product sudah di PA
            $checkPO = InboundDetail::where('inbound_id', $request->post('inboundId'))->where('qty_pa', 0)->count();
            if ($checkPO == 0) {
                Inbound::where('id', $request->post('inboundId'))->update([
                    'status' => 'close'
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => true
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false
            ]);
        }
    }

    public function downloadExcel(Request $request)
    {
        $inbound = Inbound::with('client', 'user')->where('id', $request->query('id'))->first();
        $inboundDetail = InboundDetail::where('inbound_id', $request->query('id'))->get();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('A1', 'Number');
        $activeWorksheet->setCellValue('A2', 'Client');
        $activeWorksheet->setCellValue('A3', 'Site Location');
        $activeWorksheet->setCellValue('A4', 'Inbound Type');
        $activeWorksheet->setCellValue('A5', 'Owner Status');
        $activeWorksheet->setCellValue('A6', 'PIC');
        $activeWorksheet->setCellValue('A7', 'Date');

        $activeWorksheet->setCellValue('B1', $inbound->number);
        $activeWorksheet->setCellValue('B2', $inbound->client->name);
        $activeWorksheet->setCellValue('B3', $inbound->site_location);
        $activeWorksheet->setCellValue('B4', $inbound->inbound_type);
        $activeWorksheet->setCellValue('B5', $inbound->owner_status);
        $activeWorksheet->setCellValue('B6', $inbound->pic->name ?? '-');
        $activeWorksheet->setCellValue('B7', $inbound->created_at);

        $activeWorksheet->setCellValue('A9', 'Part Name');
        $activeWorksheet->setCellValue('B9', 'Part Number');
        $activeWorksheet->setCellValue('C9', 'Serial Number');
        $activeWorksheet->setCellValue('D9', 'Condition');
        $activeWorksheet->setCellValue('E9', 'Manufacture Date');
        $activeWorksheet->setCellValue('F9', 'Warranty End Date');
        $activeWorksheet->setCellValue('G9', 'EOS Date');

        $column = 10;
        foreach ($inboundDetail as $product) {
            $activeWorksheet->setCellValue('A' . $column, $product->part_name);
            $activeWorksheet->setCellValue('B' . $column, $product->part_number);
            $activeWorksheet->setCellValue('C' . $column, $product->serial_number);
            $activeWorksheet->setCellValue('D' . $column, $product->condition);
            $activeWorksheet->setCellValue('E' . $column, $product->manufacture_date);
            $activeWorksheet->setCellValue('F' . $column, $product->warranty_end_date);
            $activeWorksheet->setCellValue('G' . $column, $product->eos_date);

            $column++;
        }

        $fileName = 'Inbound ' . $inbound->number . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function downloadPDF(Request $request)
    {
        $inbound = Inbound::with('client', 'user')->where('id', $request->query('id'))->first();
        $inboundDetail = InboundDetail::where('inbound_id', $request->query('id'))->get();

        $pdf = Pdf::loadView('pdf.inbound', compact('inbound', 'inboundDetail'))->setPaper('A4', 'landscape');
        $fileName = 'Inbound ' . $inbound->number . '.pdf';
        return $pdf->stream($fileName);
    }
}
