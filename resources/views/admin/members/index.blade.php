
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 class="h3 fw-bold text-dark mb-1">
                    <i class="fa-solid fa-users text-primary me-2"></i>
                    Member Management
                </h2>
                <p class="text-secondary mb-0">
                    Search and review registered community members.
                </p>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Admin Overview
            </a>
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
                Administration
            </p>

            <h1 class="h3 fw-bold mb-2">Community Members</h1>

            <p class="mb-0" style="color: #eff6ff;">
                Review accounts and membership levels. No member information is changed from this page.
            </p>
        </section>

        <section class="community-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <h3 class="h5 fw-bold mb-1">Registered Members</h3>
                    <p class="text-secondary small mb-0">
                        {{ $members->total() }} member(s) found
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.members.index') }}" class="mb-4">
                <div class="row g-2">
                    <div class="col-12 col-md-9">
                        <label for="search" class="form-label">Search members</label>
                        <input
                            type="search"
                            name="search"
                            id="search"
                            value="{{ $search }}"
                            class="form-control"
                            placeholder="Name, email, or ZIP code"
                        >
                    </div>

                    <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass me-1"></i>
                            Search
                        </button>

                        <a href="{{ route('admin.members.index') }}"
                           class="btn btn-outline-secondary">
                            Clear
                        </a>
                    </div>
                </div>
            </form>

            @if ($members->isEmpty())
                <div class="alert alert-info mb-0">
                    No members matched your search.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Member</th>
                                <th scope="col">ZIP Code</th>
                                <th scope="col">Membership</th>
                                <th scope="col">Admin Access</th>
                                <th scope="col">Joined</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $member->name }}
                                        </div>
                                        <div class="small text-secondary text-break">
                                            {{ $member->email }}
                                        </div>
                                    </td>

                                    <td>{{ $member->zip_code ?: '—' }}</td>

                                    <td>
                                        <span class="badge text-bg-secondary">
                                            {{ ucfirst($member->membership_level ?: 'free') }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($member->is_super_admin)
                                            <span class="badge text-bg-primary">
                                                Super Admin
                                            </span>
                                        @else
                                            <span class="text-secondary small">
                                                Member
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-nowrap">
                                        {{ $member->created_at?->format('M j, Y') ?? '—' }}
                                    </td>

                                    <td class="text-nowrap">
    <a href="{{ route('admin.members.show', $member) }}"
       class="btn btn-sm btn-outline-primary">
        <i class="fa-solid fa-eye me-1"></i>
        View Details
    </a>
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $members->links() }}
                </div>
            @endif
        </section>
    </div>
     </div>
      </div>
</x-app-layout>
