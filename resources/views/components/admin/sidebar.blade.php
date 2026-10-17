
<aside class="community-card h-100">
    <h3 class="h6 fw-bold mb-3">
        <i class="fa-solid fa-shield-halved text-primary me-2"></i>
        Administration
    </h3>

    <nav class="nav flex-column gap-1">

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link rounded-3 {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fa-solid fa-gauge-high me-2"></i>
            Overview
        </a>

        <a href="{{ route('admin.members.index') }}"
           class="nav-link rounded-3 {{ request()->routeIs('admin.members.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fa-solid fa-users me-2"></i>
            Members
        </a>

        <hr class="my-2">

        <div class="px-3 py-1 text-uppercase small text-secondary fw-semibold">
            Coming later
        </div>

        <span class="nav-link text-secondary">
            <i class="fa-solid fa-shield-halved me-2"></i>
            Moderation
        </span>

       <a href="{{ route('admin.roles.index') }}"
   class="nav-link rounded-3 {{ request()->routeIs('admin.roles.*') ? 'active bg-primary text-white' : 'text-dark' }}">
    <i class="fa-solid fa-user-lock me-2"></i>
    Roles &amp; Permissions
</a>

        <span class="nav-link text-secondary">
            <i class="fa-solid fa-id-card me-2"></i>
            Memberships
        </span>

        <span class="nav-link text-secondary">
            <i class="fa-solid fa-puzzle-piece me-2"></i>
            Features &amp; Integrations
        </span>

        <span class="nav-link text-secondary">
            <i class="fa-solid fa-sliders me-2"></i>
            System Settings
        </span>

        <hr class="my-2">

        <a href="{{ route('dashboard') }}" class="nav-link text-dark rounded-3">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Community Dashboard
        </a>

    </nav>
</aside>
