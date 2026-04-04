<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Bank Sampah') - BASMAN Kota Tegal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modal.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bank/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bank/layout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bank/profile.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo-basman.png') }}">
    <link rel="icon" href="{{ asset('images/logo/logo-basman.png') }}">

    @yield('styles')
</head>

<body class="bank-body">

<header class="header basman-dashboard-nav border-0">
    <div class="header-left flex-grow-1 min-w-0">
        <button type="button" class="burger-btn btn btn-link text-decoration-none text-white p-1 me-1 shadow-none basman-burger-toggle" id="sidebarToggle" aria-label="Buka menu">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <div class="header-logo d-flex align-items-center gap-2 min-w-0">
            <img src="{{ asset('image/logo dlh.png') }}" alt="BASMAN" width="36" height="36" class="rounded-2 flex-shrink-0 bg-white bg-opacity-10 p-1">
            <span class="text-truncate">BASMAN · Kota Tegal</span>
        </div>
    </div>

    @auth
    @php
        $bankAuth = auth()->user();
        $bankInitial = strtoupper(mb_substr($bankAuth->name, 0, 1, 'UTF-8'));
    @endphp
    <div class="d-flex align-items-center gap-3">
        <div class="dropdown d-none d-md-inline-block">
            <button type="button" class="btn btn-link text-white position-relative p-1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="min-width: 320px;">
                <div class="px-3 py-2 border-bottom bg-light fw-semibold">Notifikasi</div>
                <a class="dropdown-item py-2" href="#">
                    <div class="small fw-semibold">Laporan baru masuk</div>
                    <div class="text-muted small">Laporan bulan ini sudah dibuat</div>
                </a>
                <a class="dropdown-item py-2" href="#">
                    <div class="small fw-semibold">Pengingat update data operasional</div>
                    <div class="text-muted small">Pastikan data operasional terbaru</div>
                </a>
            </div>
        </div>

        <div class="dropdown d-none d-md-inline-block">
            <button type="button" class="btn btn-link text-white position-relative p-1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-envelope"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">2</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="min-width: 320px;">
                <div class="px-3 py-2 border-bottom bg-light fw-semibold">Pesan</div>
                <a class="dropdown-item py-2" href="#">
                    <div class="small fw-semibold">Admin DLH</div>
                    <div class="text-muted small">Mohon cek update data bulan ini.</div>
                </a>
                <a class="dropdown-item py-2" href="#">
                    <div class="small fw-semibold">Sistem BASMAN</div>
                    <div class="text-muted small">Backup data berhasil dilakukan.</div>
                </a>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-light btn-sm d-flex align-items-center gap-2 py-1 ps-1 pe-2 rounded-pill shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="navbar-user-avatar">{{ $bankInitial }}</span>
                <span class="d-none d-md-inline text-truncate user-name text-dark" style="max-width: 10rem;">{{ $bankAuth->name }}</span>
                <i class="fas fa-chevron-down small text-muted d-none d-md-inline"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 py-0 overflow-hidden" style="min-width: 15rem;">
                <li class="px-3 py-2 bg-light border-bottom">
                    <div class="fw-semibold text-truncate">{{ $bankAuth->name }}</div>
                    <small class="text-muted text-truncate d-block">{{ $bankAuth->email }}</small>
                    <span class="badge bg-success bg-opacity-10 text-success mt-1">Bank Sampah</span>
                </li>
                <li>
                    <button type="button" class="dropdown-item py-2 text-danger" data-confirm-logout>
                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                    </button>
                </li>
            </ul>
        </div>
    </div>
    @endauth
</header>

<div class="app-wrapper" id="appWrapper">

    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-nav">

            <div class="sidebar-header">
                <h3><i class="fas fa-recycle me-2 opacity-75"></i>BASMAN Tegal</h3>
                <p class="bank-name text-truncate" title="{{ auth()->user()->bankSampahMaster->nama_bank_sampah ?? '' }}">
                    {{ auth()->user()->bankSampahMaster->nama_bank_sampah ?? 'Bank Sampah' }}
                </p>
            </div>

            <ul class="nav-menu">

                <li class="nav-item {{ request()->is('bank-sampah/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('bank-sampah.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bank-sampah/laporan*') ? 'active' : '' }}">
                    <a href="{{ route('bank-sampah.laporan.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan Bulanan</span>

                        @php
                            $currentMonth = now()->format('Y-m');
                            $hasLaporan = auth()->user()
                                ->bankSampahMaster
                                ->laporans()
                                ->whereRaw("DATE_FORMAT(periode, '%Y-%m') = ?", [$currentMonth])
                                ->exists();
                        @endphp

                        @if(!$hasLaporan)
                            <span class="badge">!</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bank-sampah/operasional*') ? 'active' : '' }}">
                    <a href="{{ route('bank-sampah.operasional.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Data Operasional</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bank-sampah/profile*') ? 'active' : '' }}">
                    <a href="{{ route('bank-sampah.profile.index') }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Profil Bank Sampah</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('bank-sampah/download*') ? 'active' : '' }}">
                    <a href="{{ route('bank-sampah.download.index') }}" class="sidebar-download-link" id="sidebarDownloadNavLink">
                        <i class="fas fa-download"></i>
                        <span>Download Data</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-confirm-logout>
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>

            <div class="sidebar-footer">
                <p class="mb-1 text-truncate" title="{{ auth()->user()->email }}"><i class="fas fa-envelope me-1"></i> {{ auth()->user()->email }}</p>
                <small>
                    <i class="fas fa-clock me-1"></i>
                    {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : '—' }}
                </small>
            </div>

        </nav>
    </aside>

    <div class="bank-sidebar-overlay" id="bankSidebarOverlay" aria-hidden="true"></div>

    <main class="main-panel">

        <div class="content-body">
            @include('components.alert')
            @yield('content-body')
        </div>

    </main>
</div>

<footer class="footer">
    <div class="container">
        <p class="mb-0">&copy; {{ date('Y') }} <strong>BASMAN</strong> — Dinas Lingkungan Hidup Kota Tegal</p>
        <small>Bank Sampah Management System v1.0</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="{{ asset('js/confirm-modal.js') }}"></script>
<script src="{{ asset('js/bank/main.js') }}"></script>

@include('components.modal-confirm')

<div class="modal fade" id="downloadNavConfirmModal" tabindex="-1" aria-labelledby="downloadNavConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #1f5f46, #2f7d5a);">
                <h5 class="modal-title" id="downloadNavConfirmModalLabel"><i class="fas fa-download me-2"></i>Buka halaman Download Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0 text-secondary">Anda akan menuju halaman unduh laporan dan data operasional. Lanjutkan?</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="downloadNavConfirmBtn">Ya, lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const link = document.getElementById('sidebarDownloadNavLink');
    const modalEl = document.getElementById('downloadNavConfirmModal');
    const confirmBtn = document.getElementById('downloadNavConfirmBtn');
    if (!link || !modalEl || !confirmBtn || typeof bootstrap === 'undefined') return;

    const targetUrl = link.getAttribute('href');
    const modal = new bootstrap.Modal(modalEl);

    link.addEventListener('click', function (e) {
        e.preventDefault();
        confirmBtn.onclick = function () {
            modal.hide();
            window.location.href = targetUrl;
        };
        modal.show();
    });
});
</script>

@yield('scripts')

</body>
</html>
