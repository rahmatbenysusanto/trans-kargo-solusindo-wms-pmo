<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $user = User::paginate(10);

        $title = "User";
        return view('user.index', compact('title', 'user'));
    }

    public function create(): View
    {
        $title = "User";
        return view('user.create', compact('title'));
    }

    public function store(Request $request)
    {
        User::create([
            'username'  => $request->post('username'),
            'name'      => $request->post('name'),
            'no_hp'     => $request->post('no_hp'),
            'email'     => $request->post('email'),
            'password'  => Hash::make($request->post('password')),
            'status'    => $request->post('status'),
        ]);

        return redirect()->action([UserController::class, 'index']);
    }
}
