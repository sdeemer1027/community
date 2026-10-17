<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 fw-semibold">
            <i class="fa-solid fa-user-lock me-2"></i>
            Roles &amp; Permissions
        </h2>
    </x-slot>

    <div class="community-dashboard">
        <div class="row g-4">
            <div class="col-12 col-lg-3 col-xl-2">
                @include('components.admin.sidebar')
            </div>

            <div class="col-12 col-lg-9 col-xl-10">
                <div class="community-card mb-4">
                    <h3 class="h5 fw-bold">Role Management</h3>
                    <p class="text-secondary mb-0">
                        Review the roles assigned to your community members
                        and the permissions available to each role.
                    </p>
                </div>

                <div class="row g-4">
                    @forelse ($roles as $role)
                        <div class="col-12 col-xl-6">
                            <div class="community-card h-100">
                                <div class="d-flex justify-content-between
                                            align-items-start gap-3 mb-3">
                                    <div>
                                        <h3 class="h5 fw-bold mb-1">
                                            {{ $role->display_name }}
                                        </h3>

                                        <div class="small text-secondary">
                                            {{ $role->name }}
                                        </div>
                                    </div>

                                    <span class="badge text-bg-light">
                                        {{ $role->users_count }}
                                        {{ $role->users_count === 1
                                            ? 'user' : 'users' }}
                                    </span>
                                </div>

                                <p class="text-secondary">
                                    {{ $role->description }}
                                </p>

                                <hr>

                                <h4 class="h6 fw-bold">
                                    Assigned Permissions
                                </h4>

                                @if ($role->permissions->isNotEmpty())
                                    <ul class="list-group list-group-flush">
                                        @foreach ($role->permissions as $permission)
                                            <li class="list-group-item px-0">
                                                <div class="fw-semibold">
                                                    {{ $permission->display_name }}
                                                </div>

                                                <div class="small text-secondary">
                                                    {{ $permission->name }}
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-secondary mb-0">
                                        No permissions assigned.
                                    </p>
                                @endif

                                @if ($role->is_system_role)
                                    <div class="mt-3">
                                        <span class="badge text-bg-secondary">
                                            System Role
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="community-card">
                                No roles have been created yet.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
