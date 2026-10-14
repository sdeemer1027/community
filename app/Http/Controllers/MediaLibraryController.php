<?php

namespace App\Http\Controllers;

use App\Models\MediaItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    /**
     * Display the signed-in member's private media library.
     */
    public function index(Request $request): View
    {
        $mediaItems = $request->user()
            ->mediaItems()
            ->latest()
            ->paginate(20);

        return view('media-library.index', [
            'mediaItems' => $mediaItems,
        ]);
    }

    /**
     * Upload a photo or video to the member's private folder.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'media' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,webm',
                'max:51200',
            ],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $file = $validated['media'];
        $mimeType = $file->getMimeType();

        if (!Str::startsWith($mimeType, ['image/', 'video/'])) {
            return back()->withErrors([
                'media' => 'Please upload a valid image or video.',
            ])->withInput();
        }

        $mediaType = Str::startsWith($mimeType, 'image/')
            ? 'image'
            : 'video';

        $user = $request->user();
        $directory = (string) $user->id;
        $filename = Str::uuid()->toString() . '.' . $file->extension();

        // The local disk points to storage/app/private.
        $path = $file->storeAs($directory, $filename, 'local');

        if ($path === false) {
            return back()->withErrors([
                'media' => 'The file could not be saved. Please try again.',
            ]);
        }

        try {
            $user->mediaItems()->create([
                'file_path' => $path,
                'media_type' => $mediaType,
                'original_filename' => $file->getClientOriginalName(),
                'caption' => $validated['caption'] ?? null,
                'file_size' => $file->getSize(),
                'mime_type' => $mimeType,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        return redirect()
            ->route('media-library.index')
            ->with('success', 'Your media was uploaded privately.');
    }

    /**
     * Stream a media item only to its owner.
     */
    
public function file(Request $request, MediaItem $mediaItem)
{
    abort_unless(
        (int) $mediaItem->user_id === (int) $request->user()->id,
        404
    );

    $disk = Storage::disk('local');

    abort_unless($disk->exists($mediaItem->file_path), 404);

  
/*
    return response()->file(
    $disk->path($mediaItem->file_path),
    [
        'Content-Type' => $mediaItem->mime_type,
        'X-Content-Type-Options' => 'nosniff',
        'Cache-Control' => 'private, no-store',
    ]
                          );
*/


                          
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
    /**
     * Delete a media item belonging to the signed-in member.
     */
    public function destroy(
        Request $request,
        MediaItem $mediaItem
    ): RedirectResponse {
        abort_unless(
            (int) $mediaItem->user_id === (int) $request->user()->id,
            404
        );

        Storage::disk('local')->delete($mediaItem->file_path);
        $mediaItem->delete();

        return redirect()
            ->route('media-library.index')
            ->with('success', 'Media item deleted.');
    }
}
