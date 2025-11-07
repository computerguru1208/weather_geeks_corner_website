<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Weather Proxy Docs</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist/swagger-ui.css" />
</head>

<body>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist/swagger-ui-standalone-preset.js"></script>
    <script>
        window.addEventListener('load', function() {
            window.ui = SwaggerUIBundle({
                urls: [{
                        url: "/api/openapi.yaml",
                        name: "Weather Proxy (Your Site)"
                    },
                    {
                        url: "https://api.weather.gov/openapi.json",
                        name: "NWS API (Official)"
                    }
                ],
                dom_id: "#swagger-ui",
                deepLinking: true,
                presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
                layout: "StandaloneLayout"
            });
        });
    </script>
</body>

</html>