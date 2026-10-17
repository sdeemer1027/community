
<x-app-layout>
    <div class="container py-4">
        <div class="community-card mb-4">
            <h1 class="h4 fw-bold mb-2">
                <i class="fa-solid fa-shield-halved text-primary me-2"></i>
                Community Moderation
            </h1>
            <p class="text-secondary mb-0">
                Review community posts and their attachments.
                This page is read-only for now.
            </p>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @forelse ($posts as $post)
            <article class="community-card mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <strong>{{ $post->user->name }}</strong>
                        <div class="small text-secondary">
                            Member ID: {{ $post->user_id }}
                        </div>
                    </div>

                    <div class="text-end">
                        <small class="text-secondary d-block">
                            {{ $post->created_at->format('M j, Y g:i A') }}
                        </small>
                        <span class="badge bg-secondary">
                            {{ ucfirst(str_replace('_', ' ', $post->audience)) }}
                        </span>
@if ($post->hidden_at)
    <span class="badge bg-danger">Hidden</span>
@else
    <span class="badge bg-success">Visible</span>
@endif
                        
                    </div>
                </div>

                <p class="mb-0" style="white-space: pre-wrap;">{{ $post->body }}</p>

                @if ($post->mediaItems->isNotEmpty())
                    <div class="row g-3 mt-2">
                        @foreach ($post->mediaItems as $item)
                            <div class="col-6 col-md-4">
                                @if (str_starts_with($item->mime_type, 'image/'))
                                    <img
                                        src="{{ route('posts.media', ['post' => $post->id, 'mediaItem' => $item->id]) }}"
                                        alt="{{ $item->caption ?: 'Photo attached to post' }}"
                                        loading="lazy"
                                        style="display:block; width:100%; max-height:300px; object-fit:contain; border-radius:8px;"
                                    >
                                @elseif (str_starts_with($item->mime_type, 'video/'))
                                    <video
                                        controls
                                        preload="metadata"
                                        style="display:block; width:100%; max-height:300px; border-radius:8px;"
                                    >
                                        <source
                                            src="{{ route('posts.media', ['post' => $post->id, 'mediaItem' => $item->id]) }}"
                                            type="{{ $item->mime_type }}"
                                        >
                                        Your browser does not support video playback.
                                    </video>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="small text-secondary mt-3">
                    Post ID: {{ $post->id }}
                    <span class="mx-1">|</span>
                    Updated: {{ $post->updated_at->format('M j, Y g:i A') }}
                </div>
                                <div class="mt-3">
                    @if ($post->hidden_at)
                        <form method="POST" action="{{ route('moderation.posts.restore', $post) }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Restore Post
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('moderation.posts.hide', $post) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-eye-slash me-1"></i>
                                Hide Post
                            </button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="community-card">
                <p class="text-secondary mb-0">
                    There are no community posts to review yet.
                </p>
            </div>
        @endforelse

        @if ($posts->hasPages())
            <div class="mt-3">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
