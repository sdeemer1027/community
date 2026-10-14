<div class="community-dashboard-grid">

    {{-- Left sidebar --}}
    @include('components.community-left-sidebar')

    {{-- Center content changes for each page --}}
    <main class="community-main">
        {{ $slot }}
    </main>

    {{-- Right sidebar --}}
    @include('components.community-right-sidebar')

</div>