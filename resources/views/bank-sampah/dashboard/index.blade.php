@extends('layouts.bank-sampah')

@section('styles')
<link href="{{ asset('css/bank/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content-body')
@php
    $totalTenagaKerja = $operasional ? ($operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan) : 0;
    $totalNasabah = $operasional ? ($operasional->nasabah_laki + $operasional->nasabah_perempuan) : 0;
    $statusLabel = $laporanBulanIni ? ucfirst(str_replace('_', ' ', $laporanBulanIni->status)) : 'Belum Melapor';
    $laporanProgress = $laporanBulanIni
        ? ($laporanBulanIni->status === 'disetujui' ? 100 : ($laporanBulanIni->status === 'menunggu_verifikasi' ? 70 : 45))
        : 15;
@endphp
<div class="bank-dashboard">

    {{-- =====================
        WELCOME
    ===================== --}}
    <div class="bank-welcome-section">
        <div class="bank-welcome-content">
            <h1>Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p>Bank Sampah: <strong>{{ $bankSampah->nama_bank_sampah }}</strong></p>

            <div class="bank-welcome-info">
                <span>
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $bankSampah->kecamatan->nama_kecamatan ?? '-' }},
                    {{ $bankSampah->kelurahan->nama_kelurahan ?? '-' }}
                </span>
                <span>
                    <i class="fas fa-user-tie"></i>
                    {{ $bankSampah->nama_direktur }}
                </span>
                <span>
                    <i class="fas fa-phone"></i>
                    {{ $bankSampah->nomor_hp }}
                </span>
            </div>
        </div>

        <div class="bank-hero-side">
            <span class="bank-status-badge bank-status-active">
                <i class="fas fa-check-circle"></i> Akun Aktif
            </span>
            <div class="bank-progress-snapshot">
                <small>Status Laporan: {{ $statusLabel }}</small>
                <div class="progress" role="progressbar" aria-label="Progres laporan">
                    <div class="progress-bar bg-light text-dark" style="width: {{ $laporanProgress }}%;">{{ $laporanProgress }}%</div>
                </div>
            </div>
        </div>
    </div>

    {{-- =====================
        QUICK STATS
    ===================== --}}
    <div class="bank-quick-stats">

        <div class="bank-stat-card">
            <div class="bank-stat-icon" style="background:#3498db">
                <i class="fas fa-users"></i>
            </div>
            <div class="bank-stat-info">
                <h3>{{ $totalTenagaKerja }}</h3>
                <p>Tenaga Kerja</p>
                <small>Pengelola aktif</small>
            </div>
        </div>

        <div class="bank-stat-card">
            <div class="bank-stat-icon" style="background:#2f7d5a">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="bank-stat-info">
                <h3>{{ $totalNasabah }}</h3>
                <p>Total Nasabah</p>
                <small>Nasabah terdaftar</small>
            </div>
        </div>

        <div class="bank-stat-card">
            <div class="bank-stat-icon" style="background:#e67e22">
                <i class="fas fa-trash-restore"></i>
            </div>
            <div class="bank-stat-info">
                <h3>{{ number_format($totalSampah,0,',','.') }}</h3>
                <p>Kg Sampah Terkelola</p>
                <small>Akumulasi laporan</small>
            </div>
        </div>

    </div>

    {{-- =====================
        ACTION SECTION
    ===================== --}}
    <div class="bank-action-section">

        {{-- STATUS LAPORAN --}}
        <div class="bank-action-card">
            <div class="bank-card-header">
                <h3><i class="fas fa-file-alt"></i> Status Laporan Bulan Ini</h3>
            </div>

            <div class="bank-card-body">
                <div class="table-responsive">
                    <table class="table bank-status-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($laporanBulanIni)
                            <tr>
                                <td>{{ $laporanBulanIni->periode->translatedFormat('F Y') }}</td>
                                <td>
                                    <span class="bank-status-badge bank-status-{{ str_replace('_','-',$laporanBulanIni->status) }}">
                                        {{ ucfirst(str_replace('_',' ',$laporanBulanIni->status)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="bank-table-actions">
                                        <a href="{{ route('bank-sampah.laporan.show',$laporanBulanIni->id) }}"
                                           class="bank-btn-action bank-btn-sm bank-btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($laporanBulanIni->status === 'perlu_perbaikan')
                                            <a href="{{ route('bank-sampah.laporan.edit',$laporanBulanIni->id) }}"
                                               class="bank-btn-action bank-btn-sm bank-btn-warning" title="Perbaiki">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td>{{ now()->translatedFormat('F Y') }}</td>
                                <td>
                                    <span class="bank-status-badge bank-status-none">
                                        <i class="fas fa-exclamation-circle"></i> Belum Melapor
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('bank-sampah.laporan.create') }}"
                                       class="bank-btn-action bank-btn-sm bank-btn-success" title="Buat Laporan">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($laporanBulanIni && $laporanBulanIni->catatan_verifikasi)
                    <div class="bank-catatan mt-3">
                        <strong><i class="fas fa-comment-dots"></i> Catatan Admin</strong>
                        <p>{{ $laporanBulanIni->catatan_verifikasi }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- DATA OPERASIONAL --}}
        <div class="bank-action-card">
            <div class="bank-card-header">
                <h3><i class="fas fa-chart-line"></i> Data Operasional Terbaru</h3>
            </div>

            <div class="bank-card-body">
                @if($operasional)
                    <div class="bank-data-info">
                        <div class="bank-data-item">
                            <span><i class="fas fa-male"></i> Tenaga Kerja Laki-laki</span>
                            <strong>{{ $operasional->tenaga_kerja_laki }} org</strong>
                        </div>
                        <div class="bank-data-item">
                            <span><i class="fas fa-female"></i> Tenaga Kerja Perempuan</span>
                            <strong>{{ $operasional->tenaga_kerja_perempuan }} org</strong>
                        </div>
                        <div class="bank-data-item">
                            <span><i class="fas fa-users"></i> Total Nasabah</span>
                            <strong>{{ $operasional->nasabah_laki + $operasional->nasabah_perempuan }} org</strong>
                        </div>
                        <div class="bank-data-item">
                            <span><i class="fas fa-money-bill-wave"></i> Omset</span>
                            <strong>Rp {{ number_format($operasional->omset,0,',','.') }}</strong>
                        </div>
                    </div>

                    <div class="bank-action-buttons">
                        <a href="{{ route('bank-sampah.operasional.edit') }}"
                           class="bank-btn-action bank-btn-primary">
                            <i class="fas fa-edit"></i> Update
                        </a>
                        <a href="{{ route('bank-sampah.operasional.index') }}"
                           class="bank-btn-action bank-btn-secondary">
                            <i class="fas fa-history"></i> Riwayat
                        </a>
                    </div>
                @else
                    <div class="bank-empty-state">
                        <div class="bank-empty-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <p>Data operasional belum diisi</p>
                        <a href="{{ route('bank-sampah.operasional.edit') }}"
                           class="bank-btn-action bank-btn-success">
                            <i class="fas fa-plus"></i> Isi Data
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- =====================
        LAPORAN TERBARU
    ===================== --}}
    <div class="bank-recent-section">
        <div class="bank-section-header">
            <h3><i class="fas fa-history"></i> Laporan Terbaru</h3>
            <a href="{{ route('bank-sampah.laporan.index') }}" class="bank-btn-link">
                Lihat Semua
            </a>
        </div>

        @if($recentLaporan->count())
        <div class="bank-recent-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Periode</th>
                        <th>Masuk (kg)</th>
                        <th>Terkelola (kg)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLaporan as $i => $laporan)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $laporan->periode->translatedFormat('F Y') }}</td>
                        <td>{{ number_format($laporan->jumlah_sampah_masuk,0,',','.') }}</td>
                        <td>{{ number_format($laporan->jumlah_sampah_terkelola,0,',','.') }}</td>
                        <td>
                            <span class="bank-status-badge bank-status-{{ str_replace('_','-',$laporan->status) }}">
                                {{ ucfirst(str_replace('_',' ',$laporan->status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="bank-table-actions">
                                <a href="{{ route('bank-sampah.laporan.show',$laporan->id) }}"
                                   class="bank-btn-action bank-btn-sm bank-btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($laporan->status !== 'disetujui')
                                <a href="{{ route('bank-sampah.laporan.edit',$laporan->id) }}"
                                   class="bank-btn-action bank-btn-sm bank-btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="bank-empty-state">
                <div class="bank-empty-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <p>Belum ada laporan</p>
            </div>
        @endif
    </div>

</div>
@endsection