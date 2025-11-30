<?php

namespace App\Http\Controllers;

use App\Models\StorageArea;
use App\Models\StorageBin;
use App\Models\StorageLantai;
use App\Models\StorageRak;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorageController extends Controller
{
    public function area(): View
    {
        $storageArea = StorageArea::all();

        $title = 'Area';
        return view('storage.area', compact('title', 'storageArea'));
    }

    public function areaStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        StorageArea::create([
            'warehouse_id'  => 1,
            'name'          => $request->post('name'),
        ]);

        return back()->with('success', 'Created Area Successfully');
    }

    public function rak(): View
    {
        $storageArea = StorageArea::all();
        $storageRak = StorageRak::with('storageArea')->paginate(10);

        $title = 'Rak';
        return view('storage.rak', compact('title', 'storageRak', 'storageArea'));
    }

    public function rakStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        StorageRak::create([
            'storage_area_id'   => $request->post('area'),
            'name'              => $request->post('name'),
        ]);

        return back()->with('success', 'Created Rak Successfully');
    }

    public function rakFind(Request $request): \Illuminate\Http\JsonResponse
    {
        $rak = StorageRak::where('storage_area_id', $request->get('areaId'))->get();

        return response()->json([
            'data' => $rak,
        ]);
    }

    public function lantai(): View
    {
        $storageArea = StorageArea::all();
        $storageLantai = StorageLantai::with('storageArea', 'storageRak')->paginate(10);

        $title = 'Lantai';
        return view('storage.lantai', compact('title', 'storageLantai', 'storageArea'));
    }

    public function lantaiStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        StorageLantai::create([
            'storage_area_id'   => $request->post('area'),
            'storage_rak_id'    => $request->post('rak'),
            'name'              => $request->post('name'),
        ]);

        return back()->with('success', 'Created Lantai Successfully');
    }

    public function lantaiFind(Request $request): \Illuminate\Http\JsonResponse
    {
        $lantai = StorageLantai::where('storage_rak_id', $request->get('rakId'))->get();

        return response()->json([
            'data' => $lantai,
        ]);
    }

    public function bin(): View
    {
        $storageArea = StorageArea::all();
        $storageBin = StorageBin::with('storageArea', 'storageRak', 'storageLantai')->paginate(10);

        $title = 'Bin';
        return view('storage.bin', compact('title', 'storageArea', 'storageBin'));
    }

    public function binStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        StorageBin::create([
            'storage_area_id'   => $request->post('area'),
            'storage_rak_id'    => $request->post('rak'),
            'storage_lantai_id' => $request->post('lantai'),
            'name'              => $request->post('name'),
        ]);

        return back()->with('success', 'Created Bin Successfully');
    }
}
