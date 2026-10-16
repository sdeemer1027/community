
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 fw-semibold">
            <i class="fa-solid fa-user me-2"></i>
            Member Details
        </h2>
    </x-slot>

    <div class="community-dashboard">
        <div class="row g-4">
            <div class="col-12 col-lg-3 col-xl-2">
                @include('components.admin.sidebar')
            </div>

            <div class="col-12 col-lg-9 col-xl-10">
                <div class="community-card">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                        <div>
                            <h3 class="h5 fw-bold mb-1">
                                {{ $member->name }}
                            </h3>
                            <div class="text-secondary small">
                                Member ID: {{ $member->id }}
                            </div>
                        </div>

                        <a href="{{ route('admin.members.index') }}"
                           class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Back to Members
                        </a>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">Full Name</div>
                                <div class="fw-semibold">{{ $member->name ?: '—' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">Email Address</div>
                                <div class="fw-semibold">{{ $member->email ?: '—' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">ZIP / Postal Code</div>
                                <div>{{ $member->zip_code ?: '—' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">City</div>
                                <div>{{ $member->city ?: '—' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">State</div>
                                <div>{{ $member->state ?: '—' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">Phone</div>
                                <div>{{ $member->phone ?: '—' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">Gender</div>
                                <div>{{ $member->gender ?: '—' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">Birthdate</div>
                                <div>
                                    {{ $member->birthdate?->format('M j, Y') ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">Membership Level</div>
                                <div>{{ $member->membership_level ?: 'free' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">Administrator</div>
                                <div>
                                    {{ $member->is_super_admin ? 'Super Admin' : 'Member' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-secondary mb-1">Date Joined</div>
                                <div>
                                    {{ $member->created_at?->format('M j, Y') ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="border rounded-3 p-3">
                                <div class="small text-secondary mb-1">About / Bio</div>
                                <div class="text-break">{{ $member->bio ?: '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
