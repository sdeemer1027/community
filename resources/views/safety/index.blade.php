<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold mb-2">
                <i class="fa-solid fa-shield-halved text-primary me-2"></i>
                Public Safety
            </h2>
            <p class="text-secondary mb-0">
                Local police information, public safety alerts, and incident reports.
            </p>
        </div>
    </x-slot>

    <div class="community-dashboard">
        <x-community-layout>
            <section class="community-card">
                <h3 class="h5 fw-bold mb-3">
                    Your Local Public Safety
                </h3>

                @if (Auth::user()->zip_code)
                    <p class="text-secondary">
                        Finding public safety resources near ZIP code:
                        <strong>{{ Auth::user()->zip_code }}</strong>
                    </p>

                    
<div id="safety-page-content" aria-live="polite">

    <div class="mb-4 p-3 border rounded">
        <h4 class="h6 fw-bold">
            <i class="fa-solid fa-triangle-exclamation text-primary me-2"></i>
            Live Weather & Emergency Alerts
        </h4>

        <p class="text-secondary small">
            Active alerts from the National Weather Service for your area.
        </p>

        <div id="safety-live-alerts" aria-live="polite">
            <p class="text-secondary small mb-0">
                Loading local alerts...
            </p>
        </div>

        <p class="small text-secondary mt-3 mb-0">
            Official source:
            <a href="https://www.weather.gov/"
               target="_blank"
               rel="noopener noreferrer">National Weather Service</a>.
            For emergencies, call 911.
        </p>
    </div>

    <hr>

    @php
        $safetyCity = $zipLocation->city ?? '';
        $safetyState = $zipLocation->statename ?? '';
        $safetyZip = Auth::user()->zip_code;

        $safetyArea = trim(
            $safetyCity . ' ' . $safetyState . ' ' . $safetyZip
        );
    @endphp

    <div class="mb-4">
        <h4 class="h6 fw-bold">
            <i class="fa-solid fa-building-shield text-primary me-2"></i>
            Official Police Department
        </h4>

        <p class="text-secondary small">
            Find your local police department's official website and contact information.
        </p>

        <a
            href="https://www.google.com/search?q={{ urlencode('official police department website phone ' . $safetyArea) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-outline-primary btn-sm"
        >
            Find Local Police
        </a>
    </div>

    <hr>

    <div class="my-4">
        <h4 class="h6 fw-bold">
            <i class="fa-solid fa-bell text-primary me-2"></i>
            Public Safety Alerts
        </h4>

        <p class="text-secondary small">
            Look for official emergency notices, police alerts, and community safety announcements.
        </p>

        <a
            href="https://www.google.com/search?q={{ urlencode('official public safety alerts police department ' . $safetyArea) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-outline-primary btn-sm"
        >
            Find Safety Alerts
        </a>
    </div>

    <hr>

    <div class="my-4">
        <h4 class="h6 fw-bold">
            <i class="fa-solid fa-newspaper text-primary me-2"></i>
            Recent Incidents & Crime Reports
        </h4>

        <p class="text-secondary small">
            Search for recent police incidents, published crime reports, and local public safety news.
        </p>

        <a
            href="https://news.google.com/search?q={{ urlencode('police incidents crime reports ' . $safetyArea) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-outline-secondary btn-sm"
        >
            Find Incidents & Reports
        </a>
    </div>
</div>

                @else
                    <p>
                        Add your ZIP code to your profile to find local public safety resources.
                    </p>

                    <a href="{{ route('profile.edit') }}"
                       class="btn btn-primary">
                        Update My Profile
                    </a>
                @endif

                <p class="small text-secondary mt-4 mb-0">
                    For emergencies in the United States, call 911.
                    Always verify alerts and incident information with official sources.
                </p>
            </section>
        </x-community-layout>
    </div>



<script>
document.addEventListener('DOMContentLoaded', async function () {
    const alertBox = document.getElementById('safety-live-alerts');

    if (!alertBox) {
        return;
    }

    const location = @json($zipLocation);

    if (!location || !location.lat || !location.lng) {
        alertBox.textContent =
            'A valid ZIP-code location could not be found. Please check your profile.';
        return;
    }

    try {
        const response = await fetch(
            'https://api.weather.gov/alerts/active?point=' +
            encodeURIComponent(location.lat + ',' + location.lng),
            {
                headers: {
                    'Accept': 'application/geo+json'
                }
            }
        );

        if (!response.ok) {
            throw new Error('Alert service unavailable');
        }

        const data = await response.json();
        const alerts = data.features || [];

        alertBox.replaceChildren();

        if (alerts.length === 0) {
            const message = document.createElement('p');
            message.className = 'text-secondary small mb-0';
            message.textContent =
                'No active National Weather Service alerts were returned for this location.';
            alertBox.appendChild(message);
            return;
        }

        alerts.forEach(function (item) {
            const properties = item.properties || {};

            const card = document.createElement('div');
            card.className = 'border rounded p-3 mb-3';

            const title = document.createElement('h5');
            title.className = 'h6 fw-bold mb-2';
            title.textContent =
                properties.event || 'Weather alert';
            card.appendChild(title);

            const severity = document.createElement('p');
            severity.className = 'small mb-2';
            severity.textContent =
                'Severity: ' + (properties.severity || 'Not specified');
            card.appendChild(severity);

            if (properties.headline) {
                const headline = document.createElement('p');
                headline.className = 'mb-2';
                headline.textContent = properties.headline;
                card.appendChild(headline);
            }

            if (properties.description) {
                const description = document.createElement('p');
                description.className = 'small mb-2';
                description.textContent = properties.description;
                card.appendChild(description);
            }

            if (properties.ends) {
                const endTime = document.createElement('p');
                endTime.className = 'small text-secondary mb-0';
                endTime.textContent =
                    'Expires: ' + new Date(properties.ends).toLocaleString();
                card.appendChild(endTime);
            }

            alertBox.appendChild(card);
        });
    } catch (error) {
        alertBox.textContent =
            'Live alerts are temporarily unavailable. Please check the official National Weather Service website.';
        console.error('Public Safety alerts failed:', error);
    }
});
</script>


    
</x-app-layout>
