<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold mb-2">
                <i class="fa-solid fa-car text-primary me-2"></i>
                Local Traffic
            </h2>
            <p class="text-secondary mb-0">
                View traffic conditions near your neighborhood.
            </p>
        </div>
    </x-slot>

    <div class="community-dashboard">
        <x-community-layout>
            <section class="community-card">
                <h3 class="h5 fw-bold mb-3">
                    <i class="fa-solid fa-map-location-dot text-primary me-2"></i>
                    Your Local Traffic Map
                </h3>

                @if (Auth::user()->zip_code)
                    <p class="text-secondary">
                        Traffic map centered on ZIP code:
                        <strong>{{ Auth::user()->zip_code }}</strong>
                    </p>

                                        <div id="traffic-map-loading" class="text-secondary mb-3">
                        Locating your ZIP code...
                    </div>

                    <div class="ratio ratio-4x3 mb-3" id="traffic-map-container" style="display: none;">
                        <iframe
                            id="traffic-map-frame"
                            title="Local area traffic map"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <a
                        href="https://www.google.com/maps/search/?api=1&query={{ urlencode(Auth::user()->zip_code) }}&layer=traffic"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-primary"
                        id="traffic-map-button"
                    >
                        <i class="fa-solid fa-car me-2"></i>
                        Open Live Traffic Map
                    </a>

                    
                    



                @else
                    <p>
                        Add your ZIP code to your profile to view your local area.
                    </p>

                    <a href="{{ route('profile.edit') }}"
                       class="btn btn-primary">
                        Update My Profile
                    </a>
                @endif

                <p class="small text-secondary mt-4 mb-0">
                    Map and traffic information provided by Google Maps.
                    Traffic availability may vary by area.
                </p>
            </section>
        </x-community-layout>
    </div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const location = @json($zipLocation);
    const loading = document.getElementById('traffic-map-loading');
    const container = document.getElementById('traffic-map-container');
    const mapFrame = document.getElementById('traffic-map-frame');
    const trafficButton = document.getElementById('traffic-map-button');

    if (!loading || !container || !mapFrame) {
        return;
    }

    if (!location || !location.lat || !location.lng) {
        loading.textContent =
            'We could not find coordinates for your ZIP code. Please check your profile.';
        return;
    }

    const latitude = Number(location.lat);
    const longitude = Number(location.lng);

            mapFrame.src =
            'https://maps.google.com/maps?q=' +
            encodeURIComponent(latitude + ',' + longitude) +
            '&z=12&layer=traffic&output=embed';

    container.style.display = '';

    loading.textContent =
        'Showing the local area near ' +
        location.city + ', ' + location.statename;

    if (trafficButton) {
        trafficButton.href =
            'https://www.google.com/maps/@' +
            latitude + ',' + longitude +
            ',12z/data=!5m1!1e1';
    }
});
</script>









</x-app-layout>
