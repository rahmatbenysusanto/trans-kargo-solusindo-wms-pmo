<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InboundController extends Controller
{
    public function index(): View
    {
        $title = 'Purchase Order';
        return view('inbound.purchaseOrder.index', compact('title'));
    }

    public function create(): View
    {
        $client = Client::all();

        $title = 'Purchase Order';
        return view('inbound.purchaseOrder.create', compact('title', 'client'));
    }

    public function putAway(): View
    {
        $title = 'Put Away';
        return view('inbound.put-away.index', compact('title'));
    }
}
