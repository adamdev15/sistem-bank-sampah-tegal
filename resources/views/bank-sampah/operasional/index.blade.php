@extends('layouts.bank-sampah')

@section('page-title', 'Data Operasional')
@section('breadcrumb', 'Data Operasional')

@section('styles')
<link href="{{ asset('css/bank/operasional.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="operasional-container">
    @if($operasional)
    @php
        $totalSdm = ($operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan) + ($operasional->nasabah_laki + $operasional->nasabah_perempuan);
        $isComplete = filled($operasional->tempat_penjualan)
            && filled($operasional->kegiatan_pengelolaan)
            && filled($operasional->sistem_pencatatan);
    @endphp
    <div class="page-header">
        <div>
            <h1>Data Operasional</h1>
            <p>Ringkasan data operasional bank sampah Anda</p>
        </div>
        <div class="download-options">
            <a href="{{ route('bank-sampah.operasional.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i> Edit Data
            </a>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download me-2"></i> Download Data
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('bank-sampah.operasional.export.pdf') }}" target="_blank">
                            <i class="fas fa-file-pdf text-danger me-2"></i> Download PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('bank-sampah.operasional.export.excel') }}" target="_blank">
                            <i class="fas fa-file-excel text-success me-2"></i> Download Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="table-section operasional-table-shell">
        <div class="table-header">
            <h3>Daftar Data Operasional</h3>
            <div class="table-info">Menampilkan 1 - 1 dari 1 data</div>
        </div>
        <div class="table-responsive">
            <table class="table operasional-detail-table align-middle">
                <thead>
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Periode Update</th>
                        <th class="text-end">Total SDM</th>
                        <th class="text-end">Omset Bulanan</th>
                        <th>Sistem Pencatatan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <strong>{{ $operasional->updated_at->translatedFormat('d F Y') }}</strong><br>
                            <small class="text-muted">{{ $operasional->updated_at->translatedFormat('H:i') }}</small>
                        </td>
                        <td class="text-end">{{ $totalSdm }}</td>
                        <td class="text-end">Rp {{ number_format($operasional->omset, 0, ',', '.') }}</td>
                        <td>{{ $operasional->sistem_pencatatan ?: '-' }}</td>
                        <td>
                            <span class="status-chip {{ $isComplete ? 'complete' : 'draft' }}">
                                {{ $isComplete ? 'Lengkap' : 'Perlu Dilengkapi' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="row-actions">
                                <a href="{{ route('bank-sampah.operasional.show') }}" class="btn btn-sm btn-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('bank-sampah.operasional.edit') }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('bank-sampah.operasional.export.pdf') }}" class="btn btn-sm btn-danger" title="PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-database"></i>
        </div>
        <h3>Data Operasional Belum Diisi</h3>
        <p>Anda belum mengisi data operasional bank sampah. Silahkan isi data untuk melengkapi profil bank sampah Anda.</p>
        <a href="{{ route('bank-sampah.operasional.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i> Isi Data Operasional
        </a>
    </div>
    @endif
</div>
@endsection