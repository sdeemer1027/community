<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerationController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::with(['user', 'mediaItems'])
            ->latest()
            ->paginate(20);

        return view('moderation.index', [
            'posts' => $posts,
        ]);
    }

    public function hide(Post $post): RedirectResponse
    {
        $post->hidden_at = now();
        $post->save();

        return back()->with(
            'success',
            'Post hidden from the community feed.'
        );
    }

    public function restore(Post $post): RedirectResponse
    {
        $post->hidden_at = null;
        $post->save();

        return back()->with(
            'success',
            'Post restored to the community feed.'
        );
    }
}
