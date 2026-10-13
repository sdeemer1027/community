 <x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center">
            <div class="rounded-3 p-3 me-3" style="background: #dbeafe; color: #1d4ed8;">
                <i class="fa-solid fa-users fa-xl"></i>
            </div>
            <div>
                <h2 class="h3 fw-bold text-dark mb-1">Find Members</h2>
                <p class="text-secondary mb-0">Discover neighbors, friends, and family.</p>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid px-3 px-md-4 py-4">
        <div class="mx-auto" style="max-width: 1200px;">

            {{-- Introduction --}}
            <div class="community-card mb-4">
                <h3 class="h5 fw-bold mb-2">
                    <i class="fa-solid fa-magnifying-glass text-primary me-2"></i>
                    Find Your Community
                </h3>
                <p class="text-secondary mb-0">
                    Search registered members by name or ZIP code. You decide
                    whom to contact by sending a connection request.
                </p>
            </div>

            {{-- Search --}}
            <div class="community-card mb-4">
                <form method="GET" action="{{ route('members.index') }}">
                    <label for="member-search" class="form-label fw-semibold">
                        Search members
                    </label>

                    <div class="row g-2">
                        <div class="col-12 col-md">
                            <input
                                id="member-search"
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Enter a name or ZIP code"
                                class="form-control form-control-lg"
                            >
                        </div>

                        <div class="col-6 col-md-auto">
                            <button type="submit" class="btn btn-community btn-lg w-100">
                                <i class="fa-solid fa-magnifying-glass me-2"></i>
                                Search
                            </button>
                        </div>

                        <div class="col-6 col-md-auto">
                            <a href="{{ route('members.index') }}"
                               class="btn btn-outline-secondary btn-lg w-100">
                                <i class="fa-solid fa-rotate-left me-2"></i>
                                Show All
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Success and error messages --}}
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

            
            {{-- Member results --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h3 class="h5 fw-bold mb-0">Member Directory</h3>
                <span class="text-secondary small">
                    @if ($members->total() === 1)
                        1 member found
                    @else
                        {{ $members->total() }} members found
                    @endif
                </span>
            </div>

            @if ($members->isEmpty())
                <div class="community-card text-center py-5">
                    <div class="mb-3" style="color: #94a3b8;">
                        <i class="fa-solid fa-user-group fa-3x"></i>
                    </div>
                    <h4 class="h5 fw-bold">No members found</h4>
                    <p class="text-secondary mb-3">
                        Try a different name or ZIP code.
                    </p>
                    <a href="{{ route('members.index') }}" class="btn btn-outline-primary">
                        Show All Members
                    </a>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($members as $member)
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="community-card h-100 d-flex flex-column">
                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        @if ($member->profile_photo_path)
                                            <img
                                                src="{{ asset('storage/' . $member->profile_photo_path) }}"
                                                alt="{{ $member->name }}'s profile photo"
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

                                        <h4 class="h6 fw-bold mb-0 text-break">
                                            {{ $member->name }}
                                        </h4>
                                    </div>

                                    <p class="text-secondary small mb-0">
                                        <i class="fa-solid fa-location-dot me-2"></i>
                                        ZIP code: {{ $member->zip_code ?: 'Not provided' }}
                                    </p>
                                </div>

                                <div class="mt-auto">
                                    <form method="POST" action="{{ route('connections.store') }}">
                                        @csrf
                                        <input
                                            type="hidden"
                                            name="recipient_id"
                                            value="{{ $member->id }}"
                                        >

                                        <button type="submit" class="btn btn-community w-100">
                                            <i class="fa-solid fa-user-plus me-2"></i>
                                            Request Connection
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $members->links() }}
                </div>
            @endif

            <p class="text-center text-secondary small mt-4 mb-0">
                Community — bringing neighbors, friends, and family closer together.
            </p>

        </div>
    </div>
</x-app-layout>
