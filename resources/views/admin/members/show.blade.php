
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

    
<div class="community-card mt-4 mb-5">
    <h3 class="h5 fw-bold mb-2">Role Assignment</h3>

    <p class="text-secondary">
        Select the roles this member should have. Saving will replace
        their current role assignments with the selections below.
    </p>

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

    @if ($errors->any())
        <div class="alert alert-danger">
            Please correct the submitted information and try again.
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.members.roles.update', $member) }}">
        @csrf
        @method('PUT')

        @php
            $selectedRoleIds = old(
                'role_ids',
                $member->roles->pluck('id')->all()
            );
        @endphp

        @foreach ($roles as $role)
            @if ($role->name !== 'super_admin')
                <div class="form-check mb-3">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="role_ids[]"
                        value="{{ $role->id }}"
                        id="role_{{ $role->id }}"
                        @checked(in_array($role->id, $selectedRoleIds))
                    >

                    <label class="form-check-label"
                           for="role_{{ $role->id }}">
                        <strong>{{ $role->display_name }}</strong>

                        @if ($role->description)
                            <div class="small text-secondary">
                                {{ $role->description }}
                            </div>
                        @endif
                    </label>
                </div>
            @endif
        @endforeach

        <button type="submit" class="btn btn-primary">
            Save Role Assignments
        </button>
    </form>
</div>

</x-app-layout>
