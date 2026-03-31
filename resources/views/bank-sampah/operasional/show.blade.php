@extends('layouts.bank-sampah')

@section('page-title', 'Detail Data Operasional')
@section('breadcrumb', 'Data Operasional / Detail')

@section('styles')
<link href="{{ asset('css/bank/operasional.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="operasional-show-container">
    @php
        $totalSdm = ($operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan) + ($operasional->nasabah_laki + $operasional->nasabah_perempuan);
        $isComplete = filled($operasional->tempat_penjualan)
            && filled($operasional->kegiatan_pengelolaan)
            && filled($operasional->sistem_pencatatan);
    @endphp

    <div class="page-header">
        <div>
            <h1>Data Operasional</h1>
            <p>{{ $bankSampah->nama_bank_sampah }} · {{ $bankSampah->kecamatan->nama_kecamatan ?? '-' }}</p>
        </div>
        <div class="download-options">
            <a href="{{ route('bank-sampah.operasional.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i> Edit Data
            </a>
            <a href="{{ route('bank-sampah.operasional.export.pdf') }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-2"></i> PDF
            </a>
            <a href="{{ route('bank-sampah.operasional.export.excel') }}" class="btn btn-success" target="_blank">
                <i class="fas fa-file-excel me-2"></i> Excel
            </a>
            <a href="{{ route('bank-sampah.operasional.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="table-section operasional-table-shell">
        <div class="table-header">
            <h3>Ringkasan Data Operasional</h3>
            <div class="table-info">Update: {{ $operasional->updated_at->translatedFormat('d F Y H:i') }}</div>
        </div>
        <div class="table-responsive">
            <table class="table operasional-detail-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
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
                                <a href="{{ route('bank-sampah.operasional.edit') }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('bank-sampah.operasional.export.pdf') }}" class="btn btn-sm btn-danger" title="PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('bank-sampah.operasional.export.excel') }}" class="btn btn-sm btn-success" title="Excel" target="_blank">
                                    <i class="fas fa-file-excel"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-section operasional-table-shell mt-3">
        <div class="table-header">
            <h3>Rincian Data Operasional</h3>
        </div>
        <div class="table-responsive">
            <table class="table operasional-detail-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 22%;">Kategori</th>
                        <th style="width: 28%;">Field</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td rowspan="4"><strong>Tenaga Kerja & Nasabah</strong></td><td>Tenaga Kerja Laki-laki</td><td>{{ $operasional->tenaga_kerja_laki }} orang</td></tr>
                    <tr><td>Tenaga Kerja Perempuan</td><td>{{ $operasional->tenaga_kerja_perempuan }} orang</td></tr>
                    <tr><td>Nasabah Laki-laki</td><td>{{ $operasional->nasabah_laki }} orang</td></tr>
                    <tr><td>Nasabah Perempuan</td><td>{{ $operasional->nasabah_perempuan }} orang</td></tr>
                    <tr><td rowspan="2"><strong>Omset & Penjualan</strong></td><td>Omset Bulanan</td><td>Rp {{ number_format($operasional->omset, 0, ',', '.') }}</td></tr>
                    <tr><td>Tempat Penjualan</td><td>{{ $operasional->tempat_penjualan ?: '-' }}</td></tr>
                    <tr><td><strong>Kegiatan Pengelolaan</strong></td><td>Deskripsi</td><td>{{ $operasional->kegiatan_pengelolaan ?: '-' }}</td></tr>
                    <tr><td><strong>Produk Daur Ulang</strong></td><td>Deskripsi</td><td>{{ $operasional->produk_daur_ulang ?: '-' }}</td></tr>
                    <tr><td rowspan="4"><strong>Sarana & Prasarana</strong></td><td>Buku Tabungan</td><td>{{ $operasional->buku_tabungan }}</td></tr>
                    <tr><td>Sistem Pencatatan</td><td>{{ $operasional->sistem_pencatatan }}</td></tr>
                    <tr><td>Timbangan</td><td>{{ $operasional->timbangan }}</td></tr>
                    <tr><td>Alat Pengangkut</td><td>{{ $operasional->alat_pengangkut }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection