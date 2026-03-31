@extends('layouts.bank-sampah')

@section('title', 'Detail Laporan')
@section('breadcrumb', 'Laporan / Detail')

@section('styles')
<link href="{{ asset('css/bank/laporan.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="laporan-show-container">
    @php
        $fmt = fn($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');
    @endphp
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">Detail Laporan Bulanan</h3>
                    <p class="mb-0 text-muted">Periode: {{ $laporan->periode->translatedFormat('F Y') }}</p>
                </div>
                <div class="status-badge status-{{ str_replace('_', '-', $laporan->status) }}">
                    {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Catatan Admin jika ada -->
            @if($laporan->status == 'perlu_perbaikan' && $laporan->catatan_verifikasi)
                <div class="alert alert-warning">
                    <h5><i class="fas fa-exclamation-circle"></i> Catatan dari Admin:</h5>
                    <p class="mb-0">{{ $laporan->catatan_verifikasi }}</p>
                </div>
            @endif
            
            <!-- Informasi Utama -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-label">Sampah Masuk</div>
                        <div class="info-value">{{ $fmt($laporan->jumlah_sampah_masuk) }} kg</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-label">Sampah Terkelola</div>
                        <div class="info-value">{{ $fmt($laporan->jumlah_sampah_terkelola) }} kg</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <div class="info-label">Jumlah Nasabah</div>
                        <div class="info-value">{{ $laporan->jumlah_nasabah }} orang</div>
                    </div>
                </div>
            </div>
            
            <!-- Rincian Jenis Sampah -->
            <div class="section">
                <h4 class="section-title">Rincian Jenis Sampah Terkelola</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jenis Sampah</th>
                                <th class="text-end">Jumlah (Kg)</th>
                                <th class="text-end">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalSampah = $laporan->jumlah_sampah_terkelola;
                                $totalDetails = 0;
                            @endphp
                            
                            @foreach($jenisSampah as $key => $label)
                                @php
                                    $detail = $laporan->details->firstWhere('jenis_sampah', $key);
                                    $jumlah = $detail ? $detail->jumlah : 0;
                                    $persentase = $totalSampah > 0 ? ($jumlah / $totalSampah * 100) : 0;
                                    $totalDetails += $jumlah;
                                @endphp
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td class="text-end">{{ $fmt($jumlah) }}</td>
                                    <td class="text-end">{{ $fmt($persentase) }}%</td>
                                </tr>
                            @endforeach
                            
                            <!-- Total Row -->
                            <tr class="table-secondary">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-end"><strong>{{ $fmt($totalDetails) }} kg</strong></td>
                                <td class="text-end"><strong>100%</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Informasi Laporan -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="info-section">
                        <h5><i class="fas fa-info-circle"></i> Informasi Laporan</h5>
                        <table class="table table-sm">
                            <tr>
                                <td width="40%">Tanggal Dibuat</td>
                                <td>{{ $laporan->created_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Terakhir Diupdate</td>
                                <td>{{ $laporan->updated_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Status Verifikasi</td>
                                <td>
                                    <span class="badge status-{{ str_replace('_', '-', $laporan->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-section">
                        <h5><i class="fas fa-chart-pie"></i> Statistik</h5>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-label">Efisiensi Pengelolaan</div>
                                <div class="stat-value">
                                    @php
                                        $efisiensi = $totalSampah > 0 ? ($laporan->jumlah_sampah_terkelola / $laporan->jumlah_sampah_masuk * 100) : 0;
                                    @endphp
                                    {{ $fmt($efisiensi) }}%
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Sampah Tersisa</div>
                                <div class="stat-value">
                                    @php
                                        $sisa = $laporan->jumlah_sampah_masuk - $laporan->jumlah_sampah_terkelola;
                                    @endphp
                                    {{ $fmt($sisa) }} kg
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="action-buttons mt-4">
                <a href="{{ route('bank-sampah.laporan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
                
                @if($laporan->status != 'disetujui')
                    <a href="{{ route('bank-sampah.laporan.edit', $laporan) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Laporan
                    </a>
                @endif
                
                <button type="button" class="btn btn-success" onclick="printLaporan()">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
                
                <a href="{{ route('bank-sampah.download.laporan', $laporan) }}" class="btn btn-danger">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
        </div>
</div>

<script>
function printLaporan() {
    window.print();
}

// Print styles
const printStyle = `
    @media print {
        .sidebar, .header, .footer, .action-buttons, .breadcrumb {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        body {
            font-size: 12pt;
            background: white !important;
        }
        
        .table th, .table td {
            border: 1px solid #000 !important;
        }
    }
`;

const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = printStyle;
document.head.appendChild(styleSheet);
</script>
@endsection