<aside class="community-sidebar">
    <section class="community-card">
        <h3 class="h5 fw-bold mb-3">
            <i class="fa-solid fa-compass text-primary me-2"></i>
            Quick Links
        </h3>

        <a href="{{ route('members.index') }}"
           class="community-quick-link">
            <i class="fa-solid fa-users me-2"></i>
            Find Members
        </a>

        <a href="{{ route('connections.index') }}"
           class="community-quick-link">
            <i class="fa-solid fa-envelope me-2"></i>
            Connection Requests
        </a>

        <a href="{{ route('my-connections.index') }}"
           class="community-quick-link">
            <i class="fa-solid fa-handshake me-2"></i>
            My Connections
        </a>

        <a href="{{ route('profile.edit') }}"
           class="community-quick-link">
            <i class="fa-solid fa-user-gear me-2"></i>
            My Profile
        </a>
    </section>
</aside>