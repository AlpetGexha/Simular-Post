<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // TODO: Make a list of user and and make Follow button
        $users = User::with('followings')->paginate(15);

        auth()->user()->attachFollowStatus($users);

        return view('user', compact('users'));
    }
}
