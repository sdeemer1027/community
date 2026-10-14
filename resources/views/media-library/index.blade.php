<x-app-layout>
    <div class="community-dashboard">
        <x-community-layout>
            <div class="container-fluid py-3">

                <div class="mb-4">
                    <h1 class="h3">My Private Media Library</h1>
                    <p class="text-secondary">
                        Your photos and videos are private unless you choose
                        to share them in a post.
                    </p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Upload form --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3">Upload a Photo or Video</h2>

                        <form
                            action="{{ route('media-library.store') }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >
                            @csrf

                            <div class="mb-3">
                                <label for="media" class="form-label">
                                    Choose a photo or video
                                </label>

                                <input
                                    type="file"
                                    id="media"
                                    name="media"
                                    class="form-control"
                                    accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/webm"
                                    required
                                >

                                <small class="text-secondary">
                                    Maximum file size: 50 MB.
                                </small>

                                @error('media')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="caption" class="form-label">
                                    Caption (optional)
                                </label>

                                <input
                                    type="text"
                                    id="caption"
                                    name="caption"
                                    class="form-control"
                                    maxlength="1000"
                                    value="{{ old('caption') }}"
                                    placeholder="Add a description..."
                                >

                                @error('caption')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Upload to My Library
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Private library items --}}
                <h2 class="h5 mb-3">My Photos and Videos</h2>

                @forelse ($mediaItems as $item)
                    <div class="card mb-3">
                        <div class="card-body">

                            <div class="mb-3">
                                @if ($item->media_type === 'image')
                                    <span class="badge bg-primary">Photo</span>
                                @else
                                    <span class="badge bg-info text-dark">Video</span>
                                @endif

                                <span class="badge bg-secondary">Private</span>
                            </div>

                            {{-- Secure media preview --}}
                            <div class="mb-3">


{{--$item}}

<BR><BR>
{{ route('media-library.file', $item->file_path) --}}


                                @if ($item->media_type === 'image')
                                    <img
                                        src="{{ route('media-library.file', $item) }}"
                                        alt="{{ $item->caption ?: 'My private photo' }}"
                                        loading="eager"
                                        style="display:block; max-width:100%; max-height:400px; width:auto; height:auto; border-radius:8px;"
                                    >



                                @elseif ($item->media_type === 'video')
                                    <video
                                        controls
                                        preload="metadata"
                                        style="display:block; max-width:100%; max-height:400px; width:100%; border-radius:8px;"
                                    >
                                        <source
                                            src="{{ route('media-library.file', $item) }}"
                                            type="{{ $item->mime_type }}"
                                        >
                                        Your browser does not support video playback.
                                    </video>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between
                                        align-items-start gap-3">

                                <div class="flex-grow-1">
                                    <p class="mb-1 fw-semibold">
                                        {{ $item->original_filename }}
                                    </p>

                                    @if ($item->caption)
                                        <p class="mb-2">
                                            {{ $item->caption }}
                                        </p>
                                    @endif

                                    <small class="text-secondary">
                                        Uploaded
                                        {{ $item->created_at->format('M j, Y') }}
                                    </small>
                                </div>

                                <form
                                    action="{{ route('media-library.destroy', $item) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this media item permanently?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger btn-sm"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="h5">Your library is empty</h3>
                            <p class="text-secondary mb-0">
                                Upload your first photo or video above.
                            </p>
                        </div>
                    </div>
                @endforelse

                <div class="mt-3">
                    {{ $mediaItems->links() }}
                </div>

            </div>
        </x-community-layout>
    </div>
</x-app-layout>
