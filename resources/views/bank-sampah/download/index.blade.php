@extends('layouts.bank-sampah')

@section('page-title', 'Download Data')
@section('breadcrumb', 'Download Data')

@section('content-body')
<div class="download-container">
    @php
        $totalLaporan = $laporans->count();
        $totalMasuk = $laporans->sum('jumlah_sampah_masuk');
        $totalTerkelola = $laporans->sum('jumlah_sampah_terkelola');
        $avgNasabah = round($laporans->avg('jumlah_nasabah') ?? 0);
    @endphp
    <div class="download-header">
        <h2><i class="fas fa-download"></i> Download Data</h2>
        <p>Unduh data operasional dan laporan bulanan Anda</p>
    </div>

    <div class="row g-3 mb-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stats-single-item stat-report h-100">
            <div class="stat-top">
                <i class="fas fa-file-alt"></i>
                <span class="stat-label">Total Laporan</span>
            </div>
            <span class="stat-value">{{ $totalLaporan }}</span>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stats-single-item stat-report h-100">
            <div class="stat-top">
                <i class="fas fa-arrow-down"></i>
                <span class="stat-label">Total Sampah Masuk</span>
            </div>
            <span class="stat-value">
                {{ number_format($totalMasuk, 0, ',', '.') }} Kg
            </span>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stats-single-item stat-report h-100">
            <div class="stat-top">
                <i class="fas fa-recycle"></i>
                <span class="stat-label">Total Sampah Terkelola</span>
            </div>
            <span class="stat-value">
                {{ number_format($totalTerkelola, 0, ',', '.') }} Kg
            </span>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stats-single-item stat-report h-100">
            <div class="stat-top">
                <i class="fas fa-users"></i>
                <span class="stat-label">Rata-rata Nasabah</span>
            </div>
            <span class="stat-value">{{ $avgNasabah }} orang</span>
        </div>
    </div>
</div>

    <!-- Data Operasional Section -->
    <div class="download-section">
        <div class="section-header">
            <h3><i class="fas fa-chart-line"></i> Data Operasional</h3>
            <div class="section-actions">
@if($operasional)
    <div class="section-actions">
        <a href="{{ route('bank-sampah.download.operasional', ['format' => 'excel']) }}" 
           class="btn btn-success btn-sm" target="_blank">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
        <a href="{{ route('bank-sampah.download.operasional', ['format' => 'pdf']) }}" 
           class="btn btn-danger btn-sm" target="_blank">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
        {{-- Preview sementara nonaktif --}}
        {{-- <a href="#" class="btn btn-info btn-sm disabled">
            <i class="fas fa-eye"></i> Preview (Soon)
        </a> --}}
    </div>
@else
    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Data operasional belum diisi</span>
    <a href="{{ route('bank-sampah.operasional.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Isi Data Operasional
    </a>
@endif
            </div>
        </div>
        
        @if($operasional)
        <div class="table-responsive operasional-data-table">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>TK Laki-laki</th>
                        <th>TK Perempuan</th>
                        <th>Total TK</th>
                        <th>Nasabah Laki-laki</th>
                        <th>Nasabah Perempuan</th>
                        <th>Total Nasabah</th>
                        <th>Omset Bulanan</th>
                        <th>Tempat Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $operasional->tenaga_kerja_laki }} org</td>
                        <td>{{ $operasional->tenaga_kerja_perempuan }} org</td>
                        <td>{{ $operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan }} org</td>
                        <td>{{ $operasional->nasabah_laki }} org</td>
                        <td>{{ $operasional->nasabah_perempuan }} org</td>
                        <td>{{ $operasional->nasabah_laki + $operasional->nasabah_perempuan }} org</td>
                        <td>Rp {{ number_format($operasional->omset, 0, ',', '.') }}</td>
                        <td>{{ $operasional->tempat_penjualan ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Laporan Bulanan Section -->
    <div class="download-section">
        <div class="section-header">
            <h3><i class="fas fa-file-alt"></i> Laporan Bulanan</h3>
            <div class="section-actions">
                <!-- @if($laporans->count() > 0)
                    <a href="{{ route('bank-sampah.download.all-reports', ['format' => 'excel']) }}" 
                       class="btn btn-success btn-sm" target="_blank">
                        <i class="fas fa-file-excel"></i> Download Semua (Excel)
                    </a>
                    <a href="{{ route('bank-sampah.download.all-reports', ['format' => 'pdf']) }}" 
                       class="btn btn-danger btn-sm" target="_blank">
                        <i class="fas fa-file-pdf"></i> Download Semua (PDF)
                    </a>
                @endif -->
            </div>
        </div>

        <!-- Filter by Period Form -->
        <div class="period-filter-form">
            <form method="GET" action="{{ route('bank-sampah.download.reports-by-period') }}" target="_blank" class="row g-3 period-filter-row">
                @csrf
                <div class="col-12 col-sm-6 col-lg-3 filter-col">
                    <label for="start_date" class="form-label">Dari Bulan:</label>
                    <input type="month" class="form-control" id="start_date" name="start_date" 
                           min="2023-01" max="{{ date('Y-m') }}" required>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 filter-col">
                    <label for="end_date" class="form-label">Sampai Bulan:</label>
                    <input type="month" class="form-control" id="end_date" name="end_date" 
                           min="2023-01" max="{{ date('Y-m') }}" required>
                </div>
                <div class="col-12 col-sm-6 col-lg-3 filter-col">
                    <label class="form-label">Format:</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="format" id="format-excel" value="excel" checked>
                        <label class="btn btn-outline-success" for="format-excel">Excel</label>
                        
                        <input type="radio" class="btn-check" name="format" id="format-pdf" value="pdf">
                        <label class="btn btn-outline-danger" for="format-pdf">PDF</label>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3 filter-col">
                    <label class="form-label">Download:</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
            </form>
        </div>

        <!-- Laporan List -->
        <div class="laporan-list">
            <div class="table-responsive operasional-data-table">
                <table class="table table-hover align-middle mb-0">
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
                        @forelse($laporans as $laporan)
                        <tr>
                            <td>{{ $laporan->periode->translatedFormat('F Y') }}</td>
                            <td>{{ number_format($laporan->jumlah_sampah_masuk, 0, ',', '.') }}</td>
                            <td>{{ number_format($laporan->jumlah_sampah_terkelola, 0, ',', '.') }}</td>
                            <td>{{ $laporan->jumlah_nasabah }}</td>
                            <td>
                                <span class="status-badge status-{{ $laporan->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('bank-sampah.download.laporan', $laporan) }}" 
                                       class="btn btn-danger" title="Download PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('bank-sampah.download.preview.laporan', $laporan) }}" 
                                       class="btn btn-success" title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bank-sampah.laporan.show', $laporan) }}" 
                                       class="btn btn-secondary" title="Lihat Detail">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Belum ada laporan yang dibuat.
                                    <a href="{{ route('bank-sampah.laporan.create') }}" class="alert-link">
                                        Buat laporan pertama Anda
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <!-- Download Instructions -->
    <div class="instructions-section">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-info-circle"></i> Petunjuk Download</h4>
            </div>
            <div class="card-body">
                <ol>
                    <li><strong>Data Operasional:</strong> Unduh data operasional bank sampah dalam format Excel atau PDF.</li>
                    <li><strong>Laporan Per Bulan:</strong> Klik ikon PDF pada kolom aksi untuk download laporan bulan tertentu.</li>
                    <li><strong>Semua Laporan:</strong> Download semua laporan sekaligus dalam satu file.</li>
                    <li><strong>Berdasarkan Periode:</strong> Pilih rentang waktu untuk download laporan periode tertentu.</li>
                    <li><strong>Preview:</strong> Gunakan tombol preview untuk melihat data sebelum download.</li>
                </ol>
                <p class="mb-0"><strong>Catatan:</strong> File Excel cocok untuk analisis data, sedangkan PDF untuk dokumen resmi.</p>
            </div>
        </div>
    </div>
</div>

<style>
.download-container {
    padding: 20px;
}

.download-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #27ae60;
}

.download-header h2 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.download-header p {
    color: #7f8c8d;
    font-size: 16px;
}

.bank-info-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 5px solid #3498db;
}

.bank-info-card h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.bank-info-details p {
    margin: 5px 0;
    color: #34495e;
}

.download-section {
    background: white;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e0e0e0;
}

.section-header h3 {
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.operasional-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-top: 15px;
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.preview-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e0e0e0;
}

.preview-item:last-child {
    border-bottom: none;
}

.preview-item .label {
    color: #7f8c8d;
    font-weight: 500;
}

.preview-item .value {
    color: #2c3e50;
    font-weight: 600;
}

.operasional-data-table {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.operasional-data-table .table thead th {
    background: #f2f8f5;
    color: #1f5f46;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .02em;
}

.period-filter-form {
    background: #e8f4fc;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #b3e0ff;
}

.period-filter-row .filter-col {
    display: flex;
    flex-direction: column;
}

.period-filter-row .form-label {
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.laporan-list .table {
    margin-bottom: 20px;
}

.laporan-list .table th {
    background: #2c3e50;
    color: white;
    border: none;
}

.laporan-list .table td {
    vertical-align: middle;
}

.laporan-statistics {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.laporan-statistics h4 {
    color: #2c3e50;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.stats-single-card {
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 15px;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
}

.stats-single-item {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    border-left: 4px solid #1f5f46;
}

.stats-single-item .stat-top {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 6px;
}

.stats-single-item .stat-top i {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eef7f2;
    color: #1f5f46;
    font-size: 12px;
}

.stats-single-item .stat-label {
    display: block;
    color: #6b7f75;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .02em;
}

.stats-single-item .stat-value {
    display: block;
    color: #1f5f46;
    font-size: 20px;
    font-weight: 700;
}

.stats-single-item.stat-report { border-left-color: #1f5f46; }
.stats-single-item.stat-masuk { border-left-color: #0d6efd; }
.stats-single-item.stat-terkelola { border-left-color: #2f7d5a; }
.stats-single-item.stat-nasabah { border-left-color: #7c3aed; }

.stats-single-item.stat-masuk .stat-top i {
    background: #e7f1ff;
    color: #0d6efd;
}

.stats-single-item.stat-terkelola .stat-top i {
    background: #e8f5e9;
    color: #2f7d5a;
}

.stats-single-item.stat-nasabah .stat-top i {
    background: #f3ecff;
    color: #7c3aed;
}

.stat-item {
    background: white;
    border-radius: 6px;
    padding: 15px;
    text-align: center;
    border: 1px solid #e0e0e0;
}

.stat-item .stat-label {
    display: block;
    color: #7f8c8d;
    font-size: 12px;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.stat-item .stat-value {
    display: block;
    color: #27ae60;
    font-size: 20px;
    font-weight: bold;
}

@media (max-width: 900px) {
    .stats-single-card {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 560px) {
    .stats-single-card {
        grid-template-columns: 1fr;
    }
}

.instructions-section {
    margin-top: 30px;
}

.instructions-section .card {
    border: 1px solid #ffeaa7;
    background: #fffbf0;
}

.instructions-section .card-header {
    background: #fff3cd;
    border-bottom: 1px solid #ffeaa7;
    color: #856404;
}

.instructions-section ol {
    margin-bottom: 15px;
    padding-left: 20px;
}

.instructions-section li {
    margin-bottom: 8px;
    color: #5d6d7e;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
}

.status-disetujui { background: #d4edda; color: #155724; }
.status-menunggu_verifikasi { background: #fff3cd; color: #856404; }
.status-perlu_perbaikan { background: #f8d7da; color: #721c24; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates for period filter
    const now = new Date();
    const currentMonth = now.toISOString().slice(0, 7);
    const sixMonthsAgo = new Date(now.getFullYear(), now.getMonth() - 6, 1)
        .toISOString().slice(0, 7);
    
    document.getElementById('start_date').value = sixMonthsAgo;
    document.getElementById('end_date').value = currentMonth;
    
    // Validate date range
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
    
    endDateInput.addEventListener('change', function() {
        startDateInput.max = this.value;
    });
});
</script>
@endsection