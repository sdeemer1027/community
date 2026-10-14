<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center">
            <div class="rounded-3 p-3 me-3" style="background: #fef3c7; color: #92400e;">
                <i class="fa-solid fa-envelope fa-xl"></i>
            </div>
            <div>
                <h2 class="h3 fw-bold text-dark mb-1">Connection Requests</h2>
                <p class="text-secondary mb-0">Manage the people who want to connect with you.</p>
            </div>
        </div>
    </x-slot>

    <div class="community-dashboard">

        <x-community-layout>

            {{-- Introduction --}}
            <section class="community-card">
                <h3 class="h5 fw-bold mb-2">
                    <i class="fa-solid fa-user-group text-primary me-2"></i>
                    Incoming Requests
                </h3>
                <p class="text-secondary mb-0">
                    People who want to connect with you appear here.
                    Accept requests from people you want to add to your community,
                    or decline requests you do not wish to accept.
                </p>
            </section>

            {{-- Status messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->has('connection'))
                <div class="alert alert-danger" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    {{ $errors->first('connection') }}
                </div>
            @endif

            {{-- Request count --}}
            <section class="community-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h3 class="h5 fw-bold mb-0">Pending Requests</h3>
                    <span class="badge rounded-pill text-bg-primary">
                        {{ $requests->count() }}
                        {{ $requests->count() === 1 ? 'request' : 'requests' }}
                    </span>
                </div>

                @if ($requests->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3" style="color: #94a3b8;">
                            <i class="fa-solid fa-envelope-open-text fa-3x"></i>
                        </div>
                        <h4 class="h5 fw-bold">You're all caught up!</h4>
                        <p class="text-secondary mb-0">
                            You have no pending connection requests right now.
                            New requests will appear here.
                        </p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($requests as $connection)
                            <div class="col-12 col-md-6">
                                <article class="community-card h-100">
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($connection->requester->profile_photo_path)
                                            <img
                                                src="{{ asset('storage/' . $connection->requester->profile_photo_path) }}"
                                                alt="{{ $connection->requester->name }}'s profile photo"
                                                class="rounded-circle flex-shrink-0"
                                                style="width: 64px; height: 64px; object-fit: cover;"
                                            >
                                        @else
                                            <div
                                                class="rounded-circle bg-light border d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width: 64px; height: 64px;"
                                            >
                                                <i class="fa-solid fa-user text-secondary fa-xl"></i>
                                            </div>
                                        @endif

                                        <div class="flex-grow-1 min-w-0">
                                            <h4 class="h5 fw-bold mb-1 text-break">
                                                {{ $connection->requester->name }}
                                            </h4>
                                            <p class="text-secondary small mb-0">
                                                Wants to connect with you.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mt-4">
                                        <form method="POST"
                                              action="{{ route('connections.accept', $connection) }}"
                                              class="flex-grow-1">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fa-solid fa-check me-2"></i>
                                                Accept
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('connections.decline', $connection) }}"
                                              class="flex-grow-1">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                <i class="fa-solid fa-xmark me-2"></i>
                                                Decline
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </x-community-layout>

    </div>
</x-app-layout>