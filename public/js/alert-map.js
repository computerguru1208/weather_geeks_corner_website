
document.addEventListener("DOMContentLoaded", function () {
    const alerts = window.kansasAlerts || [];

    alerts.forEach(alert => {
        const fips = alert.properties.geocode?.FIPS6 || [];
        const eventType = alert.properties.event.toLowerCase();

        fips.forEach(fipsCode => {
            const county = fipsCode.slice(2); // skip state prefix (e.g., '20')
            const element = document.getElementById(countyMap[county]);
            if (!element) return;

            if (eventType.includes("tornado")) {
                element.classList.add("warn-tornado");
            } else if (eventType.includes("severe thunderstorm")) {
                element.classList.add("warn-severe");
            } else if (eventType.includes("flood")) {
                element.classList.add("warn-flood");
            }
        });
    });
});

// Mapping from county FIPS to county SVG ID (simplified)
const countyMap = {
    "173": "SEDGWICK",
    "091": "JOHNSON",
    "177": "SHAWNEE"
};
