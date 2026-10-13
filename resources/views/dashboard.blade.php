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

    <style>
        .community-dashboard {
            max-width: 1600px;
            margin: 0 auto;
            padding: 24px 20px 90px;
        }

        .community-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .community-welcome {
            background: linear-gradient(120deg, #1d4ed8, #3b82f6);
            color: #ffffff;
            border-radius: 14px;
            padding: 28px;
            margin-bottom: 24px;
        }

        .community-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(180px, 240px)
                                   minmax(0, 1fr)
                                   minmax(220px, 280px);
            gap: 20px;
            align-items: start;
        }

        .community-sidebar,
        .community-main {
            min-width: 0;
        }

        .community-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .community-main {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .community-quick-link {
            display: block;
            width: 100%;
            text-align: left;
            text-decoration: none;
            padding: 11px 12px;
            margin-bottom: 9px;
            border: 1px solid #dbe3ee;
            border-radius: 9px;
            color: #334155;
            background: #ffffff;
            transition: background 0.15s ease, border-color 0.15s ease;
        }

        .community-quick-link:last-child {
            margin-bottom: 0;
        }

        .community-quick-link:hover {
            background: #eff6ff;
            border-color: #93c5fd;
            color: #1d4ed8;
        }

        .community-getting-started-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 14px;
            margin-top: 12px;
        }

        .community-post {
            overflow-wrap: anywhere;
        }

        .community-post + .community-post {
            margin-top: 14px;
        }

        .community-fixed-footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
            min-height: 48px;
            padding: 12px 20px;
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 -2px 8px rgba(15, 23, 42, 0.05);
        }

        @media (max-width: 1050px) {
            .community-dashboard-grid {
                grid-template-columns: minmax(170px, 220px) minmax(0, 1fr);
            }

            .community-right-sidebar {
                grid-column: 1 / -1;
            }

            .community-right-sidebar .community-getting-started-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 12px;
            }

            .community-right-sidebar .community-getting-started-item {
                margin-top: 12px;
            }
        }

        @media (max-width: 700px) {
            .community-dashboard {
                padding: 16px 12px 85px;
            }

            .community-dashboard-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 16px;
            }

            .community-right-sidebar {
                grid-column: auto;
            }

            .community-right-sidebar .community-getting-started-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 0;
            }

            .community-welcome {
                padding: 22px;
            }

            .community-card {
                padding: 17px;
            }

            .community-fixed-footer {
                padding: 12px;
            }
        }
    </style>

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

        {{-- Three-column dashboard --}}
        <div class="community-dashboard-grid">

            {{-- LEFT COLUMN: Quick Links --}}
            <aside class="community-sidebar">
                <section class="community-card">
                    <h3 class="h5 fw-bold mb-3">
                        <i class="fa-solid fa-compass text-primary me-2"></i>
                        Quick Links
                    </h3>

                    <a href="{{ route('members.index') }}"
                       class="community-quick-link">
                        <i class="fa-solid fa-users me-2"></i>
                        Find Members
                    </a>

                    <a href="{{ route('connections.index') }}"
                       class="community-quick-link">
                        <i class="fa-solid fa-envelope me-2"></i>
                        Connection Requests
                    </a>

                    <a href="{{ route('my-connections.index') }}"
                       class="community-quick-link">
                        <i class="fa-solid fa-handshake me-2"></i>
                        My Connections
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="community-quick-link">
                        <i class="fa-solid fa-user-gear me-2"></i>
                        My Profile
                    </a>
                </section>
            </aside>

            {{-- CENTER COLUMN: Create Post and Community Feed --}}
            <main class="community-main">

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

            </main>

            {{-- RIGHT COLUMN: Getting Started --}}
            <aside class="community-sidebar community-right-sidebar">
                <section class="community-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-3 p-3 me-3"
                             style="background: #dbeafe; color: #1d4ed8;">
                            <i class="fa-solid fa-compass fa-xl"></i>
                        </div>

                        <div>
                            <h3 class="h5 fw-bold mb-1">Getting Started</h3>
                            <p class="text-secondary small mb-0">
                                A few ways to get involved.
                            </p>
                        </div>
                    </div>

                    <div class="community-getting-started-grid">

                        <div class="community-getting-started-item">
                            <h4 class="h6 fw-bold">
                                <i class="fa-solid fa-id-card text-primary me-2"></i>
                                Complete Your Profile
                            </h4>

                            <p class="small text-secondary mb-3">
                                Add your ZIP code and update your personal information.
                            </p>

                            <a href="{{ route('profile.edit') }}"
                               class="btn btn-outline-primary btn-sm">
                                Edit Profile
                            </a>
                        </div>

                        <div class="community-getting-started-item">
                            <h4 class="h6 fw-bold">
                                <i class="fa-solid fa-magnifying-glass text-primary me-2"></i>
                                Explore the Directory
                            </h4>

                            <p class="small text-secondary mb-3">
                                Discover registered members by name or ZIP code.
                            </p>

                            <a href="{{ route('members.index') }}"
                               class="btn btn-outline-primary btn-sm">
                                Browse Members
                            </a>
                        </div>

                        <div class="community-getting-started-item">
                            <h4 class="h6 fw-bold">
                                <i class="fa-solid fa-user-plus text-primary me-2"></i>
                                Build Connections
                            </h4>

                            <p class="small text-secondary mb-3">
                                Send requests and choose which members to connect with.
                            </p>

                            <a href="{{ route('connections.index') }}"
                               class="btn btn-outline-primary btn-sm">
                                View Requests
                            </a>
                        </div>

                    </div>
                </section>
            </aside>

        </div>

        <p class="text-center text-secondary small mt-4 mb-0">
            Community &mdash; bringing neighbors, friends, and family closer together.
        </p>
    </div>

    {{-- Fixed footer: space reserved for future footer notes --}}
    <footer class="community-fixed-footer">
        {{-- Footer notes can be added here later. --}}
    </footer>
</x-app-layout>
