@extends('layouts.bank-sampah')

@section('page-title', 'Buat Laporan Bulanan')
@section('breadcrumb', 'Laporan / Buat Baru')

@section('styles')
<link href="{{ asset('css/bank/form.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="container">

    <form method="POST" action="{{ route('bank-sampah.laporan.store') }}" class="laporan-form" id="laporanForm">
        @csrf
 
        <!-- Form 1 -->
        <div class="form-section">
            <h3><i class="fas fa-layer-group"></i> Form 1 - Data Sampah</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="periode" class="form-label">
                        1) Periode Laporan <span class="required">*</span>
                    </label>
                    <input type="month"
                           id="periode"
                           name="periode"
                           class="form-control"
                           value="{{ old('periode', date('Y-m')) }}"
                           required
                           min="2023-01"
                           max="{{ date('Y-m') }}"
                           onchange="checkExistingReport()">
                    <div class="form-help">Pilih bulan dan tahun laporan</div>
                    <div id="periodeWarning" class="form-warning" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i> Anda sudah memiliki laporan untuk periode ini
                    </div>
                    @error('periode')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_sampah_masuk" class="form-label">
                        2) Sampah Masuk <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text"
                               id="jumlah_sampah_masuk"
                               name="jumlah_sampah_masuk"
                               class="form-control"
                               value="{{ old('jumlah_sampah_masuk') }}"
                               required
                               placeholder="Contoh: 9"
                               inputmode="decimal"
                               onfocus="clearZeroValue(this)"
                               oninput="normalizeNumber(this); validateSampah()">
                        <span class="input-unit">kg</span>
                    </div>
                    <div class="form-help">Gunakan angka tanpa koma</div>
                    @error('jumlah_sampah_masuk')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_sampah_terkelola" class="form-label">
                        3) Sampah Terkelola <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <input type="text"
                               id="jumlah_sampah_terkelola"
                               name="jumlah_sampah_terkelola"
                               class="form-control"
                               value="{{ old('jumlah_sampah_terkelola') }}"
                               required
                               placeholder="Contoh: 9"
                               inputmode="decimal"
                               onfocus="clearZeroValue(this)"
                               oninput="normalizeNumber(this); validateSampah()">
                        <span class="input-unit">kg</span>
                    </div>
                    <div class="form-help">Tidak boleh lebih dari sampah masuk</div>
                    <div id="terkelolaError" class="error-message" style="display: none;">
                        <i class="fas fa-exclamation-circle"></i> Tidak boleh melebihi sampah masuk
                    </div>
                    @error('jumlah_sampah_terkelola')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_nasabah" class="form-label">
                        Jumlah Nasabah <span class="required">*</span>
                    </label>
                    <input type="number"
                           id="jumlah_nasabah"
                           name="jumlah_nasabah"
                           class="form-control"
                           min="0"
                           value="{{ old('jumlah_nasabah') }}"
                           required
                           onfocus="clearZeroValue(this)"
                           placeholder="Contoh: 45">
                    <div class="form-help">Jumlah nasabah aktif bulan ini</div>
                    @error('jumlah_nasabah')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form 2 -->
        <div class="form-section">
            <h3><i class="fas fa-recycle"></i> Form 2 - Rincian Sampah & Validasi Total</h3>

            <div class="info-box">
                <h4><i class="fas fa-calculator"></i> Validasi Total</h4>
                <p>
                    <strong>Total harus sama dengan Sampah Terkelola:</strong> 
                    <span id="totalRincianDisplay" class="total-value"></span> kg
                    <span id="totalError" class="total-error" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i> Tidak sesuai!
                    </span>
                </p>
                <div class="progress-container">
                    <div class="progress-bar" id="progressBar"></div>
                    <div class="progress-text" id="progressText">0%</div>
                </div>
            </div>

            <div class="rincian-grid">
                @foreach($jenisSampah as $key => $label)
                <div class="rincian-item">
                    <label for="rincian_{{ $key }}" class="rincian-label">
                        <i class="fas fa-trash-alt"></i> {{ $label }}
                    </label>
                    <div class="rincian-input-group">
                        <span class="rincian-icon"><i class="fas fa-weight-hanging"></i></span>
                        <input type="text"
                               id="rincian_{{ $key }}"
                               name="rincian_sampah[{{ $key }}]"
                               class="rincian-input"
                               value="{{ old("rincian_sampah.$key", '') }}"
                               placeholder="0"
                               inputmode="decimal"
                               onfocus="clearZeroValue(this)"
                               oninput="normalizeNumber(this); calculateTotal()">
                        <span class="input-unit">kg</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn-submit" onclick="calculateTotal()">
                <i class="fas fa-calculator"></i> Hitung Ulang
            </button>
            <button type="button" class="btn-submit" onclick="autoFill()">
                <i class="fas fa-magic"></i> Isi Otomatis
            </button>
            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-save"></i> Simpan Laporan
            </button>
            <a href="{{ route('bank-sampah.laporan.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
// Global variables
let totalSampahTerkelola = 0;
let totalRincian = 0;

// Only dot decimal and numeric, without comma
function normalizeNumber(input) {
    let value = input.value;
    // Remove commas and non-numeric characters (except dot)
    value = value.replace(/,/g, '');
    value = value.replace(/[^0-9.]/g, '');
    // Keep only one decimal dot
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }
    input.value = value;

    if (input.id === 'jumlah_sampah_masuk' || input.id === 'jumlah_sampah_terkelola') {
        validateSampah();
    } else if (input.classList.contains('rincian-input')) {
        calculateTotal();
    }
}

function clearZeroValue(input) {
    if (input.value === '0' || input.value === '0.00' || input.value === '0.000') {
        input.value = '';
    }
}

// Validate sampah masuk vs terkelola
function validateSampah() {
    const masuk = parseFloat(document.getElementById('jumlah_sampah_masuk').value) || 0;
    const terkelola = parseFloat(document.getElementById('jumlah_sampah_terkelola').value) || 0;
    const errorDiv = document.getElementById('terkelolaError');
    
    if (terkelola > masuk) {
        errorDiv.style.display = 'block';
        document.getElementById('jumlah_sampah_terkelola').classList.add('error');
    } else {
        errorDiv.style.display = 'none';
        document.getElementById('jumlah_sampah_terkelola').classList.remove('error');
    }
    
    totalSampahTerkelola = terkelola;
    calculateTotal();
}

// Calculate total of all rincian
function calculateTotal() {
    totalRincian = 0;
    const inputs = document.querySelectorAll('.rincian-input');
    let hasFilledValue = false;
    
    inputs.forEach(input => {
        if (input.value !== '') {
            hasFilledValue = true;
        }
        const value = parseFloat(input.value) || 0;
        totalRincian += value;
    });
    
    // Update display (kosong dulu jika belum ada input)
    document.getElementById('totalRincianDisplay').textContent = hasFilledValue ? totalRincian.toFixed(2) : '';
    
    // Update progress
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const totalError = document.getElementById('totalError');
    
    if (totalSampahTerkelola > 0) {
        const percentage = Math.min((totalRincian / totalSampahTerkelola) * 100, 100);
        progressBar.style.width = percentage + '%';
        progressText.textContent = percentage.toFixed(1) + '%';
        
        // Color based on match
        if (Math.abs(totalRincian - totalSampahTerkelola) <= 0.001) {
            progressBar.style.backgroundColor = '#2f7d5a';
            progressText.style.color = '#1f5f46';
            totalError.style.display = 'none';
        } else if (totalRincian < totalSampahTerkelola) {
            progressBar.style.backgroundColor = '#2f7d5a';
            progressText.style.color = '#1f5f46';
            totalError.style.display = 'block';
            totalError.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Kurang ' +
                (totalSampahTerkelola - totalRincian).toFixed(2) + ' kg';
        } else {
            progressBar.style.backgroundColor = '#2f7d5a';
            progressText.style.color = '#1f5f46';
            totalError.style.display = 'block';
            totalError.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Perbaiki kesalahan berikut: Lebih ' +
                (totalRincian - totalSampahTerkelola).toFixed(2) + ' kg';
        }
    }
    
    validateForm();
}

// Auto-fill rincian based on total
function autoFill() {
    const total = totalSampahTerkelola;
    if (total <= 0) {
        alert('Masukkan total sampah terkelola terlebih dahulu');
        return;
    }
    
    // Default distribution percentages (bisa disesuaikan)
    const distribution = {
        'plastik_keras': 0.25,      // 25%
        'plastik_fleksibel': 0.20,  // 20%
        'kertas_karton': 0.15,      // 15%
        'logam': 0.10,              // 10%
        'kaca': 0.08,               // 8%
        'karet_kulit': 0.07,        // 7%
        'kain_tekstil': 0.05,       // 5%
        'lainnya': 0.10             // 10%
    };
    
    // Fill each input
    for (const [key, percentage] of Object.entries(distribution)) {
        const input = document.getElementById('rincian_' + key);
        if (input) {
            input.value = (total * percentage).toFixed(2);
            normalizeNumber(input);
        }
    }
    
    calculateTotal();
}

// Check if report already exists for selected period
function checkExistingReport() {
    const periode = document.getElementById('periode').value;
    if (!periode) return;
    
    // AJAX request to check existing report
    fetch(`/bank-sampah/laporan/check-existing?periode=${periode}`)
        .then(response => response.json())
        .then(data => {
            const warning = document.getElementById('periodeWarning');
            if (data.exists) {
                warning.style.display = 'block';
                warning.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i> 
                    Anda sudah memiliki laporan untuk periode ini. 
                    <a href="${data.edit_url}" class="warning-link">Edit laporan</a>
                `;
            } else {
                warning.style.display = 'none';
            }
        });
}

// Validate entire form before submit
function validateForm() {
    const errors = [];
    const validationSection = document.getElementById('validationSection');
    const errorsList = document.getElementById('validationErrors');
    const submitBtn = document.getElementById('submitBtn');
    
    // Clear previous errors
    errorsList.innerHTML = '';
    
    // Validation 1: Sampah terkelola <= Sampah masuk
    const masuk = parseFloat(document.getElementById('jumlah_sampah_masuk').value) || 0;
    const terkelola = parseFloat(document.getElementById('jumlah_sampah_terkelola').value) || 0;
    
    if (terkelola > masuk) {
        errors.push('Jumlah sampah terkelola tidak boleh melebihi sampah masuk');
    }
    
    // Validation 2: Total rincian must equal sampah terkelola
    if (Math.abs(totalRincian - terkelola) > 0.01) {
        errors.push(`Total rincian (${totalRincian.toFixed(2)} kg) harus sama dengan sampah terkelola (${terkelola.toFixed(2)} kg)`);
    }
    
    // Show/hide validation
    if (errors.length > 0) {
        errors.forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            errorsList.appendChild(li);
        });
        validationSection.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled');
    } else {
        validationSection.style.display = 'none';
        submitBtn.disabled = false;
        submitBtn.classList.remove('disabled');
    }
    
    return errors.length === 0;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    validateSampah();
    calculateTotal();
    
    // Add form validation on submit
    const form = document.getElementById('laporanForm');
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
        
        // Show loading
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });
    
    // Check existing report when page loads
    checkExistingReport();
});
</script>
@endsection