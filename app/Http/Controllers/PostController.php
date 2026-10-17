<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\MediaItem;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Find all accepted connections.
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






        // Find accepted connections who have marked the current user as family.
        $familyAuthorIds = Connection::where('status', 'accepted')
            ->where(function ($query) use ($user) {
                $query->where(function ($side) use ($user) {
                    $side->where('recipient_id', $user->id)
                        ->where('requester_is_family', true);
                })->orWhere(function ($side) use ($user) {
                    $side->where('requester_id', $user->id)
                        ->where('recipient_is_family', true);
                });
            })
            ->get()
            ->map(function ($connection) use ($user) {
                return $connection->requester_id == $user->id
                    ? $connection->recipient_id
                    : $connection->requester_id;
            })
            ->all();








        // Start with posts the current user is allowed to see.
        $posts = Post::with(['user', 'mediaItems'])
    ->whereNull('hidden_at')
            ->where(function ($query) use (
    $user,
    $connectedUserIds,
    $familyAuthorIds
) {

                // Always show the user's own posts.
                $query->where('user_id', $user->id)

                    // Show public posts from the same ZIP code
                    // or from accepted connections.
                    ->orWhere(function ($public) use (
                        $user,
                        $connectedUserIds
                    ) {
                        $public->where('audience', 'public')
                            ->where(function ($location) use (
                                $user,
                                $connectedUserIds
                            ) {
                                if ($user->zip_code) {
                                    $location->whereHas(
                                        'user',
                                        function ($author) use ($user) {
                                            $author->where(
                                                'zip_code',
                                                $user->zip_code
                                            );
                                        }
                                    );

                                    if (!empty($connectedUserIds)) {
                                        $location->orWhereIn(
                                            'user_id',
                                            $connectedUserIds
                                        );
                                    }
                                } elseif (!empty($connectedUserIds)) {
                                    $location->whereIn(
                                        'user_id',
                                        $connectedUserIds
                                    );
                                } else {
                                    $location->whereRaw('1 = 0');
                                }
                            });
                    })

      



                    // Show friends-only posts from accepted connections.
                    ->orWhere(function ($friends) use (
                        $connectedUserIds
                    ) {
                        $friends->where('audience', 'friends');

                        if (!empty($connectedUserIds)) {
                            $friends->whereIn(
                                'user_id',
                                $connectedUserIds
                            );
                        } else {
                            $friends->whereRaw('1 = 0');
                        }
                    })

                    // Show family-only posts to people
                    // the author has marked as family.
                    ->orWhere(function ($family) use (
                        $familyAuthorIds
                    ) {
                        $family->where('audience', 'family');

                        if (!empty($familyAuthorIds)) {
                            $family->whereIn(
                                'user_id',
                                $familyAuthorIds
                            );
                        } else {
                            $family->whereRaw('1 = 0');
                        }
                    });
            })
            ->latest()
            ->paginate(20);




        // Only offer media belonging to the signed-in user.
        $mediaItems = MediaItem::where('user_id', $user->id)
            ->latest()
            ->get();


        // Calculate Free Member usage for the dashboard.
        $postsToday = $user->posts()
            ->whereBetween('created_at', [
                now()->startOfDay(),
                now()->endOfDay(),
            ])
            ->count();

        $mediaStorageBytes = $user->mediaItems()->sum('file_size');

        $connectionLimit = config('community.free_member_connection_limit', 100);
        $storageLimitMb = config('community.free_member_storage_limit_mb', 100);

        $acceptedConnections = Connection::where('status', 'accepted')
            ->where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            })
            ->count();






        return view('dashboard', [
            'posts' => $posts,
            'connectedUserIds' => $connectedUserIds,
            'mediaItems' => $mediaItems,
            'postsToday' => $postsToday,
            'mediaStorageBytes' => $mediaStorageBytes,
            'acceptedConnections' => $acceptedConnections,
            'connectionLimit' => $connectionLimit,
            'storageLimitMb' => $storageLimitMb,
        ]);






    }

    public function store(Request $request): RedirectResponse
    {
        // Validate the post and its selected audience.
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'audience' => [
                'required',
                'in:only_me,friends,family,public',
            ],
            'media' => ['nullable', 'array'],
            'media.*' => ['integer', 'distinct'],
        ]);

        
$user = $request->user();

// Free Members may post only to themselves or connections.
if (
    $user->membership_level === 'free' &&
    !in_array($validated['audience'], ['only_me', 'friends'], true)
) {
    return back()
        ->withErrors([
            'audience' => 'Free Members can post only to themselves or their connections.',
        ])
        ->withInput();
}


// Free Members may publish up to 5 posts per Eastern Time day.
if ($user->membership_level === 'free') {
    $postsToday = $user->posts()
        ->whereBetween('created_at', [
            now()->startOfDay(),
            now()->endOfDay(),
        ])
        ->count();

    if ($postsToday >= 5) {
        return back()
            ->withErrors([
                'body' => 'Free Members can publish up to 5 posts per day. Your limit resets at midnight Eastern Time.',
            ])
            ->withInput();
    }
}



// Confirm every selected media item belongs to this user.

        $selectedMediaIds = $validated['media'] ?? [];

        $mediaItems = MediaItem::where('user_id', $user->id)
            ->whereIn('id', $selectedMediaIds)
            ->get();

        if ($mediaItems->count() !== count($selectedMediaIds)) {
            return back()
                ->withErrors([
                    'media' => 'One or more selected media items are invalid.',
                ])
                ->withInput();
        }

        // Save the post and connect the selected media.
        $post = $user->posts()->create([
            'body' => $validated['body'],
            'audience' => $validated['audience'],
        ]);

        $post->mediaItems()->sync($mediaItems->pluck('id'));

        return redirect()
            ->route('dashboard')
            ->with('success', 'Your post has been published.');
    }




    public function media(
        Request $request,
        Post $post,
        MediaItem $mediaItem
    ) {
        $user = $request->user();
        // Hidden post attachments are restricted to moderators.
        if (
            $post->hidden_at &&
            !$user->hasPermission('posts.moderate')
        ) {
            abort(404);
        }


        // The media must be attached to this specific post.
        abort_unless(
            $post->mediaItems()
                ->whereKey($mediaItem->id)
                ->exists(),
            404
        );

        // Find accepted connections.
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

        // The author can always view their own post media.
        $allowed = (int) $post->user_id === (int) $user->id;

        if (!$allowed && $post->audience === 'friends') {
            $allowed = in_array(
                (int) $post->user_id,
                array_map('intval', $connectedUserIds),
                true
            );
        }

        if (!$allowed && $post->audience === 'public') {
            $allowed = in_array(
                (int) $post->user_id,
                array_map('intval', $connectedUserIds),
                true
            );

            if (
                !$allowed &&
                $user->zip_code &&
                $post->user &&
                $post->user->zip_code
            ) {
                $allowed = $user->zip_code === $post->user->zip_code;
            }
        }

       
        // Allow family posts only when the author has
        // marked the current user as family.
        if (!$allowed && $post->audience === 'family') {
            $familyConnection = Connection::where('status', 'accepted')
                ->where(function ($query) use ($post, $user) {
                    $query->where(function ($side) use ($post, $user) {
                        $side->where('requester_id', $post->user_id)
                            ->where('recipient_id', $user->id)
                            ->where('requester_is_family', true);
                    })->orWhere(function ($side) use ($post, $user) {
                        $side->where('recipient_id', $post->user_id)
                            ->where('requester_id', $user->id)
                            ->where('recipient_is_family', true);
                    });
                })
                ->exists();

            $allowed = $familyConnection;
        }

        abort_unless($allowed, 404);

        // Stream the file from private storage.
        $disk = Storage::disk('local');

        abort_unless($disk->exists($mediaItem->file_path), 404);

        return response()->stream(
            function () use ($disk, $mediaItem) {
                $stream = $disk->readStream($mediaItem->file_path);

                if ($stream === false) {
                    abort(404);
                }

                if (ob_get_length()) {
                    ob_clean();
                }

                fpassthru($stream);
                fclose($stream);
            },
            200,
            [
                'Content-Type' => $mediaItem->mime_type,
                'Content-Length' => (string) $mediaItem->file_size,
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]
        );
    }




public function feed(Request $request): View
{
    $user = $request->user();

    // Find all accepted connections.
    $connections = Connection::where('status', 'accepted')
        ->where(function ($query) use ($user) {
            $query->where('requester_id', $user->id)
                ->orWhere('recipient_id', $user->id);
        })
        ->get();

    $connectedUserIds = $connections->map(function ($connection) use ($user) {
        return $connection->requester_id == $user->id
            ? $connection->recipient_id
            : $connection->requester_id;
    })->all();

    // Find accepted connections marked as family by the post author.
    $familyAuthorIds = Connection::where('status', 'accepted')
        ->where(function ($query) use ($user) {
            $query->where(function ($side) use ($user) {
                $side->where('recipient_id', $user->id)
                    ->where('requester_is_family', true);
            })->orWhere(function ($side) use ($user) {
                $side->where('requester_id', $user->id)
                    ->where('recipient_is_family', true);
            });
        })
        ->get()
        ->map(function ($connection) use ($user) {
            return $connection->requester_id == $user->id
                ? $connection->recipient_id
                : $connection->requester_id;
        })->all();

    // Retrieve only posts this user is allowed to see.
    $posts = Post::with(['user', 'mediaItems'])
    ->whereNull('hidden_at')
        ->where(function ($query) use (
            $user,
            $connectedUserIds,
            $familyAuthorIds
        ) {
            $query->where('user_id', $user->id)
                ->orWhere(function ($public) use (
                    $user,
                    $connectedUserIds
                ) {
                    $public->where('audience', 'public')
                        ->where(function ($location) use (
                            $user,
                            $connectedUserIds
                        ) {
                            if ($user->zip_code) {
                                $location->whereHas('user', function ($author) use ($user) {
                                    $author->where('zip_code', $user->zip_code);
                                });

                                if (!empty($connectedUserIds)) {
                                    $location->orWhereIn('user_id', $connectedUserIds);
                                }
                            } elseif (!empty($connectedUserIds)) {
                                $location->whereIn('user_id', $connectedUserIds);
                            } else {
                                $location->whereRaw('1 = 0');
                            }
                        });
                })
                ->orWhere(function ($friends) use ($connectedUserIds) {
                    $friends->where('audience', 'friends');

                    if (!empty($connectedUserIds)) {
                        $friends->whereIn('user_id', $connectedUserIds);
                    } else {
                        $friends->whereRaw('1 = 0');
                    }
                })
                ->orWhere(function ($family) use ($familyAuthorIds) {
                    $family->where('audience', 'family');

                    if (!empty($familyAuthorIds)) {
                        $family->whereIn('user_id', $familyAuthorIds);
                    } else {
                        $family->whereRaw('1 = 0');
                    }
                });
        })
        ->latest()
        ->paginate(20);

    return view('posts._feed', [
        'posts' => $posts,
        'connectedUserIds' => $connectedUserIds,
    ]);
}



}
