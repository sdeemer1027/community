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

            









                {{-- Private library gallery --}}
                <h2 class="h5 mb-3">My Photos and Videos</h2>

                <div class="row g-3">
                    @forelse ($mediaItems as $item)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative">
                                    @if ($item->media_type === 'image')
                                        <button
                                            type="button"
                                            class="media-gallery-image p-0 border-0 bg-dark w-100"
                                            data-image-url="{{ route('media-library.file', $item) }}"
                                            data-image-alt="{{ $item->caption ?: $item->original_filename }}"
                                            aria-label="View full-size photo"
                                            style="display:block; cursor:zoom-in;"
                                        >
                                            <img
                                                src="{{ route('media-library.file', $item) }}"
                                                alt="{{ $item->caption ?: $item->original_filename }}"
                                                loading="lazy"
                                                style="display:block; width:100%; aspect-ratio:1/1; object-fit:cover;"
                                            >
                                        </button>
                                    @elseif ($item->media_type === 'video')
                                       


                                        <button
                                            type="button"
                                            class="media-gallery-video p-0 border-0 bg-dark w-100 position-relative"
                                            data-video-url="{{ route('media-library.file', $item) }}"
                                            data-video-type="{{ $item->mime_type }}"
                                            aria-label="Play video"
                                            style="display:block; cursor:pointer;"
                                        >
                                            <video
                                                muted
                                                preload="metadata"
                                                playsinline
                                                style="display:block; width:100%; aspect-ratio:1/1; object-fit:cover; background:#111; pointer-events:none;"
                                            >
                                                <source
                                                    src="{{ route('media-library.file', $item) }}"
                                                    type="{{ $item->mime_type }}"
                                                >
                                            </video>

                                            <span
                                                aria-hidden="true"
                                                style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:white; font-size:42px; text-shadow:0 2px 6px #000;"
                                            >&#9654;</span>
                                        </button>


                                    @endif

                                    <span class="badge bg-secondary position-absolute top-0 end-0 m-2">
                                        Private
                                    </span>
                                </div>

                                <div class="card-body p-2 d-flex flex-column">
                                    <p class="small fw-semibold mb-1 text-break">
                                        {{ $item->original_filename }}
                                    </p>

                                    @if ($item->caption)
                                        <p class="small text-secondary mb-2">
                                            {{ $item->caption }}
                                        </p>
                                    @endif

                                    <small class="text-secondary mt-auto mb-2">
                                        {{ $item->created_at->format('M j, Y') }}
                                    </small>

                                    <form
                                        action="{{ route('media-library.destroy', $item) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this media item permanently?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-sm w-100"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <h3 class="h5">Your library is empty</h3>
                                    <p class="text-secondary mb-0">
                                        Upload your first photo or video above.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>








                <div class="mt-3">
                    {{ $mediaItems->links() }}
                </div>

            </div>
        </x-community-layout>
    </div>



<div
    id="media-image-modal"
    hidden
    role="dialog"
    aria-modal="true"
    aria-label="Full-size photo viewer"
    style="position:fixed; inset:0; z-index:2000; background:rgba(0,0,0,.88); padding:24px; display:none; align-items:center; justify-content:center;"
>
    <button
        type="button"
        id="media-image-modal-close"
        aria-label="Close photo"
        style="position:absolute; top:16px; right:20px; color:white; background:#333; border:0; border-radius:50%; width:44px; height:44px; font-size:28px; cursor:pointer;"
    >&times;</button>

    <img
        id="media-image-modal-photo"
        src=""
        alt=""
        style="max-width:95vw; max-height:90vh; object-fit:contain; border-radius:4px;"
    >
</div>



<div
    id="media-video-modal"
    hidden
    role="dialog"
    aria-modal="true"
    aria-label="Video player"
    style="position:fixed; inset:0; z-index:2001; background:rgba(0,0,0,.9); padding:24px; display:none; align-items:center; justify-content:center;"
>
    <button
        type="button"
        id="media-video-modal-close"
        aria-label="Close video"
        style="position:absolute; top:16px; right:20px; color:white; background:#333; border:0; border-radius:50%; width:44px; height:44px; font-size:28px; cursor:pointer;"
    >&times;</button>

    <video
        id="media-video-modal-player"
        controls
        playsinline
        preload="metadata"
        style="max-width:95vw; max-height:85vh; width:100%; object-fit:contain; background:#000;"
    >
        Your browser does not support video playback.
    </video>
</div>






<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('media-image-modal');
        const modalPhoto = document.getElementById('media-image-modal-photo');
        const closeButton = document.getElementById('media-image-modal-close');

        if (!modal || !modalPhoto || !closeButton) {
            return;
        }

        function closePhotoModal() {
            modal.style.display = 'none';
            modal.hidden = true;
            modalPhoto.src = '';
            modalPhoto.alt = '';
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.media-gallery-image').forEach(function (button) {
            button.addEventListener('click', function () {
                modalPhoto.src = button.dataset.imageUrl;
                modalPhoto.alt = button.dataset.imageAlt || 'Full-size photo';
                modal.hidden = false;
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                closeButton.focus();
            });
        });

        closeButton.addEventListener('click', closePhotoModal);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closePhotoModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.hidden) {
                closePhotoModal();
            }
        });



        const videoModal = document.getElementById('media-video-modal');
        const videoPlayer = document.getElementById('media-video-modal-player');
        const videoCloseButton = document.getElementById('media-video-modal-close');

        function closeVideoModal() {
            videoPlayer.pause();
            videoPlayer.removeAttribute('src');
            videoPlayer.load();
            videoModal.style.display = 'none';
            videoModal.hidden = true;
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.media-gallery-video').forEach(function (button) {
            button.addEventListener('click', function () {
                videoPlayer.src = button.dataset.videoUrl;
                videoPlayer.load();

                videoModal.hidden = false;
                videoModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';

                videoPlayer.play().catch(function () {
                    // If autoplay is blocked, the user can press Play.
                });

                videoCloseButton.focus();
            });
        });

        videoCloseButton.addEventListener('click', closeVideoModal);

        videoModal.addEventListener('click', function (event) {
            if (event.target === videoModal) {
                closeVideoModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !videoModal.hidden) {
                closeVideoModal();
            }
        });



    });
</script>







    
</x-app-layout>
