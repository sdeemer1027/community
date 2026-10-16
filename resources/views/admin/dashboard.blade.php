
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="h3 fw-bold text-dark mb-1">
                <i class="fa-solid fa-shield-halved text-primary me-2"></i>
                Admin Panel
            </h2>
            <p class="text-secondary mb-0">
                Manage members, moderation, roles, and community settings.
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
                Welcome, {{ Auth::user()->name }}
            </h1>

            <p class="mb-0" style="color: #eff6ff;">
                Your central place to manage and protect your community.
            </p>
        </section>

        <section class="community-card mb-4">
            <h3 class="h5 fw-bold mb-3">
                <i class="fa-solid fa-chart-simple text-primary me-2"></i>
                Community Overview
            </h3>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="text-secondary small">Total Members</div>
                        <div class="h3 fw-bold mb-0">{{ $totalMembers }}</div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="text-secondary small">Super Administrators</div>
                        <div class="h3 fw-bold mb-0">{{ $superAdmins }}</div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <div class="text-secondary small">Free Members</div>
                        <div class="h3 fw-bold mb-0">{{ $freeMembers }}</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="community-card">
            <h3 class="h5 fw-bold mb-3">
                <i class="fa-solid fa-sliders text-primary me-2"></i>
                Administration
            </h3>

            <p class="text-secondary">
                These areas will become functional as we build the administration system.
            </p>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h4 class="h6 fw-bold">Members</h4>
                        <p class="text-secondary small mb-2">
                            Review accounts and manage member access.
                        </p>
                        <span class="badge text-bg-secondary">Planned</span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h4 class="h6 fw-bold">Moderation</h4>
                        <p class="text-secondary small mb-2">
                            Handle reports, complaints, and inappropriate content.
                        </p>
                        <span class="badge text-bg-secondary">Planned</span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h4 class="h6 fw-bold">Roles & Permissions</h4>
                        <p class="text-secondary small mb-2">
                            Assign limited duties to moderators and sub-admins.
                        </p>
                        <span class="badge text-bg-secondary">Planned</span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h4 class="h6 fw-bold">Memberships</h4>
                        <p class="text-secondary small mb-2">
                            Configure Free and future paid packages separately from admin roles.
                        </p>
                        <span class="badge text-bg-secondary">Planned</span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h4 class="h6 fw-bold">Features & Integrations</h4>
                        <p class="text-secondary small mb-2">
                            Configure forums, connection chat, and external services.
                        </p>
                        <span class="badge text-bg-secondary">Planned</span>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h4 class="h6 fw-bold">Appearance & System Settings</h4>
                        <p class="text-secondary small mb-2">
                            Customize the installation, branding, and system preferences.
                        </p>
                        <span class="badge text-bg-secondary">Planned</span>
                    </div>
                </div>
            </div>
        </section>

        <p class="text-center text-secondary small mt-4 mb-0">
            Community Administration
        </p>
    </div>
    </div>
     </div>
</x-app-layout>
