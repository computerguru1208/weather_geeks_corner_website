<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>The Weather Geeks Corner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- ✅ Your Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v=1.1">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body class="bg-light text-dark">
    <nav class="navbar navbar-expand-lg navbar-custom mb-4">
        <div class="container d-flex justify-content-between align-items-center w-100">
            <div class="d-flex align-items-center">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/weather_geeks_corner_150x150.png') }}" alt="Logo" height="60" class="me-3">
                    <div>
                        <div class="fw-bold text-yellow fs-5 m-0">Weather Geek’s Corner</div>
                        <div class="navbar-subtitle text-white"><em>"Where Weather Meets Wonder"</em></div>
                    </div>
                </a>
            </div>


            <!-- Navbar links -->
            <div>
                <a href="{{ url('/radar-live') }}" class="nav-link d-inline-block text-white">Radar & Alerts</a>
                <a href="{{ url('/blog') }}" class="nav-link d-inline-block text-white">Weather Blog</a>
            </div>
        </div>
    </nav>
    <div class="container py-4">
        @yield('content')
    </div>
</body>

</html>