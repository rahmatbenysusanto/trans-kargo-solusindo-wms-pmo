<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
                $now = \Carbon\Carbon::now();
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
}
