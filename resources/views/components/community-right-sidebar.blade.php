<aside class="community-sidebar community-right-sidebar">
    <section class="community-card">
        <div class="d-flex align-items-center mb-3">
            <div class="rounded-3 p-3 me-3"
                 style="background: #dbeafe; color: #1d4ed8;">
                <i class="fa-solid fa-compass fa-xl"></i>
            </div>

            <div>
                <h3 class="h5 fw-bold mb-1">Getting Started</h3>
                <p class="text-secondary small mb-0">
                    A few ways to get involved.
                </p>
            </div>
        </div>

        <div class="community-getting-started-grid">

            <div class="community-getting-started-item">
                <h4 class="h6 fw-bold">
                    <i class="fa-solid fa-id-card text-primary me-2"></i>
                    Complete Your Profile
                </h4>

                <p class="small text-secondary mb-3">
                    Add your ZIP code and update your personal information.
                </p>

                <a href="{{ route('profile.edit') }}"
                   class="btn btn-outline-primary btn-sm">
                    Edit Profile
                </a>
            </div>

            <div class="community-getting-started-item">
                <h4 class="h6 fw-bold">
                    <i class="fa-solid fa-magnifying-glass text-primary me-2"></i>
                    Explore the Directory
                </h4>

                <p class="small text-secondary mb-3">
                    Discover registered members by name or ZIP code.
                </p>

                <a href="{{ route('members.index') }}"
                   class="btn btn-outline-primary btn-sm">
                    Browse Members
                </a>
            </div>

            <div class="community-getting-started-item">
                <h4 class="h6 fw-bold">
                    <i class="fa-solid fa-user-plus text-primary me-2"></i>
                    Build Connections
                </h4>

                <p class="small text-secondary mb-3">
                    Send requests and choose which members to connect with.
                </p>

                <a href="{{ route('connections.index') }}"
                   class="btn btn-outline-primary btn-sm">
                    View Requests
                </a>
            </div>

        </div>
    </section>
</aside>