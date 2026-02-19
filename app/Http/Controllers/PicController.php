<?php

namespace App\Http\Controllers;

use App\Models\Pic;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PicController extends Controller
{
    public function index(): View
    {
        $pic = Pic::paginate(10);

        $title = 'PIC';
        return view('pic.index', compact('title', 'pic'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Pic::create([
            'name' => $request->post('name'),
        ]);

        return back()->with('success', 'PIC added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $pic = Pic::findOrFail($id);
        $pic->update([
            'name' => $request->post('name'),
        ]);

        return back()->with('success', 'PIC updated successfully');
    }

    public function destroy($id)
    {
        $pic = Pic::findOrFail($id);
        $pic->delete();

        return back()->with('success', 'PIC deleted successfully');
    }
}
