<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - BASMAN Kota Tegal</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/table.css') }}" rel="stylesheet">
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modal.css') }}" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/logo/logo-basman.png') }}">
    @yield('styles')
</head>
<body>

@auth
@php
    $authUser = auth()->user();
    $initial = strtoupper(mb_substr($authUser->name, 0, 1, 'UTF-8'));
@endphp
@endauth

<nav class="navbar navbar-expand basman-dashboard-nav sticky-top py-2 px-2 px-lg-3">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
            <button type="button" class="burger-btn btn btn-link text-decoration-none text-white p-1 me-1 shadow-none basman-burger-toggle" id="burgerBtn" aria-label="Buka menu">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </button>

            <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                <div class="input-group input-group-sm w-100" style="max-width: 320px;">
                    <span class="input-group-text bg-white bg-opacity-90 border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="search" class="form-control border-0" placeholder="Cari bank sampah / laporan..." aria-label="Search">
                </div>
            </form>
        </div>

        @auth
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
                        <div class="text-muted small">Bank Sampah Melati - 5 menit lalu</div>
                    </a>
                    <a class="dropdown-item py-2" href="#">
                        <div class="small fw-semibold">Akun menunggu verifikasi</div>
                        <div class="text-muted small">2 akun baru menunggu persetujuan</div>
                    </a>
                    <a class="dropdown-item py-2" href="#">
                        <div class="small fw-semibold">Pengingat export bulanan</div>
                        <div class="text-muted small">Sudah saatnya export rekap bulan ini</div>
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
                        <div class="text-muted small">Mohon cek laporan bank sampah wilayah timur.</div>
                    </a>
                    <a class="dropdown-item py-2" href="#">
                        <div class="small fw-semibold">Sistem BASMAN</div>
                        <div class="text-muted small">Backup data harian berhasil dilakukan.</div>
                    </a>
                </div>
            </div>

            <div class="dropdown">
                <button class="btn btn-light btn-sm d-flex align-items-center gap-2 py-1 ps-1 pe-2 rounded-pill shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="navbar-user-avatar">{{ $initial }}</span>
                    <span class="d-none d-md-inline text-truncate" style="max-width: 10rem;">{{ $authUser->name }}</span>
                    <i class="fas fa-chevron-down small text-muted d-none d-md-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 py-0 overflow-hidden" style="min-width: 14rem;">
                    <li class="px-3 py-2 bg-light border-bottom">
                        <div class="fw-semibold text-truncate">{{ $authUser->name }}</div>
                        <small class="text-muted text-truncate d-block">{{ $authUser->email }}</small>
                        @php
                            $roleLabel = $authUser->role === 'admin' ? 'Administrator' : ucfirst(str_replace('_',' ',$authUser->role));
                            $roleClass = $authUser->role === 'admin'
                                ? 'bg-success bg-opacity-10 text-success'
                                : 'bg-primary bg-opacity-10 text-primary';
                        @endphp
                        <span class="badge mt-1 {{ $roleClass }}">{{ $roleLabel }}</span>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item py-2 text-danger" onclick="confirmLogout()">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        @endauth
    </div>
</nav>

<div class="admin-container">

    <aside class="sidebar" id="sidebar">
        <nav class="sidebar-nav">

            <div class="sidebar-header">
                <h3><img src="{{ asset('image/logo dlh.png') }}" alt="BASMAN" width="50" height="50" class="rounded-5 flex-shrink-0 p-1 bg-white bg-opacity-10"> <span>BASMAN TEGAL</span></h3>
                <p>Dinas Lingkungan Hidup Kota Tegal</p>
            </div>

            <ul class="nav-menu">
                <li class="nav-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/bank-sampah*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bank-sampah.index') }}">
                        <i class="fas fa-university"></i>
                        <span>Data Master Bank Sampah</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/operasional*') ? 'active' : '' }}">
                    <a href="{{ route('admin.operasional.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Data Operasional</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/laporan*') ? 'active' : '' }}">
                    <a href="{{ route('admin.laporan.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Verifikasi Laporan</span>
                        @php
                            $pendingReports = \App\Models\Laporan::where('status','menunggu_verifikasi')->count();
                        @endphp
                        @if($pendingReports)
                            <span class="badge">{{ $pendingReports }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Verifikasi Akun</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/export*') ? 'active' : '' }}">
                    <a href="{{ route('admin.export.index') }}">
                        <i class="fas fa-download"></i>
                        <span>Export Data</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/wilayah*') ? 'active' : '' }}">
                    <a href="{{ route('admin.wilayah.kecamatan') }}">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Data Wilayah</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); confirmLogout();" role="button">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                @php
                    $sidebarRoleLabel = $authUser->role === 'admin' ? 'Administrator' : ucfirst(str_replace('_',' ',$authUser->role));
                    $sidebarRoleClass = $authUser->role === 'admin'
                        ? 'bg-purple bg-opacity-10 text-white'
                        : 'bg-info bg-opacity-10 text-white';
                @endphp
                <p class="mb-1">Peran: <span class="badge {{ $sidebarRoleClass }}">{{ $sidebarRoleLabel }}</span></p>
                <small>Login: {{ auth()->user()->last_login_at?->diffForHumans() ?? '—' }}</small>
            </div>

        </nav>
    </aside>

    <main class="main-panel">
        <div class="content-header">
            <h1>@yield('page-title','Dashboard')</h1>
            <div class="breadcrumb">
                @yield('breadcrumb')
            </div>
        </div>

        <div class="content-body">
            @yield('content-body')
        </div>
    </main>

</div>

<footer class="footer">
    <div class="container">
        <p>&copy; {{ date('Y') }} BASMAN · Dinas Lingkungan Hidup Kota Tegal</p>
        <p class="mb-0 small opacity-75">Versi 1.0.0</p>
    </div>
</footer>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title"><i class="fas fa-sign-out-alt text-danger me-2"></i>Konfirmasi keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body pt-2">
                Yakin ingin keluar dari sistem BASMAN?
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="{{ asset('js/sidebar.js') }}"></script>
<script>
function confirmLogout(){
    new bootstrap.Modal(document.getElementById('logoutModal')).show();
}
</script>

@yield('scripts')
</body>
</html>
