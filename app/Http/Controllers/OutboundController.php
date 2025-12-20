<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use App\Models\Outbound;
use App\Models\OutboundDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OutboundController extends Controller
{
    public function index(): View
    {
        $outbound = Outbound::with('client', 'user')->latest()->paginate(10);

        $title = "Outbound";
        return view('outbound.index', compact('title', 'outbound'));
    }

    public function detail(Request $request): View
    {
        $outbound = Outbound::with('client', 'user')->where('id', $request->query('id'))->first();
        $outboundDetail = OutboundDetail::with('inventory')->where('outbound_id', $request->query('id'))->get();

        $title = "Outbound";
        return view('outbound.detail', compact('title', 'outbound', 'outboundDetail'));
    }

    public function create(): View
    {
        $client = Client::all();
        $inventory = Inventory::with('inboundDetail.inbound.client:id,name')
            ->whereNot('qty', 0)
            ->select('id', 'part_name', 'part_number', 'serial_number', 'inbound_detail_id')
            ->get();

        $title = "Outbound";
        return view('outbound.create', compact('title', 'client', 'inventory'));
    }

    private function outboundNumber()
    {
        $prefix = 'OUT-' . date('Ym') . '-';

        $last = Outbound::where('number', 'like', $prefix.'%')
            ->orderBy('number', 'desc')
            ->first();

        $nextNumber = $last ? str_pad((int)substr($last->number, -4) + 1, 4, '0', STR_PAD_LEFT) : '0001';

        return $prefix . $nextNumber;
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $outbound = Outbound::create([
                'number'        => $this->outboundNumber(),
                'client_id'     => $request->post('client'),
                'site_location' => $request->post('siteLocation'),
                'type'          => 'outbound',
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
                    'status'    => 'in use'
                ]);

                InventoryHistory::create([
                    'inventory_id'  => $product['id'],
                    'type'          => 'Outbound',
                    'description'   => 'Outbound with number ' . $outbound->number,
                ]);

                OutboundDetail::create([
                    'outbound_id'   => $outbound->id,
                    'inventory_id'  => $product['id'],
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => true,
            ]);
        } catch (\Throwable $err) {
            DB::rollBack();
            Log::info($err->getMessage());
            return response()->json([
                'status' => false,
            ]);
        }
    }
}
