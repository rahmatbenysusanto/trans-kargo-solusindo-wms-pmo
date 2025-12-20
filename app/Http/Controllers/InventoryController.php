<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inventory;
use App\Models\InventoryHistory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $inventory = Inventory::with('bin', 'bin.storageArea', 'bin.storageRak', 'bin.storageLantai', 'inboundDetail.inbound.client')->paginate(10);
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
}
