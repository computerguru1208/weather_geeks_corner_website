@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Welcome Section -->
    <div class="dark-banner mb-5">
        <h2 class="fw-bold text-yellow">Welcome to The Weather Geek's Corner</h1>
            <p class="lead">Your #1 source for Kansas weather — real-time alerts, local forecasts, and Kansas weather history.</p>
    </div>

    <!-- Live Radar Section -->
    <div class="mb-5 position-relative">
        <h2 class="text-yellow mb-3">Live Kansas Radar Snapshot</h2>

        <a href="{{ url('/radar-live') }}" class="text-decoration-none position-relative d-block" style="height: 400px;">
            <div id="homeMap" style="height: 100%; width: 100%; z-index: 1;"></div>

            <div style="
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 1rem 2rem;
                border-radius: 10px;
                z-index: 2;
                text-align: center;
                font-weight: bold;
            ">
                Click to view full interactive radar & alerts →
            </div>

            <div style="
                position: absolute;
                top: 20px;
                left: 20px;
                background-color: red;
                color: white;
                padding: 6px 12px;
                font-weight: bold;
                border-radius: 5px;
                z-index: 3;
                box-shadow: 0 0 8px red;
                font-size: 0.9rem;
                letter-spacing: 1px;
            ">
                LIVE
            </div>
        </a>
    </div>

    <!-- Forecast Section -->
    <div class="mb-5">
        <h2 class="text-yellow mb-4">Current Conditions – Top Kansas Cities</h2>
        <div class="row">
            @foreach($kansasCities as $city)
            <div class="col-md-3">
                <div class="card dark-card text-center mb-4">
                    <h5 class="text-yellow">{{ $city['name'] }}</h5>
                    <p>{{ $city['description'] }}</p>
                    <h3>{{ $city['temperature'] }}°</h3>
                    <p>Wind: {{ $city['wind'] }}</p>
                    <p>Hum: {{ $city['humidity'] ?? 'N/A%' }}</p>
                    <p>Dewpt: {{ $city['dewpoint'] ?? 'N/A' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Blog Section -->
    <div class="mb-5">
        <h2 class="text-yellow mb-4">Latest Blog Posts</h2>
        @forelse($posts as $post)
        <div class="card dark-card mb-4">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{ $post['image'] ?? 'https://via.placeholder.com/300x200?text=No+Image' }}"
                        class="img-fluid rounded-start" alt="{{ $post['title'] }}">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="text-white">{{ $post['title'] }}</h5>
                        <p class="text-secondary">{{ $post['excerpt'] }}</p>
                        <a href="{{ route('blog.show', $post['slug']) }}" class="btn btn-warning">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="alert-box">
            <strong>No blog posts yet.</strong> Please check back soon!
        </div>
        @endforelse
    </div>

    <!-- Weather History Section -->
    <div class="alert-box">
        <h2 class="text-yellow">Today in Kansas Weather History</h2>
        @if(!empty($weatherHistory) && $weatherHistory->isNotEmpty())
        <ul>
            @foreach($weatherHistory as $event)
            <li>{{ $event->summary }}</li>
            @endforeach
        </ul>
        @else
        <p>No historical events found for this date.</p>
        @endif
    </div>

</div>

<!-- Leaflet Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const map = L.map('homeMap', {
            zoomControl: false,
            attributionControl: false,
            dragging: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false,
            tap: false,
        }).setView([38.5, -98.0], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.tileLayer('https://mesonet.agron.iastate.edu/cache/tile.py/1.0.0/nexrad-n0q/{z}/{x}/{y}.png', {
            opacity: 0.85
        }).addTo(map);
    });
</script>
@endsection