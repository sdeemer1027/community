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