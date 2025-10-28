@extends('layouts.app')

@section('content')
<div class="container position-relative">
    <h2 class="text-yellow mb-4">Interactive Kansas Radar & Warnings</h2>

    {{-- LIVE Badge --}}
    <div style="position:absolute; top:80px; left:30px; background:red; color:white; padding:6px 12px; font-weight:bold; border-radius:5px; z-index:999; box-shadow:0 0 8px red; font-size:0.9rem; letter-spacing:1px;">
        LIVE
    </div>

    {{-- Radar Map --}}
    <div id="map" style="height:600px; width:100%; margin-bottom:2rem;"></div>

    {{-- Alerts --}}
    <div class="bg-dark text-warning p-4 rounded">
        <h4>Current Alerts</h4>
        <div id="alertsAccordion" class="accordion"></div>
    </div>
</div>

{{-- Leaflet CDN --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
    let map;
    document.addEventListener("DOMContentLoaded", function() {
        map = L.map("map", {
            zoomControl: false
        }).setView([38.5, -98.0], 7);
        L.control.zoom({
            position: "bottomright"
        }).addTo(map);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "© OpenStreetMap",
        }).addTo(map);

        // Declare radarLayer globally so we can replace it later
        let radarLayer = L.tileLayer(getRadarUrl(), {
            opacity: 0.75,
            zIndex: 20,
        }).addTo(map);

        // Refresh radar tiles every 60 seconds
        setInterval(() => {
            const newUrl = getRadarUrl(); // new URL with timestamp
            map.removeLayer(radarLayer);
            radarLayer = L.tileLayer(newUrl, {
                opacity: 0.75,
                zIndex: 20,
            }).addTo(map);
        }, 60000);

        // Helper to add a timestamp to bust cache
        function getRadarUrl() {
            return `https://mesonet.agron.iastate.edu/cache/tile.py/1.0.0/nexrad-n0q/{z}/{x}/{y}.png?_=${Date.now()}`;
        }

        // Load alerts
        fetchAlerts();
        setInterval(fetchAlerts, 60000);
    });

    function fetchAlerts() {
        const allowed = [
            "Tornado Warning",
            "Severe Thunderstorm Warning",
            "Flash Flood Warning",
            "Flood Warning",
        ];
        fetch("https://api.weather.gov/alerts/active?area=KS")
            .then((res) => res.json())
            .then((data) => {
                if (window.alertLayers) window.alertLayers.forEach((l) => map.removeLayer(l));
                window.alertLayers = [];
                document.getElementById("alertsAccordion").innerHTML = "";

                data.features.forEach((a, i) => {
                    const props = a.properties;
                    if (!allowed.includes(props.event)) return;

                    const poly = L.polygon(
                            a.geometry.coordinates[0].map((c) => [c[1], c[0]]), {
                                color: getColor(props.event),
                                fillOpacity: 0.4,
                                weight: 2,
                            }
                        )
                        .bindPopup(
                            `<strong>${props.event}</strong><br>${props.areaDesc}<br><em>Expires:</em> ${props.expires}`
                        )
                        .addTo(map);
                    window.alertLayers.push(poly);

                    // Accordion
                    document.getElementById("alertsAccordion").insertAdjacentHTML(
                        "beforeend",
                        `
          <div class="accordion-item bg-dark border-warning mb-2">
            <h2 class="accordion-header" id="heading${i}">
              <button class="accordion-button collapsed bg-dark text-warning" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapse${i}">
                ${props.event} – ${props.areaDesc}
              </button>
            </h2>
            <div id="collapse${i}" class="accordion-collapse collapse" data-bs-parent="#alertsAccordion">
              <div class="accordion-body text-white">
                <strong>Issued:</strong> ${props.sent}<br>
                <strong>Expires:</strong> ${props.expires}<br><br>
                ${props.description}
              </div>
            </div>
          </div>`
                    );
                });
            });
    }

    function getColor(type) {
        switch (type) {
            case "Tornado Warning":
                return "red";
            case "Severe Thunderstorm Warning":
                return "yellow";
            case "Flash Flood Warning":
            case "Flood Warning":
                return "green";
            default:
                return "gray";
        }
    }
</script>
@endsection