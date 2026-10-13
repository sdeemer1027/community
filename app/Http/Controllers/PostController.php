<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $connectedUserIds = Connection::where('status', 'accepted')
            ->where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            })
            ->get()
            ->map(function ($connection) use ($user) {
                return $connection->requester_id == $user->id
                    ? $connection->recipient_id
                    : $connection->requester_id;
            })
            ->all();

        $posts = Post::with('user')
            ->whereHas('user', function ($query) use ($user) {
                if ($user->zip_code) {
                    $query->where('zip_code', $user->zip_code);
                } else {
                    $query->where('id', $user->id);
                }
            })
            ->latest()
            ->paginate(20);

        return view('dashboard', [
            'posts' => $posts,
            'connectedUserIds' => $connectedUserIds,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $request->user()->posts()->create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Your post has been published.');
    }
}
