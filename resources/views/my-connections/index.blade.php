<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center">
            <div class="rounded-3 p-3 me-3" style="background: #dcfce7; color: #166534;">
                <i class="fa-solid fa-handshake fa-xl"></i>
            </div>
            <div>
                <h2 class="h3 fw-bold text-dark mb-1">My Connections</h2>
                <p class="text-secondary mb-0">The people you've chosen to connect with.</p>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid px-3 px-md-4 py-4">
        <div class="mx-auto" style="max-width: 1100px;">

            {{-- Introduction --}}
            <div class="community-card mb-4">
                <h3 class="h5 fw-bold mb-2">
                    <i class="fa-solid fa-users text-primary me-2"></i>
                    People You Are Connected With
                </h3>
                <p class="text-secondary mb-0">
                    These members have accepted your connection request,
                    or you accepted theirs. Your community grows one connection at a time.
                </p>
            </div>

            {{-- Connection count --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h3 class="h5 fw-bold mb-0">Your Community</h3>
                <span class="badge rounded-pill text-bg-success">
                    {{ $connections->count() }}
                    {{ $connections->count() === 1 ? 'connection' : 'connections' }}
                </span>
            </div>

            @if ($connections->isEmpty())
                <div class="community-card text-center py-5">
                    <div class="mb-3" style="color: #94a3b8;">
                        <i class="fa-solid fa-handshake-angle fa-3x"></i>
                    </div>
                    <h4 class="h5 fw-bold">Your community starts here</h4>
                    <p class="text-secondary mb-3">
                        You don't have any accepted connections yet.
                        Browse the member directory to find people you'd like to connect with.
                    </p>
                    <a href="{{ route('members.index') }}" class="btn btn-community">
                        <i class="fa-solid fa-user-plus me-2"></i>
                        Find Members
                    </a>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($connections as $connection)
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="community-card h-100">
                                <div class="d-flex align-items-center gap-3">
                                    
                                    <div class="flex-grow-1 min-w-0">
                                        <h4 class="h6 fw-bold mb-2 text-break">
                                            


@php
    $connectedMember = $connection->requester_id === $userId
        ? $connection->recipient
        : $connection->requester;
@endphp

<div class="d-flex align-items-center gap-3">
    @if ($connectedMember->profile_photo_path)
        <img
            src="{{ asset('storage/' . $connectedMember->profile_photo_path) }}"
            alt="{{ $connectedMember->name }}'s profile photo"
            class="rounded-circle"
            style="width: 64px; height: 64px; object-fit: cover;"
        >
    @else
        <div
            class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
            style="width: 64px; height: 64px; flex-shrink: 0;"
        >
            <i class="fa-solid fa-user text-secondary fa-xl"></i>
        </div>
    @endif

    <span>{{ $connectedMember->name }}</span>
</div>




                                        </h4>

                                        <span class="badge rounded-pill text-bg-success">
                                            <i class="fa-solid fa-circle-check me-1"></i>
                                            Connected
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <p class="text-center text-secondary small mt-4 mb-0">
                Community — bringing neighbors, friends, and family closer together.
            </p>

        </div>
    </div>
</x-app-layout>