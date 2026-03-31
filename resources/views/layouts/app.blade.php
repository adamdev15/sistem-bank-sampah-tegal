<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - BASMAN Kota Tegal</title>
    
    <!-- CSS UTAMA -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="{{ asset('images/logo/logo-basman.png') }}">
    
    @yield('styles')
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo/logo-basman.png') }}">
</head>
<body class="@yield('body-class', '')">
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-logo">
                <img src="{{ asset('images/logo/logo-basman.png') }}" alt="BASMAN Logo" height="40">
                <span>Bank Sampah Management System Kota Tegal</span>
            </div>
            @auth
            <div class="header-user">
                <span>{{ auth()->user()->name }}</span>
                <button type="button" class="btn-logout" data-confirm-logout>
                    Logout
                </button>
            </div>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} BASMAN - Dinas Lingkungan Hidup Kota Tegal</p>
            <p>Version 1.0.0</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('components.modal-confirm')
    <script src="{{ asset('js/confirm-modal.js') }}"></script>
    @yield('scripts')
</body>
</html>