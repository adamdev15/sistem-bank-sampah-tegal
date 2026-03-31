@extends('layouts.admin')

@section('page-title', 'Export Data')
@section('breadcrumb', 'Export Data')

@section('styles')
<link href="{{ asset('css/export.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="data-container">
    <!-- Main Form -->
    <div class="export-form-wrapper">
        <div class="form-card">
            <div class="card-header text-white" style="background: linear-gradient(180deg, #1f5f46 0%, #198754 100%);">
                <div class="header-content">
                    <h1><i class="fas fa-file-export"></i> Export Data</h1>
                    <p class="subtitle">Sistem BASMAN - Bank Sampah Management System</p>
                    <p class="description">Pilih jenis data dan format export untuk mendownload data dalam format Excel atau PDF</p>
                </div>
                <div class="export-steps">
                    <button type="button" class="step-item active" data-step-nav="1"><i class="fas fa-database"></i> 1. Pilih Data</button>
                    <button type="button" class="step-item" data-step-nav="2"><i class="fas fa-file-export"></i> 2. Pilih Format</button>
                    <button type="button" class="step-item" data-step-nav="3"><i class="fas fa-filter"></i> 3. Atur Filter</button>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.export.generate') }}" id="exportForm" class="export-form">
                @csrf

                <!-- Jenis Data -->
                <div class="form-section wizard-step active" data-step="1">
                    <div class="radio-group-grid">
                        <div class="radio-card active" onclick="selectRadio('master')">
                            <input type="radio" id="type_master" name="type" value="master" checked>
                            <div class="radio-content">
                                <div class="radio-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="radio-info">
                                    <h4>Data Master Bank Sampah</h4>
                                    <p>Data identitas bank sampah (Sheet 1)</p>
                                    <div class="feature-list">
                                        <div><i class="fas fa-check"></i> Nama bank sampah</div>
                                        <div><i class="fas fa-check"></i> Lokasi & kontak</div>
                                        <div><i class="fas fa-check"></i> Status terbentuk</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="radio-card" onclick="selectRadio('operasional')">
                            <input type="radio" id="type_operasional" name="type" value="operasional">
                            <div class="radio-content">
                                <div class="radio-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="radio-info">
                                    <h4>Data Operasional</h4>
                                    <p>Data kegiatan operasional bank sampah (Sheet 2)</p>
                                    <div class="feature-list">
                                        <div><i class="fas fa-check"></i> Tenaga kerja & nasabah</div>
                                        <div><i class="fas fa-check"></i> Omset & penjualan</div>
                                        <div><i class="fas fa-check"></i> Sarana & prasarana</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="radio-card" onclick="selectRadio('laporan')">
                            <input type="radio" id="type_laporan" name="type" value="laporan">
                            <div class="d-flex align-items-center gap-2">
                                <div class="radio-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="radio-info">
                                    <h4>Laporan Bulanan</h4>
                                    <p>Data laporan sampah bulanan (Sheet 3)</p>
                                    <div class="feature-list">
                                        <div><i class="fas fa-check"></i> Sampah masuk & terkelola</div>
                                        <div><i class="fas fa-check"></i> Rincian jenis sampah</div>
                                        <div><i class="fas fa-check"></i> Jumlah nasabah</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Format Export -->
                <div class="form-section wizard-step" data-step="2">
                    <div class="format-options">
                        <div class="format-option active" onclick="selectFormat('excel')">
                            <input type="radio" id="format_excel" name="format" value="excel" checked>
                            <div class="format-content">
                                <div class="format-icon excel">
                                    <i class="fas fa-file-excel"></i>
                                </div>
                                <div class="format-info">
                                    <h4 class="format-title-row">Excel (.xlsx) <span class="format-badge">Rekomendasi</span></h4>
                                    <p>Microsoft Excel - Format spreadsheet</p>
                                </div>
                            </div>
                        </div>

                        <div class="format-option" onclick="selectFormat('pdf')">
                            <input type="radio" id="format_pdf" name="format" value="pdf">
                            <div class="format-content">
                                <div class="format-icon pdf">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="format-info">
                                    <h4 class="format-title-row">PDF (.pdf) <span class="format-badge">Cetak</span></h4>
                                    <p>Portable Document Format</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Data -->
                <div class="form-section wizard-step" data-step="3">
                    
                    <div class="filter-grid">
                        <!-- Filter Kecamatan -->
                        <div class="filter-group">
                            <label for="kecamatan_id">
                                <i class="fas fa-map-marker-alt"></i> Pilih Kecamatan
                            </label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-select">
                                <option value="">Semua Kecamatan</option>
                                @foreach($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}">
                                        {{ $kecamatan->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Tahun (hanya untuk laporan) -->
                        <div class="filter-group" id="year-filter" style="display: none;">
                            <label for="tahun">
                                <i class="fas fa-calendar"></i> Tahun Laporan
                            </label>
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Tanggal (hanya untuk laporan) -->
                        <div class="filter-group date-group" id="date-start-filter" style="display: none;">
                            <label for="start_date">
                                <i class="fas fa-calendar-alt"></i> Tanggal Mulai
                            </label>
                            <input type="month" name="start_date" id="start_date" 
                                   class="form-control"
                                   min="2023-01" max="{{ date('Y-m') }}">
                        </div>
                        
                        <div class="filter-group date-group" id="date-end-filter" style="display: none;">
                            <label for="end_date">
                                <i class="fas fa-calendar-alt"></i> Tanggal Akhir
                            </label>
                            <input type="month" name="end_date" id="end_date" 
                                   class="form-control"
                                   min="2023-01" max="{{ date('Y-m') }}">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="action-info">
                        <i class="fas fa-info-circle"></i>
                        <p>Pastikan semua pengaturan sudah sesuai sebelum mengeksekusi export</p>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" id="cancelButton">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="button" class="btn btn-outline-secondary" id="prevStepButton" style="display:none;">
                            <i class="fas fa-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="button" class="btn btn-primary" id="nextStepButton">
                            Selanjutnya <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="submit" class="btn btn-primary" id="exportButton" style="display:none;">
                            <i class="fas fa-download"></i> Generate Export
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

</div>

<script>
// ============================================
// SIMPLE JAVASCRIPT - TANPA LOADING COMPLEX
// ============================================

// Fungsi untuk select radio card
function selectRadio(type) {
    // Uncheck semua radio
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Check yang dipilih
    document.getElementById(`type_${type}`).checked = true;
    
    // Update active state
    document.querySelectorAll('.radio-card').forEach(card => {
        card.classList.remove('active');
    });
    document.querySelector(`[onclick="selectRadio('${type}')"]`).classList.add('active');
    
    // Toggle filters
    toggleFilters();
}

// Fungsi untuk select format
function selectFormat(format) {
    // Uncheck semua
    document.querySelectorAll('input[name="format"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Check yang dipilih
    document.getElementById(`format_${format}`).checked = true;
    
    // Update active state
    document.querySelectorAll('.format-option').forEach(option => {
        option.classList.remove('active');
    });
    document.querySelector(`[onclick="selectFormat('${format}')"]`).classList.add('active');
}

// Toggle filters berdasarkan jenis data
function toggleFilters() {
    const type = document.querySelector('input[name="type"]:checked').value;
    
    // Reset semua filter
    document.getElementById('year-filter').style.display = 'none';
    document.querySelectorAll('.date-group').forEach(el => {
        el.style.display = 'none';
    });
    
    // Tampilkan filter untuk laporan
    if (type === 'laporan') {
        document.getElementById('year-filter').style.display = 'block';
        document.querySelectorAll('.date-group').forEach(el => {
            el.style.display = 'block';
        });
    }
}

// Date range validation
function setupDateValidation() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    if (startDate && endDate) {
        startDate.addEventListener('change', function() {
            if (this.value) {
                endDate.min = this.value;
                if (endDate.value && endDate.value < this.value) {
                    endDate.value = this.value;
                }
            }
        });
        
        endDate.addEventListener('change', function() {
            if (this.value) {
                startDate.max = this.value;
                if (startDate.value && startDate.value > this.value) {
                    startDate.value = this.value;
                }
            }
        });
    }
}

// Simple form submission - hanya disable button sebentar
function setupFormSubmission() {
    const form = document.getElementById('exportForm');
    const exportButton = document.getElementById('exportButton');
    let isSubmitting = false;
    
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }
        
        // Validasi sederhana untuk date range
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const type = document.querySelector('input[name="type"]:checked').value;
        
        if (type === 'laporan' && startDate && endDate) {
            if (startDate.value && endDate.value && startDate.value > endDate.value) {
                e.preventDefault();
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                return;
            }
        }
        
        // Disable button untuk mencegah double click
        isSubmitting = true;
        exportButton.disabled = true;
        exportButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Auto enable setelah 5 detik (jika masih stuck)
        setTimeout(() => {
            isSubmitting = false;
            exportButton.disabled = false;
            exportButton.innerHTML = '<i class="fas fa-download"></i> Generate Export';
        }, 5000);
    });
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;

    function renderWizardStep(step) {
        currentStep = step;

        document.querySelectorAll('.wizard-step').forEach(section => {
            section.classList.toggle('active', Number(section.dataset.step) === step);
        });

        document.querySelectorAll('[data-step-nav]').forEach(item => {
            item.classList.toggle('active', Number(item.dataset.stepNav) === step);
        });

        const prevBtn = document.getElementById('prevStepButton');
        const nextBtn = document.getElementById('nextStepButton');
        const submitBtn = document.getElementById('exportButton');

        prevBtn.style.display = step > 1 ? 'inline-flex' : 'none';
        nextBtn.style.display = step < 3 ? 'inline-flex' : 'none';
        submitBtn.style.display = step === 3 ? 'inline-flex' : 'none';
    }

    document.getElementById('nextStepButton').addEventListener('click', function () {
        if (currentStep < 3) {
            renderWizardStep(currentStep + 1);
        }
    });

    document.getElementById('prevStepButton').addEventListener('click', function () {
        if (currentStep > 1) {
            renderWizardStep(currentStep - 1);
        }
    });

    document.querySelectorAll('[data-step-nav]').forEach(item => {
        item.addEventListener('click', function () {
            const target = Number(this.dataset.stepNav);
            renderWizardStep(target);
        });
    });

    // Set initial state
    toggleFilters();
    renderWizardStep(1);
    
    // Setup form submission
    setupFormSubmission();
    
    // Setup date validation
    setupDateValidation();
    
    // Add some visual feedback
    document.querySelectorAll('.radio-card, .format-option').forEach(element => {
        element.addEventListener('click', function() {
            // Small visual feedback
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
});

// Simple helper untuk format angka (jika diperlukan nanti)
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>

<style>
/* Tambahan kecil untuk button disabled state */
.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Feature list styling */
.feature-list {
    margin-top: 10px;
}

.feature-list div {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
    font-size: 12px;
    color: #5d6d7e;
}

.feature-list div i {
    color: #2ecc71;
    font-size: 10px;
}

/* Smooth transitions */
.radio-card,
.format-option,
.btn,
.filter-group select,
.filter-group input {
    transition: all 0.2s ease;
}

/* Focus styles untuk accessibility */
.form-select:focus,
.form-control:focus {
    outline: 2px solid #3498db;
    outline-offset: 2px;
}

/* Responsive tweaks untuk mobile */
@media (max-width: 576px) {
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>
@endsection