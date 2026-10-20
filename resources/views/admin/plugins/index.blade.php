<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold mb-2">
                Plugin Management
            </h2>
            <p class="text-secondary mb-0">
                View and manage the features available on your community platform.
            </p>
        </div>
    </x-slot>

    <div class="community-dashboard">
        <div class="row g-4">

            {{-- Admin sidebar --}}
            <div class="col-12 col-lg-3 col-xl-2">
                @include('components.admin.sidebar')
            </div>

            {{-- Main content --}}
            <div class="col-12 col-lg-9 col-xl-10">

                <section class="community-card mb-4">
                    <h3 class="h5 fw-bold mb-2">
                        <i class="fa-solid fa-puzzle-piece text-primary me-2"></i>
                        Installed Plugins
                    </h3>

                    <p class="text-secondary mb-0">
                        This page lists the features currently available on
                        Neighborhood Porch. Administrative visibility controls
                        can be added later.
                    </p>
                </section>

                <section class="community-card">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Plugin</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-cloud-sun text-primary me-2"></i>
                                        Weather
                                    </td>
                                    <td>Weather information and forecasts.</td>
                                    <td>Information</td>
                                    <td>
                                        <span class="badge bg-success">Enabled</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <i class="fa-solid fa-car text-primary me-2"></i>
                                        Traffic
                                    </td>
                                    <td>Local traffic information.</td>
                                    <td>Information</td>
                                    <td>
                                        <span class="badge bg-success">Enabled</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <i class="fa-solid fa-comments text-primary me-2"></i>
                                        Private Chat
                                    </td>
                                    <td>Private messaging between connected members.</td>
                                    <td>Communication</td>
                                    <td>
                                        <span class="badge bg-success">Enabled</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <i class="fa-solid fa-users text-primary me-2"></i>
                                        Connections
                                    </td>
                                    <td>Member connections and connection management.</td>
                                    <td>Community</td>
                                    <td>
                                        <span class="badge bg-success">Enabled</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <i class="fa-solid fa-image text-primary me-2"></i>
                                        Photo Uploads
                                    </td>
                                    <td>Profile photos and images shared by members.</td>
                                    <td>Media</td>
                                    <td>
                                        <span class="badge bg-success">Enabled</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <i class="fa-solid fa-flag text-primary me-2"></i>
                                        Post Reports
                                    </td>
                                    <td>Member reporting and administrator review of posts.</td>
                                    <td>Moderation</td>
                                    <td>
                                        <span class="badge bg-success">Enabled</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="small text-secondary mt-3 mb-0">
                        This is currently an informational list. The status labels
                        do not yet control whether a feature is available to members.
                    </p>
                </section>

            </div>
        </div>
    </div>
</x-app-layout>