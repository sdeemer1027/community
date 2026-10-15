
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-3">
            <div>
                <h2 class="h3 fw-bold text-dark mb-1">
                    <i class="fa-solid fa-house text-primary me-2"></i>
                    Community Dashboard
                </h2>
                <p class="text-secondary mb-0">
                    Your place to connect with neighbors, friends, and family.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="community-dashboard">

        {{-- Welcome banner --}}
        <section class="community-welcome">
            <p class="text-uppercase fw-semibold small mb-2"
               style="letter-spacing: 1.5px; color: #dbeafe;">
                Welcome to Community
            </p>

            <h1 class="h3 fw-bold mb-2">
                Hello, {{ Auth::user()->name }}!
            </h1>

            <p class="mb-0" style="color: #eff6ff;">
                Build meaningful connections with people who matter to you.
            </p>
        </section>

        {{-- Free Member Usage --}}
        @if (Auth::user()->membership_level === 'free')
            <section class="community-card mb-4">
                <h3 class="h5 fw-bold mb-3">
                    <i class="fa-solid fa-chart-simple text-primary me-2"></i>
                    Your Free Member Usage
                </h3>

                <div class="row g-3">

                    {{-- Posts Today --}}
                    <div class="col-12 col-md-4">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-secondary small">
                                Posts Today
                            </div>

                            <div class="h4 fw-bold mb-1">
                                {{ $postsToday }} / 5
                            </div>

                            <div class="progress"
                                 role="progressbar"
                                 aria-label="Daily posts used"
                                 aria-valuenow="{{ min($postsToday, 5) }}"
                                 aria-valuemin="0"
                                 aria-valuemax="5">
                                <div class="progress-bar"
                                     style="width: {{ min(($postsToday / 5) * 100, 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Media Storage --}}
                    <div class="col-12 col-md-4">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-secondary small">
                                Media Storage
                            </div>

                            <div class="h4 fw-bold mb-1">
                                {{ number_format($mediaStorageBytes / (1024 * 1024), 1) }}
                                / {{ $storageLimitMb }} MB
                            </div>

                            <div class="progress"
                                 role="progressbar"
                                 aria-label="Media storage used"
                                 aria-valuenow="{{ min(($mediaStorageBytes / max($storageLimitMb * 1024 * 1024, 1)) * 100, 100) }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                <div class="progress-bar"
                                     style="width: {{ min(($mediaStorageBytes / max($storageLimitMb * 1024 * 1024, 1)) * 100, 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Accepted Connections --}}
                    <div class="col-12 col-md-4">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-secondary small">
                                Accepted Connections
                            </div>

                            <div class="h4 fw-bold mb-1">
                                {{ $acceptedConnections }} / {{ $connectionLimit }}
                            </div>

                            <div class="progress"
                                 role="progressbar"
                                 aria-label="Accepted connections used"
                                 aria-valuenow="{{ min($acceptedConnections, $connectionLimit) }}"
                                 aria-valuemin="0"
                                 aria-valuemax="{{ $connectionLimit }}">
                                <div class="progress-bar"
                                     style="width: {{ min(($acceptedConnections / max($connectionLimit, 1)) * 100, 100) }}%">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <p class="text-secondary small mt-3 mb-0">
                    Your daily post allowance resets at midnight Eastern Time.
                    Upgrade your membership for additional features.
                </p>
            </section>
        @endif

        {{-- Shared left sidebar, center content, and right sidebar --}}
        <x-community-layout>

            {{-- Create a Post --}}
            <section class="community-card">
                <h3 class="h5 fw-bold mb-3">
                    <i class="fa-solid fa-pen-to-square text-primary me-2"></i>
                    Share an Update
                </h3>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="body" class="form-label">
                            What's on your mind?
                        </label>

                        <textarea
                            id="body"
                            name="body"
                            class="form-control"
                            rows="4"
                            maxlength="5000"
                            placeholder="Share an update with your community..."
                            required>{{ old('body') }}</textarea>

                        @error('body')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="audience" class="form-label">
                            Who can see this post?
                        </label>

                        <select id="audience"
                                name="audience"
                                class="form-select"
                                required>
                            <option value="only_me"
                                {{ old('audience', 'only_me') === 'only_me' ? 'selected' : '' }}>
                                Only Me — private
                            </option>

                            <option value="friends"
                                {{ old('audience') === 'friends' ? 'selected' : '' }}>
                                Friends — accepted connections
                            </option>

                            @if (Auth::user()->membership_level !== 'free')
                                <option value="family"
                                    {{ old('audience') === 'family' ? 'selected' : '' }}>
                                    Family — designated family members
                                </option>

                                <option value="public"
                                    {{ old('audience') === 'public' ? 'selected' : '' }}>
                                    Public — everyone
                                </option>
                            @endif
                        </select>

                        @error('audience')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                        <small class="text-secondary">
                            New posts are private by default. You can choose a different audience.
                        </small>
                    </div>

                    {{-- Select media from the user's library --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fa-solid fa-photo-film me-2"></i>
                            Add Photos or Videos
                        </label>

                        @error('media')
                            <div class="text-danger small mb-2">
                                {{ $message }}
                            </div>
                        @enderror

                        @if ($mediaItems->isNotEmpty())
                            <p class="text-secondary small">
                                Select one or more items from your media library.
                            </p>

                            <div class="row g-3">
                                @foreach ($mediaItems as $item)
                                    <div class="col-6 col-md-4">
                                        <label class="border rounded-3 p-2 d-block h-100"
                                               style="cursor: pointer;">

                                            <input
                                                type="checkbox"
                                                name="media[]"
                                                value="{{ $item->id }}"
                                                class="form-check-input me-2"
                                                {{ in_array($item->id, old('media', [])) ? 'checked' : '' }}
                                            >

                                            @if (str_starts_with($item->mime_type, 'image/'))
                                                <img
                                                    src="{{ route('media-library.file', $item) }}"
                                                    alt="{{ $item->caption ?: 'My photo' }}"
                                                    loading="lazy"
                                                    style="width:100%; max-height:140px; object-fit:contain;"
                                                >
                                            @elseif (str_starts_with($item->mime_type, 'video/'))
                                                <video
                                                    controls
                                                    preload="metadata"
                                                    style="width:100%; max-height:140px;"
                                                >
                                                    <source
                                                        src="{{ route('media-library.file', $item) }}"
                                                        type="{{ $item->mime_type }}"
                                                    >
                                                </video>
                                            @endif

                                            <small class="d-block mt-2 text-break">
                                                {{ $item->caption ?: $item->original_filename }}
                                            </small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-secondary small">
                                Your media library is empty. Upload photos or videos in
                                <a href="{{ route('media-library.index') }}">
                                    My Media Library
                                </a>
                                first.
                            </p>
                        @endif

                        @error('media.*')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane me-2"></i>
                        Publish Update
                    </button>
                </form>
            </section>

            {{-- Community Feed --}}
            <section class="community-card" id="community-feed">
                <h3 class="h5 fw-bold mb-3">
                    <i class="fa-solid fa-newspaper text-primary me-2"></i>
                    Community Updates
                </h3>

                @forelse ($posts as $post)
                    <article class="community-post p-3 rounded-3 border
                        {{ in_array($post->user_id, $connectedUserIds) ? 'border-primary border-3' : 'border-secondary-subtle' }}">

                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <strong>{{ $post->user->name }}</strong>

                            <small class="text-secondary">
                                {{ $post->created_at->format('M j, Y g:i A') }}
                            </small>
                        </div>

                        <p class="mb-0" style="white-space: pre-wrap;">{{ $post->body }}</p>

                        {{-- Post attachments --}}
                        @if ($post->mediaItems->isNotEmpty())
                            <div class="row g-3 mt-2">
                                @foreach ($post->mediaItems as $item)
                                    <div class="col-6 col-md-6">
                                        @if (str_starts_with($item->mime_type, 'image/'))
                                            <img
                                                src="{{ route('posts.media', ['post' => $post->id, 'mediaItem' => $item->id]) }}"
                                                alt="{{ $item->caption ?: 'Photo attached to post' }}"
                                                loading="lazy"
                                                style="display:block; width:100%; max-height:400px; object-fit:contain; border-radius:8px;"
                                            >
                                        @elseif (str_starts_with($item->mime_type, 'video/'))
                                            <video
                                                controls
                                                preload="metadata"
                                                style="display:block; width:100%; max-height:400px; border-radius:8px;"
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

                        @if (in_array($post->user_id, $connectedUserIds))
                            <small class="text-primary d-block mt-2">
                                <i class="fa-solid fa-handshake me-1"></i>
                                Your connection
                            </small>
                        @endif
                    </article>
                @empty
                    <p class="text-secondary mb-0">
                        There are no community updates to show yet.
                        Be the first to share an update!
                    </p>
                @endforelse

                @if ($posts->hasPages())
                    <div class="mt-3">
                        {{ $posts->links() }}
                    </div>
                @endif
            </section>

        </x-community-layout>

        <p class="text-center text-secondary small mt-4 mb-0">
            Community &mdash; bringing neighbors, friends, and family closer together.
        </p>
    </div>
</x-app-layout>
