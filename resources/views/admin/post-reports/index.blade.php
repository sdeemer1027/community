
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="h3 fw-bold text-dark mb-1">
                <i class="fa-solid fa-flag text-primary me-2"></i>
                Reported Posts
            </h2>
            <p class="text-secondary mb-0">
                Review member reports and protect your community.
            </p>
        </div>
    </x-slot>

    <div class="community-dashboard">
        <div class="row g-4">
            <div class="col-12 col-lg-3 col-xl-2">
                @include('components.admin.sidebar')
            </div>

            <div class="col-12 col-lg-9 col-xl-10">
                <section class="community-welcome mb-4">
                    <p class="text-uppercase fw-semibold small mb-2"
                       style="letter-spacing: 1.5px; color: #dbeafe;">
                        Community Administration
                    </p>

                    <h1 class="h3 fw-bold mb-2">
                        Report Review Center
                    </h1>

                    <p class="mb-0" style="color: #eff6ff;">
                        Review reported posts, inspect attachments, and record your decisions.
                        Reviewing a report does not automatically hide or delete its post.
                    </p>
                </section>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @forelse ($reports as $report)
                    <article class="community-card mb-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                            <div>
                                <strong>Report #{{ $report->id }}</strong>
                                <div class="small text-secondary">
                                    Submitted {{ $report->created_at->format('M j, Y g:i A') }}
                                </div>
                            </div>

                            @if ($report->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending Review</span>
                            @else
                                <span class="badge bg-success">Reviewed</span>
                            @endif
                        </div>

                        <div class="border rounded-3 p-3 mb-3">
                            <h2 class="h6 fw-bold mb-2">Report Details</h2>

                            <p class="mb-1">
                                <strong>Reported by:</strong>
                                {{ $report->reporter->name ?? 'Account no longer available' }}
                            </p>

                            <p class="mb-1">
                                <strong>Reason:</strong>
                                {{ ucfirst(str_replace('_', ' ', $report->reason)) }}
                            </p>

                            <p class="mb-0">
                                <strong>Additional details:</strong>
                                {{ $report->details ?: 'No additional details provided.' }}
                            </p>
                        </div>

                        <div class="border rounded-3 p-3">
                            <h2 class="h6 fw-bold mb-2">Reported Post</h2>

                            @if ($report->post)
                                <p class="mb-2">
                                    <strong>Author:</strong>
                                    {{ $report->post->user->name ?? 'Account no longer available' }}
                                </p>

                                <p class="mb-2" style="white-space: pre-wrap;">{{ $report->post->body }}</p>

                                @if ($report->post->mediaItems->isNotEmpty())
                                    <div class="row g-3 mt-2">
                                        @foreach ($report->post->mediaItems as $item)
                                            <div class="col-12 col-md-6">
                                                @if (str_starts_with($item->mime_type, 'image/'))
                                                    <img
                                                        src="{{ route('posts.media', ['post' => $report->post->id, 'mediaItem' => $item->id]) }}"
                                                        alt="{{ $item->caption ?: 'Image attached to reported post' }}"
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
                                                            src="{{ route('posts.media', ['post' => $report->post->id, 'mediaItem' => $item->id]) }}"
                                                            type="{{ $item->mime_type }}"
                                                        >
                                                        Your browser does not support video playback.
                                                    </video>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="small text-secondary mt-2 mb-0">
                                        This post has no attached images or videos.
                                    </p>
                                @endif

                                <p class="small text-secondary mt-3 mb-0">
                                    Post ID: {{ $report->post_id }}
                                </p>

                                @if ($report->post->hidden_at)
                                    <span class="badge bg-danger mt-2">Post Hidden</span>
                                @else
                                    <span class="badge bg-success mt-2">Post Visible</span>
                                @endif

                                @if (auth()->user()->hasPermission('posts.moderate'))
                                    <div class="mt-3 pt-3 border-top">
                                        @if ($report->post->hidden_at)
                                            <form
                                                method="POST"
                                                action="{{ route('moderation.posts.restore', $report->post) }}"
                                                onsubmit="return confirm('Restore this post so it can appear in the community feed again?');"
                                            >
                                                @csrf

                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fa-solid fa-eye me-1"></i>
                                                    Restore Post
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                method="POST"
                                                action="{{ route('moderation.posts.hide', $report->post) }}"
                                                onsubmit="return confirm('Hide this post from the community feed?');"
                                            >
                                                @csrf

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-eye-slash me-1"></i>
                                                    Hide Post
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <p class="text-secondary mb-0">
                                    The original post is no longer available.
                                </p>
                            @endif
                        </div>

                        @if ($report->status === 'pending')
                            <form
                                method="POST"
                                action="{{ route('admin.post-reports.review', $report) }}"
                                class="mt-3"
                            >
                                @csrf

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-check me-1"></i>
                                    Mark as Reviewed
                                </button>
                            </form>
                        @else
                            <div class="small text-secondary mt-3">
                                <strong>Reviewed by:</strong>
                                {{ $report->reviewer->name ?? 'Account no longer available' }}

                                @if ($report->reviewed_at)
                                    <span class="mx-1">|</span>
                                    {{ $report->reviewed_at->format('M j, Y g:i A') }}
                                @endif
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="community-card">
                        <p class="text-secondary mb-0">
                            No member reports have been submitted yet.
                        </p>
                    </div>
                @endforelse

                @if ($reports->hasPages())
                    <div class="mt-3">
                        {{ $reports->links() }}
                    </div>
                @endif

                <p class="text-center text-secondary small mt-4 mb-0">
                    Community Administration
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
