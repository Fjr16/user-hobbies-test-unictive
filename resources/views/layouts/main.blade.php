<!doctype html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'User & hobby app' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @stack('styles')
  </head>
  <body class="bg-light min-vh-100 d-flex flex-column">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-code-square me-2"></i> User-Hobby App
            </a>
        </div>
    </nav>
    <main class="flex-grow-1">        
        @yield('content')
    </main>

    <footer class="text-center text-muted py-3 mt-auto small">
        &copy; {{ date('Y') }} User-Hobby. All rights reserved.
    </footer>

    @if (session()->has('success'))
        <x-toast 
            icon="bi bi-check-circle me-2"
            status="Berhasil" 
            :message="session('success')"
        />
    @elseif(session()->has('error'))
        <x-toast 
            icon="bi bi-x-circle me-2"
            status="Gagal" 
            :message="session('error')"
        />
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
  </body>
</html>