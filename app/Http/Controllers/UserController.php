<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\UserHasMenu;
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

    public function edit(Request $request): View
    {
        $user = User::find($request->query('id'));

        $title = "User";
        return view('user.edit', compact('title', 'user'));
    }

    public function update(Request $request)
    {
        User::where('id', $request->post('id'))->update([
            'username'  => $request->post('username'),
            'name'      => $request->post('name'),
            'no_hp'     => $request->post('no_hp'),
            'email'     => $request->post('email'),
            'status'    => $request->post('status'),
        ]);

        if ($request->post('password') == '********') {
            User::where('id', $request->post('id'))->update([
                'password'  => Hash::make($request->post('password')),
            ]);
        }

        return redirect()->action([UserController::class, 'index']);
    }

    public function menu(Request $request): View
    {
        $user = User::find($request->query('id'));

        $userHasMenu = Menu::with(['userHasMenu' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $title = "User";
        return view('user.menu', compact('title', 'user', 'userHasMenu'));
    }

    public function menuStore(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->post('type') == 'disable') {
            UserHasMenu::where('menu_id', $request->post('menuId'))
                ->where('user_id', $request->post('userId'))
                ->delete();
        } else {
            UserHasMenu::create([
                'user_id' => $request->post('userId'),
                'menu_id' => $request->post('menuId'),
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
