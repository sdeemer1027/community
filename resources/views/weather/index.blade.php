 <x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold mb-2">
                <i class="fa-solid fa-cloud-sun text-primary me-2"></i>
                Local Weather
            </h2>
            <p class="text-secondary mb-0">
                Current weather conditions for your ZIP code.
            </p>
        </div>
    </x-slot>

    <div class="community-dashboard">
        <x-community-layout>
            <section class="community-card">
                <h3 class="h5 fw-bold mb-3">
                    Your Local Weather
                </h3>

                @if (Auth::user()->zip_code)
                    <p class="text-secondary">
                        Weather for ZIP code:
                        <strong>{{ Auth::user()->zip_code }}</strong>
                    </p>

                    <div id="weather-page-content"
                         aria-live="polite">
                        <p class="text-secondary">
                            Loading local weather...
                        </p>
                    </div>
                @else
                    <p>
                        Add your ZIP code to your profile to see local weather.
                    </p>

                    <a href="{{ route('profile.edit') }}"
                       class="btn btn-primary">
                        Update My Profile
                    </a>
                @endif

                <p class="small text-secondary mt-4 mb-0">
                    Weather data provided by
                    <a href="https://open-meteo.com/"
                       target="_blank"
                       rel="noopener noreferrer">Open-Meteo</a>.
                </p>
            </section>
        </x-community-layout>
    </div>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const content = document.getElementById('weather-page-content');
    const zipCode = @json(Auth::user()->zip_code);

    if (!content || !zipCode) {
        return;
    }

    try {
        
        const location = @json($zipLocation);

        if (!location || !location.lat || !location.lng) {
            content.textContent =
                'Weather location not found. Check your ZIP code in My Profile.';
            return;
        }

        const weatherResponse = await fetch(
            'https://api.open-meteo.com/v1/forecast?' +
            new URLSearchParams({
                latitude: Number(location.lat),
                longitude: Number(location.lng),
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

const place = [location.city, location.statename]
    .filter(Boolean)
    .join(', ');

        content.replaceChildren();

        const placeLine = document.createElement('p');
        placeLine.className = 'text-secondary mb-2';
        placeLine.textContent = place;
        content.appendChild(placeLine);

        const temperature = document.createElement('div');
        temperature.className = 'display-3 fw-bold mb-2';
        temperature.textContent =
            Math.round(current.temperature_2m) + '°F';
        content.appendChild(temperature);

        const condition = document.createElement('p');
        condition.className = 'fs-5 mb-3';
        condition.textContent =
            conditions[current.weather_code] || 'Current conditions';
        content.appendChild(condition);

        const feelsLike = document.createElement('p');
        feelsLike.className = 'mb-2';
        feelsLike.textContent =
            'Feels like ' +
            Math.round(current.apparent_temperature) + '°F';
        content.appendChild(feelsLike);

        const wind = document.createElement('p');
        wind.className = 'mb-0';
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
    
</x-app-layout>