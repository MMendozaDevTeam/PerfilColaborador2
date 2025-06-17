<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'PeerRank')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS (v5.x) y Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="/bienvenida">Inicio</a>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer class="text-center mt-5 mb-3 text-muted">
        &copy; {{ date('Y') }} Equipo de Sistemas
    </footer>
</body>
</html>
