<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Product;
use App\Models\StorageArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class InboundController extends Controller
{
    public function index(): View
    {
        $inbound = Inbound::with('client', 'user')->latest()->paginate(10);

        $title = 'Purchase Order';
        return view('inbound.purchaseOrder.index', compact('title', 'inbound'));
    }

    public function detail(Request $request): View
    {
        $inbound = Inbound::where('number', $request->query('number'))->first();
        $inboundDetail = InboundDetail::where('inbound_id', $inbound->id)->get();

        $title = 'Purchase Order';
        return view('inbound.purchaseOrder.detail', compact('title', 'inbound', 'inboundDetail'));
    }

    public function create(): View
    {
        $client = Client::all();

        $title = 'Purchase Order';
        return view('inbound.purchaseOrder.create', compact('title', 'client'));
    }

    private function inboundNumber()
    {
        $prefix = 'INB-' . date('Ym') . '-';

        $last = Inbound::where('number', 'like', $prefix.'%')
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

    public function putAway(): View
    {
        $inbound = Inbound::where('status', 'open')->paginate(10);

        $title = 'Put Away';
        return view('inbound.put-away.index', compact('title', 'inbound'));
    }

    public function detailPutAway(Request $request): View
    {
        $inbound = Inbound::find($request->query('id'));
        $inboundDetail = InboundDetail::where('inbound_id', $request->query('id'))->get();
        $storage = StorageArea::all();

        $title = 'Put Away';
        return view('inbound.put-away.process', compact('title', 'inbound', 'inboundDetail', 'storage'));
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
                $inventory = Inventory::create([
                    'product_id'        => $inboundDetail->product_id,
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
}
