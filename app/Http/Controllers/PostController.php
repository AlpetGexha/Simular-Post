<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $posts = auth()->user()->followersPosts()
            ->with([
                'media',
                'user.media',
                'reactions',
                'popularComment.popularReply',
            ])
            ->withCount('tags')
            ->whereDate('posts.created_at', '>=', now()->subDays(7))
            ->orderByPopularity()
            ->take(10)
            ->get();

        // return $posts;
        return view('welcome', compact('posts'));
    }
}
