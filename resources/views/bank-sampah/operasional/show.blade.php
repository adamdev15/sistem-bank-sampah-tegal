@extends('layouts.bank-sampah')

@section('page-title', 'Detail Data Operasional')
@section('breadcrumb', 'Data Operasional / Detail')

@section('styles')
<link href="{{ asset('css/bank/operasional.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="operasional-show-container">
    @php
        $totalTk = $operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan;
        $totalNs = $operasional->nasabah_laki + $operasional->nasabah_perempuan;
        $totalSdm = $totalTk + $totalNs;
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

    {{-- Ringkasan: tabel 2 kolom rapi --}}
    <div class="table-section operasional-table-shell mt-3">
        <div class="table-header">
            <h3>Ringkasan Data Operasional</h3>
            <div class="table-info">Update: {{ $operasional->updated_at->translatedFormat('d F Y H:i') }}</div>
        </div>
        <div class="table-responsive">
            <table class="table operasional-detail-table align-middle operasional-ringkasan-table mb-0">
                <tbody>
                    <tr>
                        <th scope="row" class="text-muted" style="width: 38%;">Terakhir diperbarui</th>
                        <td><strong>{{ $operasional->updated_at->translatedFormat('d F Y') }}</strong> <span class="text-muted small">{{ $operasional->updated_at->format('H:i') }}</span></td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Total tenaga kerja</th>
                        <td>{{ $totalTk }} orang</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Tenaga kerja laki-laki</th>
                        <td>{{ $operasional->tenaga_kerja_laki }} orang</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Tenaga kerja perempuan</th>
                        <td>{{ $operasional->tenaga_kerja_perempuan }} orang</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Total nasabah</th>
                        <td>{{ $totalNs }} orang</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Nasabah laki-laki</th>
                        <td>{{ $operasional->nasabah_laki }} orang</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Nasabah perempuan</th>
                        <td>{{ $operasional->nasabah_perempuan }} orang</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Total SDM (TK + nasabah)</th>
                        <td><strong>{{ $totalSdm }} orang</strong></td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Omset bulanan</th>
                        <td>Rp {{ number_format($operasional->omset, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Kegiatan pengelolaan</th>
                        <td>{{ $operasional->kegiatan_pengelolaan ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Tempat penjualan</th>
                        <td>{{ $operasional->tempat_penjualan_label }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Produk daur ulang</th>
                        <td>{{ $operasional->produk_daur_ulang ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Sistem pencatatan</th>
                        <td>{{ $operasional->sistem_pencatatan ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Buku tabungan</th>
                        <td>{{ $operasional->buku_tabungan_label }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Timbangan</th>
                        <td>{{ $operasional->timbangan_label }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Alat pengangkut</th>
                        <td>{{ $operasional->alat_pengangkut_label }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted">Status kelengkapan</th>
                        <td>
                            <span class="status-chip {{ $isComplete ? 'complete' : 'draft' }}">
                                {{ $isComplete ? 'Lengkap' : 'Perlu dilengkapi' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
