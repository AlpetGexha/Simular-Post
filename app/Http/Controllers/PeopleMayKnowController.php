<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PeopleMayKnowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $userId = auth()->user()->id;

        // $peopleYouMayKnow = User::select('users.*')
        //     ->leftJoin('followables', function ($join) use ($userId) {
        //         $join->on('users.id', '=', 'followables.followable_id')
        //             ->where('followables.user_id', $userId);
        //     })
        //     ->whereNull('followables.id')
        //     ->where('users.id', '<>', $userId)
        //     ->orderBy('users.created_at', 'desc')
        //     ->take(10)
        //     ->get();

        // $peopleYouMayKnow contains the "People You May Know" based on shared interests and not already followed by the authenticated user

        $peopleYouMayKnow = User::query()
            ->peopleYouMayKnowSmall()
            ->take(10)
            ->get();

        // return $peopleYouMayKnow;

        return view('people', compact('peopleYouMayKnow'));
    }
}
