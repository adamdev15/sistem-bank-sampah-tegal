@extends('layouts.bank-sampah')

@section('title', 'Edit Laporan')
@section('breadcrumb', 'Laporan / Edit')

@section('styles')
<link href="{{ asset('css/bank/form.css') }}" rel="stylesheet">
<link href="{{ asset('css/bank/laporan.css') }}" rel="stylesheet">
@endsection

@section('content-body')
@php
    $fmtKg = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, '.', ''), '0'), '.');
@endphp
<div class="data-container">
    <div class="form-header">
        <h2 class="text-white"><i class="fas fa-pen-to-square"></i> Edit Laporan Bulanan</h2>
        <p class="text-white">Periode: <strong>{{ $laporan->periode->translatedFormat('F Y') }}</strong></p>
    </div>

            @if($laporan->status == 'disetujui')
                <div class="reference-info">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Laporan yang sudah disetujui tidak dapat diubah.
                    <a href="{{ route('bank-sampah.laporan.show', $laporan) }}" class="btn btn-sm btn-outline-primary ms-2">
                        Lihat Detail
                    </a>
                </div>
            @elseif($laporan->status == 'perlu_perbaikan' && $laporan->catatan_verifikasi)
                <div class="reference-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan dari Admin:</strong> {{ $laporan->catatan_verifikasi }}
                </div>
            @endif
    <form method="POST" action="{{ route('bank-sampah.laporan.update', $laporan) }}" class="laporan-form" id="editLaporanForm">
        @csrf
        @method('PUT')

        <div class="form-section">
            <h3><i class="fas fa-layer-group"></i> Form 1 - Data 1, 2, 3</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">1) Periode Laporan</label>
                    <input type="text" class="form-control" value="{{ $laporan->periode->translatedFormat('F Y') }}" readonly>
                    <div class="form-help">Periode laporan tidak dapat diubah saat edit</div>
                </div>

                <div class="form-group">
                    <label for="jumlah_sampah_masuk" class="form-label">2) Sampah Masuk <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="text" id="jumlah_sampah_masuk" name="jumlah_sampah_masuk"
                            class="form-control @error('jumlah_sampah_masuk') is-invalid @enderror"
                            value="{{ old('jumlah_sampah_masuk') !== null ? old('jumlah_sampah_masuk') : $fmtKg($laporan->jumlah_sampah_masuk) }}"
                            required inputmode="decimal" onfocus="clearZeroValue(this)"
                            oninput="normalizeNumber(this); validateTerkelola()">
                        <span class="input-unit">kg</span>
                    </div>
                    @error('jumlah_sampah_masuk')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_sampah_terkelola" class="form-label">3) Sampah Terkelola <span class="required">*</span></label>
                    <div class="input-group">
                        <input type="text" id="jumlah_sampah_terkelola" name="jumlah_sampah_terkelola"
                            class="form-control @error('jumlah_sampah_terkelola') is-invalid @enderror"
                            value="{{ old('jumlah_sampah_terkelola') !== null ? old('jumlah_sampah_terkelola') : $fmtKg($laporan->jumlah_sampah_terkelola) }}"
                            required inputmode="decimal" onfocus="clearZeroValue(this)"
                            oninput="normalizeNumber(this); validateTerkelola()">
                        <span class="input-unit">kg</span>
                    </div>
                    <div id="terkelolaError" class="error-message" style="display: none;">
                        <i class="fas fa-exclamation-circle"></i> Tidak boleh melebihi sampah masuk
                    </div>
                    @error('jumlah_sampah_terkelola')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_nasabah" class="form-label">Jumlah Nasabah <span class="required">*</span></label>
                    <input type="number" id="jumlah_nasabah" name="jumlah_nasabah"
                        class="form-control @error('jumlah_nasabah') is-invalid @enderror"
                        value="{{ old('jumlah_nasabah', $laporan->jumlah_nasabah) }}"
                        min="0" required onfocus="clearZeroValue(this)">
                    @error('jumlah_nasabah')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

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
                        <input type="text" id="rincian_{{ $key }}"
                            name="rincian_sampah[{{ $key }}]"
                            class="rincian-input"
                            value="{{ old("rincian_sampah.$key") !== null ? old("rincian_sampah.$key") : (isset($rincian[$key]) && $rincian[$key] !== '' && $rincian[$key] !== null ? $fmtKg($rincian[$key]) : '') }}"
                            inputmode="decimal" placeholder="0"
                            onfocus="clearZeroValue(this)"
                            oninput="normalizeNumber(this); calculateTotal()">
                        <span class="input-unit">kg</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="validation-section" id="validationAlert" style="display: none;">
            <div class="validation-header">
                <i class="fas fa-exclamation-triangle"></i> Perbaiki kesalahan berikut:
            </div>
            <ul class="validation-errors" id="validationErrors"></ul>
        </div>

        <div class="form-actions">
            <button type="button" class="btn-cancel" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
            <button type="button" class="btn-calculate" onclick="calculateTotal()">
                <i class="fas fa-calculator"></i> Hitung Ulang
            </button>
            @if($laporan->status != 'disetujui')
            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
            @endif
        </div>
    </form>
</div>

<script>
let sampahMasuk = {{ (float) $laporan->jumlah_sampah_masuk }};
let sampahTerkelola = {{ (float) $laporan->jumlah_sampah_terkelola }};
let totalRincian = 0;

function normalizeNumber(input) {
    let value = input.value.replace(/,/g, '');
    value = value.replace(/[^0-9.]/g, '');
    const parts = value.split('.');
    if (parts.length > 2) value = parts[0] + '.' + parts.slice(1).join('');
    input.value = value;
}

function clearZeroValue(input) {
    if (input.value === '0' || input.value === '0.00' || input.value === '0.000') input.value = '';
}

function validateTerkelola() {
    sampahMasuk = parseFloat(document.getElementById('jumlah_sampah_masuk').value) || 0;
    sampahTerkelola = parseFloat(document.getElementById('jumlah_sampah_terkelola').value) || 0;
    
    const terkelolaError = document.getElementById('terkelolaError');
    if (sampahTerkelola > sampahMasuk) {
        terkelolaError.style.display = 'block';
    } else {
        terkelolaError.style.display = 'none';
    }
    
    validateForm();
}

function calculateTotal() {
    totalRincian = 0;
    const inputs = document.querySelectorAll('.rincian-input');
    let hasFilledValue = false;
    
    inputs.forEach(input => {
        if (input.value !== '') hasFilledValue = true;
        totalRincian += parseFloat(input.value) || 0;
    });
    
    document.getElementById('totalRincianDisplay').textContent = hasFilledValue ? totalRincian.toFixed(2) : '';
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const totalError = document.getElementById('totalError');
    if (sampahTerkelola > 0) {
        const percentage = Math.min((totalRincian / sampahTerkelola) * 100, 100);
        progressBar.style.width = percentage + '%';
        progressText.textContent = percentage.toFixed(1) + '%';
        progressBar.style.backgroundColor = '#2f7d5a';
        progressText.style.color = '#1f5f46';
        if (Math.abs(totalRincian - sampahTerkelola) <= 0.01) {
            totalError.style.display = 'none';
        } else if (totalRincian < sampahTerkelola) {
            totalError.style.display = 'block';
            totalError.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Kurang ' + (sampahTerkelola - totalRincian).toFixed(2) + ' kg';
        } else {
            totalError.style.display = 'block';
            totalError.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Lebih ' + (totalRincian - sampahTerkelola).toFixed(2) + ' kg';
        }
    }
    validateForm();
}

function validateForm() {
    const errors = [];
    const validationAlert = document.getElementById('validationAlert');
    const errorsList = document.getElementById('validationErrors');
    const submitBtn = document.getElementById('submitBtn');
    
    // Reset
    errorsList.innerHTML = '';
    validationAlert.style.display = 'none';
    
    // Validasi 1: Sampah terkelola ≤ Sampah masuk
    if (sampahTerkelola > sampahMasuk) {
        errors.push('Jumlah sampah terkelola tidak boleh melebihi sampah masuk');
    }
    
    // Validasi 2: Total rincian harus sama dengan sampah terkelola
    if (Math.abs(totalRincian - sampahTerkelola) > 0.01) {
        errors.push('Total rincian jenis sampah harus sama dengan jumlah sampah terkelola');
        document.getElementById('totalError').style.display = 'inline';
    } else {
        document.getElementById('totalError').style.display = 'none';
    }
    
    // Tampilkan error jika ada
    if (errors.length > 0) {
        errors.forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            errorsList.appendChild(li);
        });
        validationAlert.style.display = 'block';
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('disabled');
        }
    } else {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('disabled');
        }
    }
    
    return errors.length === 0;
}

// Form submission validation
document.getElementById('editLaporanForm')?.addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault();
        alert('Mohon perbaiki error sebelum menyimpan.');
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    validateTerkelola();
    calculateTotal();
    
    // Disable submit if status disetujui
    @if($laporan->status == 'disetujui')
        const form = document.getElementById('editLaporanForm');
        const inputs = form.querySelectorAll('input, select, textarea, button');
        inputs.forEach(input => {
            if (input.type !== 'hidden' && input.id !== 'submitBtn') {
                input.disabled = true;
            }
        });
        document.getElementById('submitBtn')?.style && (document.getElementById('submitBtn').style.display = 'none');
    @endif
});
</script>
@endsection