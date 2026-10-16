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

    {{-- Local Weather --}}
    <section class="community-card mt-3" id="community-weather">
        <h3 class="h5 fw-bold mb-3">
            <i class="fa-solid fa-cloud-sun text-primary me-2"></i>
            Local Weather
        </h3>

        <div id="weather-content">
            <p class="text-secondary small mb-0">
                Loading local weather...
            </p>
        </div>

        <p class="text-secondary small mt-3 mb-0">
            Weather data: <a href="https://open-meteo.com/" target="_blank" rel="noopener noreferrer">Open-Meteo</a>
        </p>
    </section>


    {{-- Local Traffic --}}
    <section class="community-card mt-3" id="community-traffic">
        <h3 class="h5 fw-bold mb-3">
            <i class="fa-solid fa-car text-primary me-2"></i>
            Local Traffic
        </h3>

        @if (Auth::user()->zip_code)
            <p class="text-secondary small">
                Check current traffic conditions near your area.
            </p>

            <a
                href="https://www.google.com/maps/@?api=1&map_action=map&center={{ urlencode(Auth::user()->zip_code) }}&layer=traffic"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-outline-primary btn-sm w-100"
            >
                <i class="fa-solid fa-map-location-dot me-2"></i>
                View Traffic Map
            </a>
        @else
            <p class="text-secondary small mb-0">
                Add your ZIP code in
                <a href="{{ route('profile.edit') }}">My Profile</a>
                to find traffic near you.
            </p>
        @endif
    </section>




















{{-- Local Police and Public Safety --}}
<section class="community-card mt-3" id="community-public-safety">
    <h3 class="h5 fw-bold mb-3">
        <i class="fa-solid fa-shield-halved text-primary me-2"></i>
        Public Safety
    </h3>

    @if (Auth::user()->zip_code)
        <p class="text-secondary small">
            Look for police reports, road incidents, and public safety alerts near your area.
        </p>

        <a
            href="https://www.google.com/search?q={{ urlencode('official police department public safety alerts near ' . Auth::user()->zip_code) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-outline-primary btn-sm w-100 mb-2"
        >
            <i class="fa-solid fa-building-shield me-2"></i>
            Local Police & Alerts
        </a>

        <a
            href="https://news.google.com/search?q={{ urlencode('police incident near ' . Auth::user()->zip_code) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-outline-secondary btn-sm w-100"
        >
            <i class="fa-solid fa-newspaper me-2"></i>
            Police News & Reports
        </a>
    @else
        <p class="text-secondary small mb-0">
            Add your ZIP code in
            <a href="{{ route('profile.edit') }}">My Profile</a>
            to look up local public safety information.
        </p>
    @endif

    <p class="text-secondary small mt-3 mb-0">
        For emergencies, call 911 in the United States. Search results may not be official or current.
    </p>
</section>






























<script>
document.addEventListener('DOMContentLoaded', async function () {
    const content = document.getElementById('weather-content');
    const zipCode = @json(Auth::user()->zip_code);

    if (!content) {
        return;
    }

    if (!zipCode) {
        content.innerHTML =
            '<p class="text-secondary small">Add your ZIP code in My Profile to see local weather.</p>';
        return;
    }

    try {
        const locationResponse = await fetch(
            'https://geocoding-api.open-meteo.com/v1/search?' +
            new URLSearchParams({
                name: zipCode,
                count: 10,
                countryCode: 'US',
                language: 'en',
                format: 'json'
            })
        );

        if (!locationResponse.ok) {
            throw new Error('Location lookup failed');
        }

        const locationData = await locationResponse.json();
        const locations = locationData.results || [];
        const location = locations.find(item =>
            (item.postcodes || []).includes(zipCode)
        ) || locations[0];

        if (!location) {
            content.textContent =
                'Weather location not found. Check your ZIP code in My Profile.';
            return;
        }

        const weatherResponse = await fetch(
            'https://api.open-meteo.com/v1/forecast?' +
            new URLSearchParams({
                latitude: location.latitude,
                longitude: location.longitude,
                current: 'temperature_2m,apparent_temperature,weather_code,wind_speed_10m',
                temperature_unit: 'fahrenheit',
                wind_speed_unit: 'mph',
                timezone: 'auto'
            })
        );

        if (!weatherResponse.ok) {
            throw new Error('Weather lookup failed');
        }

        const weatherData = await weatherResponse.json();
        const current = weatherData.current;

        const conditions = {
            0: 'Clear sky',
            1: 'Mainly clear',
            2: 'Partly cloudy',
            3: 'Overcast',
            45: 'Fog',
            48: 'Freezing fog',
            51: 'Light drizzle',
            53: 'Drizzle',
            55: 'Heavy drizzle',
            61: 'Light rain',
            63: 'Rain',
            65: 'Heavy rain',
            71: 'Light snow',
            73: 'Snow',
            75: 'Heavy snow',
            80: 'Rain showers',
            81: 'Rain showers',
            82: 'Heavy rain showers',
            95: 'Thunderstorm',
            96: 'Thunderstorm with hail',
            99: 'Thunderstorm with hail'
        };

        const place = [location.name, location.admin1]
            .filter(Boolean)
            .join(', ');

        content.replaceChildren();

        const placeLine = document.createElement('p');
        placeLine.className = 'small text-secondary mb-2';
        placeLine.textContent = place;
        content.appendChild(placeLine);

        const temperature = document.createElement('div');
        temperature.className = 'display-6 fw-bold mb-1';
        temperature.textContent =
            Math.round(current.temperature_2m) + '°F';
        content.appendChild(temperature);

        const condition = document.createElement('p');
        condition.className = 'mb-2';
        condition.textContent =
            conditions[current.weather_code] || 'Current conditions';
        content.appendChild(condition);

        const feelsLike = document.createElement('p');
        feelsLike.className = 'small mb-1';
        feelsLike.textContent =
            'Feels like ' +
            Math.round(current.apparent_temperature) + '°F';
        content.appendChild(feelsLike);

        const wind = document.createElement('p');
        wind.className = 'small mb-0';
        wind.textContent =
            'Wind: ' + Math.round(current.wind_speed_10m) + ' mph';
        content.appendChild(wind);

    } catch (error) {
        content.textContent =
            'Weather is temporarily unavailable. Please try again later.';
        console.error('Weather loading failed:', error);
    }
});
</script>








































    {{-- Daily Horoscope --}}
    <section class="community-card mt-3" id="community-horoscope">
        <h3 class="h5 fw-bold mb-3">
            <i class="fa-solid fa-star text-primary me-2"></i>
            Daily Horoscope
        </h3>

        @if (Auth::user()->birthdate)
            @php
                $month = Auth::user()->birthdate->month;
                $day = Auth::user()->birthdate->day;

                $zodiac = match (true) {
                    ($month == 3 && $day >= 21) || ($month == 4 && $day <= 19) => 'Aries',
                    ($month == 4 && $day >= 20) || ($month == 5 && $day <= 20) => 'Taurus',
                    ($month == 5 && $day >= 21) || ($month == 6 && $day <= 20) => 'Gemini',
                    ($month == 6 && $day >= 21) || ($month == 7 && $day <= 22) => 'Cancer',
                    ($month == 7 && $day >= 23) || ($month == 8 && $day <= 22) => 'Leo',
                    ($month == 8 && $day >= 23) || ($month == 9 && $day <= 22) => 'Virgo',
                    ($month == 9 && $day >= 23) || ($month == 10 && $day <= 22) => 'Libra',
                    ($month == 10 && $day >= 23) || ($month == 11 && $day <= 21) => 'Scorpio',
                    ($month == 11 && $day >= 22) || ($month == 12 && $day <= 21) => 'Sagittarius',
                    ($month == 12 && $day >= 22) || ($month == 1 && $day <= 19) => 'Capricorn',
                    ($month == 1 && $day >= 20) || ($month == 2 && $day <= 18) => 'Aquarius',
                    default => 'Pisces',
                };
            @endphp

            <p class="mb-2">
                Your sign: <strong>{{ $zodiac }}</strong>
            </p>

            <a
                href="https://www.google.com/search?q={{ urlencode($zodiac . ' daily horoscope today') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="btn btn-outline-primary btn-sm w-100"
            >
                <i class="fa-solid fa-moon me-2"></i>
                Read Today's Horoscope
            </a>
        @else
            <p class="text-secondary small mb-0">
                Add your birthdate in
                <a href="{{ route('profile.edit') }}">My Profile</a>
                to discover your zodiac sign and daily horoscope.
            </p>
        @endif

        <p class="text-secondary small mt-3 mb-0">
            Horoscope readings are for entertainment.
        </p>
    </section>



</aside>