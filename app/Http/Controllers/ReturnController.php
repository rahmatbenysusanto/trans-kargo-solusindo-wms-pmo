<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(): View
    {
        $title = 'Return';
        return view('return.index', compact('title'));
    }
}
