<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Product;
use App\Models\StorageArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                    'condition'     => $product['condition'],
                    'manufacture_date'  => $product['manufactureDate'],
                    'warranty_end_date' => $product['warrantyEndDate'],
                    'eos_date'          => $product['eosDate']
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
        $inboundDetail = InboundDetail::where('inbound_id', $inbound->id)->get();
        $storageArea = StorageArea::all();

        $title = 'Put Away';
        return view('inbound.put-away.process', compact('title', 'inbound', 'inboundDetail', 'storageArea'));
    }
}
