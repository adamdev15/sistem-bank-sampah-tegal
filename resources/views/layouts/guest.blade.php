<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - BASMAN Kota Tegal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="{{ asset('css/guest-shell.css') }}" rel="stylesheet">
    @yield('styles')

    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo-basman.png') }}">
</head>
<body class="guest-page">

<nav class="navbar navbar-expand-lg guest-navbar sticky-top py-2">
    <div class="container">
        <a class="navbar-brand text-white d-flex align-items-center gap-2" href="{{ route('login') }}">
            <img src="{{ asset('image/logo dlh.png') }}" alt="BASMAN" width="40" height="40" class="rounded-2 bg-white bg-opacity-10 p-1">
            <span class="d-flex flex-column lh-sm">
                <span>BASMAN</span>
                <small class="fw-normal opacity-75 d-none d-sm-block" style="font-size: 0.7rem;">Bank Sampah Management · Kota Tegal</small>
            </span>
        </a>
        <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#guestNav" aria-controls="guestNav" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="guestNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-lg-1 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('login') ? 'active bg-white bg-opacity-10' : '' }}" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('register') ? 'active bg-white bg-opacity-10' : '' }}" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i> Daftar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('password.request') ? 'active bg-white bg-opacity-10' : '' }}" href="{{ route('password.request') }}">
                        <i class="fas fa-key me-1"></i> Lupa Password
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="guest-main">
    @yield('content')
</main>

@php
    $emailError = $errors->first('email');
    $showVerifyModal = session('show_verify_modal')
        || ($emailError && \Illuminate\Support\Str::contains(strtolower($emailError), 'belum diverifikasi'));
    $verifyModalMessage = session('verify_modal_message')
        ?? ($emailError ?: 'Email Anda belum diverifikasi. Silakan cek inbox untuk verifikasi.');
@endphp

@if($showVerifyModal)
    <div class="modal fade" id="verifyMailModal" tabindex="-1" aria-labelledby="verifyMailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #1f5f46, #2f7d5a);">
                    <h5 class="modal-title" id="verifyMailModalLabel">
                        <i class="fas fa-envelope-open-text me-2"></i>Verifikasi Email Diperlukan
                    </h5>
                </div>
                <div class="modal-body">
                    <p class="mb-0 text-secondary">{{ $verifyModalMessage }}</p>
                    <small class="text-muted d-block mt-2">Popup ini akan tertutup otomatis dalam 3 detik.</small>
                </div>
            </div>
        </div>
    </div>
@endif

<footer class="">
    <div class="container text-center text-md-start">
        <div class="row align-items-center gy-2">
            <div class="col-md-6">
                <strong>Dinas Lingkungan Hidup · Kota Tegal</strong>
                <div class="small">Sistem pendataan &amp; pengelolaan Bank Sampah (BASMAN)</div>
            </div>
            <div class="col-md-6 text-md-end small">
                &copy; {{ date('Y') }} BASMAN. Semua hak dilindungi.
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@if($showVerifyModal)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('verifyMailModal');
    if (!modalElement || typeof bootstrap === 'undefined') return;

    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true
    });

    modal.show();

    setTimeout(function () {
        modal.hide();
    }, 3000);
});
</script>
@endif
@yield('scripts')
</body>
</html>
