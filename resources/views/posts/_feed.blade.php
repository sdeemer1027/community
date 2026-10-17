
{{-- Community Feed --}}
<section class="community-card" id="community-feed">
    <h3 class="h5 fw-bold mb-3">
        <i class="fa-solid fa-newspaper text-primary me-2"></i>
        Community Updates
    </h3>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @forelse ($posts as $post)
        <article class="community-post p-3 rounded-3 border mb-3
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

            {{-- Report posts created by other members --}}
            @if ($post->user_id !== Auth::id())
                <div class="mt-3">
                    <details>
                        <summary class="text-secondary small" style="cursor:pointer;">
                            <i class="fa-solid fa-flag me-1"></i>
                            Report Post
                        </summary>

                        <form
                            method="POST"
                            action="{{ route('posts.report', $post) }}"
                            class="mt-3"
                        >
                            @csrf

                            <div class="mb-3">
                                <label
                                    for="report_reason_{{ $post->id }}"
                                    class="form-label"
                                >
                                    Why are you reporting this post?
                                </label>

                                <select
                                    id="report_reason_{{ $post->id }}"
                                    name="reason"
                                    class="form-select"
                                    required
                                >
                                    <option value="">Choose a reason</option>
                                    <option value="spam">Spam or unwanted advertising</option>
                                    <option value="harassment">Harassment or bullying</option>
                                    <option value="inappropriate">Inappropriate content</option>
                                    <option value="false_information">False or misleading information</option>
                                    <option value="other">Other concern</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label
                                    for="report_details_{{ $post->id }}"
                                    class="form-label"
                                >
                                    Additional details (optional)
                                </label>

                                <textarea
                                    id="report_details_{{ $post->id }}"
                                    name="details"
                                    class="form-control"
                                    rows="3"
                                    maxlength="1000"
                                    placeholder="Tell the moderators anything they should know."
                                ></textarea>
                            </div>

                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-flag me-1"></i>
                                Submit Report
                            </button>
                        </form>
                    </details>
                </div>
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
