<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 p-3" style="background: #dbeafe; color: #1d4ed8;">
                <i class="fa-solid fa-user-gear fa-xl"></i>
            </div>
            <div>
                <h2 class="h3 fw-bold text-dark mb-1">My Profile</h2>
                <p class="text-secondary mb-0">
                    Manage your personal information and account settings.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="community-dashboard">
        <x-community-layout>

            {{-- Profile information --}}
            <section class="community-card">
                <h3 class="h5 fw-bold mb-3">
                    <i class="fa-solid fa-user text-primary me-2"></i>
                    Profile Information
                </h3>

                <p class="text-secondary mb-4">
                    Update your name and email address.
                </p>

                @include('profile.partials.update-profile-information-form')
            </section>

            {{-- Change password --}}
            <section class="community-card">
                <h3 class="h5 fw-bold mb-3">
                    <i class="fa-solid fa-lock text-primary me-2"></i>
                    Update Password
                </h3>

                <p class="text-secondary mb-4">
                    Keep your account secure by using a strong password.
                </p>

                @include('profile.partials.update-password-form')
            </section>

            {{-- Delete account --}}
            <section class="community-card">
                <h3 class="h5 fw-bold mb-3">
                    <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>
                    Delete Account
                </h3>

                <p class="text-secondary mb-4">
                    Permanently delete your account and its associated data.
                </p>

                @include('profile.partials.delete-user-form')
            </section>

        </x-community-layout>
    </div>
</x-app-layout>