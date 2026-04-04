@extends('layouts.admin')

@section('page-title', 'Export Data')
@section('breadcrumb', 'Export Data')

@section('styles')
<link href="{{ asset('css/export.css') }}" rel="stylesheet">
<style>
.export-single-page .export-type-card-v2 {
    border: 2px solid #e3ece7;
    border-radius: 14px;
    padding: 20px 18px;
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s, transform .2s, background .2s;
    background: linear-gradient(180deg, #fbfcfb 0%, #fff 55%);
    height: 100%;
    position: relative;
    overflow: hidden;
}
.export-single-page .export-type-card-v2::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, #1f5f46, #2f7d5a);
    opacity: 0;
    transition: opacity .2s;
}
.export-single-page .export-type-card-v2:hover {
    border-color: #2f7d5a;
    box-shadow: 0 10px 28px rgba(31, 95, 70, 0.12);
    transform: translateY(-3px);
}
.export-single-page .export-type-card-v2.active {
    border-color: #1f5f46;
    background: #f4faf7;
    box-shadow: 0 8px 22px rgba(31, 95, 70, 0.14);
}
.export-single-page .export-type-card-v2.active::before { opacity: 1; }
.export-single-page .export-type-card-v2 input { position: absolute; opacity: 0; pointer-events: none; }
.export-single-page .etc-icon-v2 {
    width: 52px; height: 52px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.25rem;
    background: linear-gradient(135deg, #1f5f46, #2f7d5a);
    flex-shrink: 0;
}
.export-single-page .etc-body-v2 h4 { font-size: 1rem; font-weight: 700; color: #1f3d2b; margin: 0 0 6px; }
.export-single-page .etc-body-v2 p { font-size: 0.82rem; color: #5d7268; margin: 0 0 10px; }
.export-single-page .etc-tags { display: flex; flex-wrap: wrap; gap: 6px; }
.export-single-page .etc-tags span {
    font-size: 0.68rem; padding: 4px 8px; border-radius: 6px;
    background: #e8f5e9; color: #1f5f46; font-weight: 600;
}
.export-single-page .format-row-v2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 576px) { .export-single-page .format-row-v2 { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content-body')
<div class="data-container export-single-page">
    <div class="export-form-wrapper">
        <div class="form-card border-0 shadow-sm">
            <div class="card-header text-white border-0 rounded-top" style="background: linear-gradient(135deg, #1f5f46 0%, #2f7d5a 100%);">
                <div class="header-content">
                    <h1 class="h4 mb-2"><i class="fas fa-file-export me-2"></i>Export Data</h1>
                    <p class="subtitle mb-1 opacity-90">BASMAN — Bank Sampah Management System</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.export.generate') }}" id="exportForm" class="export-form">
                @csrf

                <div class="form-section border-bottom pb-4 mb-4">
                    <div class="section-header mb-3">
                        <h3 class="h5 mb-1"><i class="fas fa-database me-2 text-success"></i>Jenis data</h3>
                        <p class="section-description mb-0 small">Tiga kategori utama ekspor BASMAN</p>
                    </div>
                    <div class="radio-group-grid">
                        <label class="export-type-card-v2 active" data-type-card="master">
                            <input type="radio" name="type" value="master" checked>
                            <div class="d-flex gap-3 align-items-start">
                                <div class="etc-icon-v2"><i class="fas fa-university"></i></div>
                                <div class="etc-body-v2 flex-grow-1">
                                    <h4>Data Master Bank Sampah</h4>
                                    <p>Identitas, lokasi, dan status terbentuk (Sheet 1).</p>
                                    <div class="etc-tags">
                                        <span>Identitas</span><span>Lokasi</span><span>Kontak</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="export-type-card-v2" data-type-card="operasional">
                            <input type="radio" name="type" value="operasional">
                            <div class="d-flex gap-3 align-items-start">
                                <div class="etc-icon-v2"><i class="fas fa-chart-line"></i></div>
                                <div class="etc-body-v2 flex-grow-1">
                                    <h4>Data Operasional</h4>
                                    <p>Tenaga kerja, nasabah, omset, sarana (Sheet 2).</p>
                                    <div class="etc-tags">
                                        <span>SDM</span><span>Keuangan</span><span>Prasarana</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="export-type-card-v2" data-type-card="laporan">
                            <input type="radio" name="type" value="laporan">
                            <div class="d-flex gap-3 align-items-start">
                                <div class="etc-icon-v2"><i class="fas fa-file-alt"></i></div>
                                <div class="etc-body-v2 flex-grow-1">
                                    <h4>Laporan Bulanan</h4>
                                    <p>Rekap sampah masuk/terkelola dan rincian jenis (Sheet 3).</p>
                                    <div class="etc-tags">
                                        <span>Masuk</span><span>Terkelola</span><span>Rincian</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-section border-bottom pb-4 mb-4">
                    <div class="section-header mb-3">
                        <h3 class="h5 mb-1"><i class="fas fa-file-export me-2 text-success"></i>Format file</h3>
                        <p class="section-description mb-0 small">Excel untuk analisis; PDF untuk cetak</p>
                    </div>
                    <div class="format-row-v2">
                        <div class="format-option active" onclick="selectFormat('excel')">
                            <input type="radio" id="format_excel" name="format" value="excel" checked>
                            <div class="format-content">
                                <div class="format-icon excel"><i class="fas fa-file-excel"></i></div>
                                <div class="format-info">
                                    <h4 class="format-title-row">Excel <span class="format-badge">.xlsx</span></h4>
                                    <p class="small mb-0">Spreadsheet, disarankan untuk data besar</p>
                                </div>
                            </div>
                        </div>
                        <div class="format-option" onclick="selectFormat('pdf')">
                            <input type="radio" id="format_pdf" name="format" value="pdf">
                            <div class="format-content">
                                <div class="format-icon pdf"><i class="fas fa-file-pdf"></i></div>
                                <div class="format-info">
                                    <h4 class="format-title-row">PDF <span class="format-badge">.pdf</span></h4>
                                    <p class="small mb-0">Dokumen siap cetak / arsip</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-0">
                    <div class="section-header mb-3">
                        <h3 class="h5 mb-1"><i class="fas fa-filter me-2 text-success"></i>Filter</h3>
                        <p class="section-description mb-0 small">Filter tanggal hanya berlaku untuk laporan bulanan</p>
                    </div>
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label for="kecamatan_id"><i class="fas fa-map-marker-alt me-1"></i> Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-select">
                                <option value="">Semua Kecamatan</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group" id="year-filter" style="display: none;">
                            <label for="tahun"><i class="fas fa-calendar me-1"></i> Tahun laporan</label>
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group date-group" id="date-start-filter" style="display: none;">
                            <label for="start_date"><i class="fas fa-calendar-alt me-1"></i> Periode mulai</label>
                            <input type="month" name="start_date" id="start_date" class="form-control" min="2023-01" max="{{ date('Y-m') }}">
                        </div>
                        <div class="filter-group date-group" id="date-end-filter" style="display: none;">
                            <label for="end_date"><i class="fas fa-calendar-alt me-1"></i> Periode akhir</label>
                            <input type="month" name="end_date" id="end_date" class="form-control" min="2023-01" max="{{ date('Y-m') }}">
                        </div>
                    </div>
                </div>

                <div class="form-actions mt-4 pt-3 border-top">
                    <div class="action-info mb-3 mb-md-0">
                        <i class="fas fa-info-circle"></i>
                        <p class="mb-0 small">Periksa jenis data dan format sebelum generate.</p>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i> Batal</a>
                        <button type="submit" class="btn btn-success" id="exportButton">
                            <i class="fas fa-download me-1"></i> Generate export
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-3"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif
</div>

<script>
function selectFormat(format) {
    document.querySelectorAll('input[name="format"]').forEach(r => { r.checked = false; });
    document.getElementById('format_' + format).checked = true;
    document.querySelectorAll('.format-option').forEach(o => o.classList.remove('active'));
    document.querySelector(`[onclick="selectFormat('${format}')"]`)?.classList.add('active');
}

function toggleFilters() {
    const type = document.querySelector('input[name="type"]:checked')?.value;
    const show = type === 'laporan';
    ['year-filter', 'date-start-filter', 'date-end-filter'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = show ? 'block' : 'none';
    });
}

document.querySelectorAll('.export-type-card-v2').forEach(card => {
    card.addEventListener('click', function () {
        const input = this.querySelector('input[name="type"]');
        if (input) input.checked = true;
        document.querySelectorAll('.export-type-card-v2').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        toggleFilters();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    toggleFilters();

    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    if (startDate && endDate) {
        startDate.addEventListener('change', function () {
            if (this.value) {
                endDate.min = this.value;
                if (endDate.value && endDate.value < this.value) endDate.value = this.value;
            }
        });
        endDate.addEventListener('change', function () {
            if (this.value) {
                startDate.max = this.value;
                if (startDate.value && startDate.value > this.value) startDate.value = this.value;
            }
        });
    }

    const form = document.getElementById('exportForm');
    const exportButton = document.getElementById('exportButton');
    form.addEventListener('submit', function (e) {
        const type = document.querySelector('input[name="type"]:checked')?.value;
        if (type === 'laporan' && startDate && endDate && startDate.value && endDate.value && startDate.value > endDate.value) {
            e.preventDefault();
            alert('Periode mulai tidak boleh lebih besar dari periode akhir.');
            return;
        }
        exportButton.disabled = true;
        exportButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses…';
        setTimeout(() => {
            exportButton.disabled = false;
            exportButton.innerHTML = '<i class="fas fa-download me-1"></i> Generate export';
        }, 5000);
    });
});
</script>
@endsection
