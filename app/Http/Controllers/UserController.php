<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(Request $request)
    {
        if (User::where('username', $request->username)->exists()) {
            return response()->error('User with this username already exists');
        }

        $user = User::create($request->all());

        return response()->success($user);
    }

    public function update(User $user, Request $request)
    {
        if (User::where('username', $request->username)->where('id', '!=', $user->id)->exists()) {
            return response()->error('User with this username already exists');
        }

        $user->update($request->all());

        return response()->success($user);
    }

    public function all()
    {
        $users = User::where('id', '!=', auth()->id())->get();

        return response()->success($users);
    }

    public function get(User $user)
    {
        return response()->success($user);
    }
}
