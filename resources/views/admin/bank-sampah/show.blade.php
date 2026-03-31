@extends('layouts.admin')

@section('page-title', 'Detail Bank Sampah')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.bank-sampah.index') }}">Bank Sampah</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
</nav>
@endsection

@section('styles')
<link href="{{ asset('css/bank-sampah.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="bank-sampah-detail">
    <!-- Header Info -->
    <div class="detail-header">
        <div class="header-info">
            <h2>{{ $bankSampah->nama_bank_sampah }}</h2>
            <div class="bank-meta">
                <span class="badge status-{{ strtolower($bankSampah->status_terbentuk) }}">
                    {{ $bankSampah->status_terbentuk }}
                </span>
                <span class="location">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $bankSampah->kecamatan->nama_kecamatan }}, 
                    {{ $bankSampah->kelurahan->nama_kelurahan }}, RW {{ $bankSampah->rw }}
                </span>
            </div>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('admin.bank-sampah.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="detail-tabs">
        <ul class="nav nav-tabs" id="bankTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                    <i class="fas fa-info-circle"></i> Informasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="akun-tab" data-bs-toggle="tab" data-bs-target="#akun" type="button">
                    <i class="fas fa-user"></i> Akun
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="operasional-tab" data-bs-toggle="tab" data-bs-target="#operasional" type="button">
                    <i class="fas fa-chart-line"></i> Operasional
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="laporan-tab" data-bs-toggle="tab" data-bs-target="#laporan" type="button">
                    <i class="fas fa-file-alt"></i> Laporan
                </button>
            </li>
        </ul>

        <div class="tab-content" id="bankTabContent">
            <!-- Tab 1: Informasi Bank Sampah -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <div class="info-grid info-grid-single">
                    <div class="info-section merged-info-section">
                        <div class="info-subsection">
                            <h4><i class="fas fa-id-card"></i> Data Identitas</h4>
                            <div class="info-row">
                                <span class="label">Nama Bank Sampah</span>
                                <span class="value">{{ $bankSampah->nama_bank_sampah }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Nomor SK</span>
                                <span class="value">{{ $bankSampah->nomor_sk ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Status Terbentuk</span>
                                <span class="value">
                                    <span class="badge status-{{ strtolower($bankSampah->status_terbentuk) }}">
                                        {{ $bankSampah->status_terbentuk }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="label">Keterangan</span>
                                <span class="value">{{ $bankSampah->keterangan ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="info-subsection">
                            <h4><i class="fas fa-map-marked-alt"></i> Lokasi</h4>
                            <div class="info-row">
                                <span class="label">Kecamatan</span>
                                <span class="value">{{ $bankSampah->kecamatan->nama_kecamatan }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Kelurahan</span>
                                <span class="value">{{ $bankSampah->kelurahan->nama_kelurahan }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">RW</span>
                                <span class="value">{{ $bankSampah->rw }}</span>
                            </div>
                        </div>

                        <div class="info-subsection">
                            <h4><i class="fas fa-user-tie"></i> Kontak</h4>
                            <div class="info-row">
                                <span class="label">Nama Direktur</span>
                                <span class="value">{{ $bankSampah->nama_direktur }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Nomor HP</span>
                                <span class="value">
                                    <a href="tel:{{ $bankSampah->nomor_hp }}">{{ $bankSampah->nomor_hp }}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Informasi Akun -->
            <div class="tab-pane fade" id="akun" role="tabpanel">
                @if($bankSampah->user)
                    <div class="account-info">
                        <div class="account-status">
                            <h4>Status Akun</h4>
                            @php
                                $statusClass = [
                                    'menunggu_verifikasi' => 'status-waiting',
                                    'aktif' => 'status-active',
                                    'ditolak' => 'status-rejected'
                                ][$bankSampah->user->status] ?? 'status-waiting';
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $bankSampah->user->status)) }}
                            </span>
                        </div>

                        <div class="info-grid">
                            <div class="info-section">
                                <h4><i class="fas fa-user-circle"></i> Data Akun</h4>
                                <div class="info-row">
                                    <span class="label">Nama Pengguna</span>
                                    <span class="value">{{ $bankSampah->user->name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Email</span>
                                    <span class="value">{{ $bankSampah->user->email }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Tanggal Daftar</span>
                                    <span class="value">{{ $bankSampah->user->created_at->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Terakhir Login</span>
                                    <span class="value">
                                        {{ $bankSampah->user->last_login_at ? $bankSampah->user->last_login_at->diffForHumans() : 'Belum pernah login' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="account-actions">
                            @if($bankSampah->user->status === 'menunggu_verifikasi')
                                <form action="{{ route('admin.users.verify', $bankSampah->user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="aktif">
                                    <button type="submit" class="btn-success">
                                        <i class="fas fa-check"></i> Aktifkan Akun
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.verify', $bankSampah->user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="btn-danger">
                                        <i class="fas fa-times"></i> Tolak Akun
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('admin.users.reset', $bankSampah->user) }}" class="btn-warning">
                                <i class="fas fa-key"></i> Reset Password
                            </a>
                        </div>
                    </div>
                @else
                    <div class="no-account">
                        <div class="alert alert-info">
                            <h4><i class="fas fa-info-circle"></i> Belum Memiliki Akun</h4>
                            <p>Bank sampah ini belum melakukan registrasi akun.</p>
                            <p>Informasikan kepada bank sampah untuk mendaftar melalui halaman registrasi.</p>
                        </div>
                        <div class="registration-info">
                            <h5>Informasi Registrasi:</h5>
                            <ul>
                                <li>Bank sampah harus mendaftar sendiri melalui halaman registrasi</li>
                                <li>Pilih bank sampah "{{ $bankSampah->nama_bank_sampah }}" saat registrasi</li>
                                <li>Akun akan muncul di menu "Verifikasi Akun" setelah registrasi</li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tab 3: Data Operasional -->
            <div class="tab-pane fade" id="operasional" role="tabpanel">
                @if($bankSampah->operasional)
                    <div class="operasional-info">
                        <div class="info-grid">
                            <div class="info-section">
                                <h4><i class="fas fa-users"></i> Tenaga Kerja</h4>
                                <div class="info-row">
                                    <span class="label">Laki-laki</span>
                                    <span class="value">{{ $bankSampah->operasional->tenaga_kerja_laki }} orang</span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Perempuan</span>
                                    <span class="value">{{ $bankSampah->operasional->tenaga_kerja_perempuan }} orang</span>
                                </div>
                                <div class="info-row total">
                                    <span class="label">Total</span>
                                    <span class="value">
                                        {{ $bankSampah->operasional->tenaga_kerja_laki + $bankSampah->operasional->tenaga_kerja_perempuan }} orang
                                    </span>
                                </div>
                            </div>

                            <div class="info-section">
                                <h4><i class="fas fa-user-friends"></i> Nasabah</h4>
                                <div class="info-row">
                                    <span class="label">Laki-laki</span>
                                    <span class="value">{{ $bankSampah->operasional->nasabah_laki }} orang</span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Perempuan</span>
                                    <span class="value">{{ $bankSampah->operasional->nasabah_perempuan }} orang</span>
                                </div>
                                <div class="info-row total">
                                    <span class="label">Total</span>
                                    <span class="value">
                                        {{ $bankSampah->operasional->nasabah_laki + $bankSampah->operasional->nasabah_perempuan }} orang
                                    </span>
                                </div>
                            </div>

                            <div class="info-section">
                                <h4><i class="fas fa-money-bill-wave"></i> Keuangan</h4>
                                <div class="info-row">
                                    <span class="label">Omset Bulanan</span>
                                    <span class="value">
                                        Rp {{ number_format($bankSampah->operasional->omset, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Tempat Penjualan</span>
                                    <span class="value">{{ $bankSampah->operasional->tempat_penjualan ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-grid">
                            <div class="info-section">
                                <h4><i class="fas fa-recycle"></i> Kegiatan & Produk</h4>
                                <div class="info-row">
                                    <span class="label">Kegiatan Pengelolaan</span>
                                    <span class="value">{{ $bankSampah->operasional->kegiatan_pengelolaan ?? '-' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Produk Daur Ulang</span>
                                    <span class="value">{{ $bankSampah->operasional->produk_daur_ulang ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="info-section">
                                <h4><i class="fas fa-tools"></i> Sarana & Prasarana</h4>
                                <div class="info-row">
                                    <span class="label">Buku Tabungan</span>
                                    <span class="value">
                                        <span class="badge {{ $bankSampah->operasional->buku_tabungan == 'Ya' ? 'status-active' : 'status-none' }}">
                                            {{ $bankSampah->operasional->buku_tabungan }}
                                        </span>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Sistem Pencatatan</span>
                                    <span class="value">{{ $bankSampah->operasional->sistem_pencatatan }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Timbangan</span>
                                    <span class="value">
                                        <span class="badge {{ $bankSampah->operasional->timbangan == 'Ya' ? 'status-active' : 'status-none' }}">
                                            {{ $bankSampah->operasional->timbangan }}
                                        </span>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="label">Alat Pengangkut</span>
                                    <span class="value">
                                        <span class="badge {{ $bankSampah->operasional->alat_pengangkut == 'Ya' ? 'status-active' : 'status-none' }}">
                                            {{ $bankSampah->operasional->alat_pengangkut }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="no-operasional">
                        <div class="alert alert-warning">
                            <h4><i class="fas fa-exclamation-triangle"></i> Data Operasional Belum Diisi</h4>
                            <p>Bank sampah belum mengisi data operasional.</p>
                            <p>Data akan muncul setelah bank sampah mengisi melalui dashboard mereka.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tab 4: Laporan Bulanan -->
            <div class="tab-pane fade" id="laporan" role="tabpanel">
                <div class="laporan-section">
                    @if($bankSampah->laporans->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Sampah Masuk (Kg)</th>
                                        <th>Sampah Terkelola (Kg)</th>
                                        <th>Nasabah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bankSampah->laporans as $laporan)
                                    <tr>
                                        <td>{{ $laporan->periode->translatedFormat('F Y') }}</td>
                                        <td>{{ number_format($laporan->jumlah_sampah_masuk, 0, ',', '.') }}</td>
                                        <td>{{ number_format($laporan->jumlah_sampah_terkelola, 0, ',', '.') }}</td>
                                        <td>{{ $laporan->jumlah_nasabah }}</td>
                                        <td>
                                            <span class="badge status-{{ str_replace('_', '-', $laporan->status) }}">
                                                {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.laporan.show', $laporan) }}" class="btn-view">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="laporan-summary">
                            <h5>Statistik Laporan:</h5>
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <span class="label">Total Laporan</span>
                                    <span class="value">{{ $bankSampah->laporans->count() }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="label">Disetujui</span>
                                    <span class="value">
                                        {{ $bankSampah->laporans->where('status', 'disetujui')->count() }}
                                    </span>
                                </div>
                                <div class="summary-item">
                                    <span class="label">Menunggu</span>
                                    <span class="value">
                                        {{ $bankSampah->laporans->where('status', 'menunggu_verifikasi')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="no-laporan">
                            <div class="alert alert-info">
                                <h4><i class="fas fa-info-circle"></i> Belum Ada Laporan</h4>
                                <p>Bank sampah belum membuat laporan bulanan.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aktifkan tabs Bootstrap
    var triggerTabList = [].slice.call(document.querySelectorAll('#bankTab button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
});
</script>
@endsection