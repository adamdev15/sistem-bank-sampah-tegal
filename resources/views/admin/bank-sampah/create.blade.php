@extends('layouts.admin')

@section('page-title', 'Tambah Bank Sampah Baru')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.bank-sampah.index') }}">Bank Sampah</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
    </ol>
</nav>
@endsection

@section('styles')
<link href="{{ asset('css/form.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="create-bank-container">
    <div class="form-header">
        <h2>Tambah Bank Sampah Baru</h2>
        <p>Form untuk menambahkan data master bank sampah</p>
    </div>

    <form method="POST" action="{{ route('admin.bank-sampah.store') }}" class="bank-create-form">
        @csrf

        <div class="form-section">
            <h3>Data Identitas</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_bank_sampah">Nama Bank Sampah *</label>
                    <input type="text" id="nama_bank_sampah" name="nama_bank_sampah" 
                           value="{{ old('nama_bank_sampah') }}" required>
                    @error('nama_bank_sampah')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nomor_sk">Nomor SK</label>
                    <input type="text" id="nomor_sk" name="nomor_sk" 
                           value="{{ old('nomor_sk') }}">
                    @error('nomor_sk')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status_terbentuk">Status Terbentuk *</label>
                    <select id="status_terbentuk" name="status_terbentuk" required>
                        <option value="">Pilih Status</option>
                        <option value="Sudah" {{ old('status_terbentuk') == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                        <option value="Belum" {{ old('status_terbentuk') == 'Belum' ? 'selected' : '' }}>Belum</option>
                    </select>
                    @error('status_terbentuk')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="2">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Lokasi</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="kecamatan_id">Kecamatan *</label>
                    <select id="kecamatan_id" name="kecamatan_id" required>
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" 
                                {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                {{ $kecamatan->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('kecamatan_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kelurahan_id">Kelurahan *</label>
                    <select id="kelurahan_id" name="kelurahan_id" required>
                        <option value="">Pilih Kelurahan</option>
                        @foreach($kelurahans as $kelurahan)
                            <option value="{{ $kelurahan->id }}" 
                                {{ old('kelurahan_id') == $kelurahan->id ? 'selected' : '' }}>
                                {{ $kelurahan->nama_kelurahan }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelurahan_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rw">RW *</label>
                    <input type="text" id="rw" name="rw" 
                           value="{{ old('rw') }}" required>
                    @error('rw')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Kontak</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="nama_direktur">Nama Direktur *</label>
                    <input type="text" id="nama_direktur" name="nama_direktur" 
                           value="{{ old('nama_direktur') }}" required>
                    @error('nama_direktur')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nomor_hp">Nomor HP *</label>
                    <input type="text" id="nomor_hp" name="nomor_hp" 
                           value="{{ old('nomor_hp') }}" required>
                    @error('nomor_hp')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Simpan Data
            </button>
            <a href="{{ route('admin.bank-sampah.index') }}" class="btn-cancel">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kecamatanSelect = document.getElementById('kecamatan_id');
    const kelurahanSelect = document.getElementById('kelurahan_id');
    
    kecamatanSelect.addEventListener('change', function() {
        const kecamatanId = this.value;
        
        if (kecamatanId) {
            fetch(`/api/kelurahan/${kecamatanId}`)
                .then(response => response.json())
                .then(data => {
                    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    
                    data.forEach(kelurahan => {
                        const option = document.createElement('option');
                        option.value = kelurahan.id;
                        option.textContent = kelurahan.nama_kelurahan;
                        kelurahanSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        } else {
            kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
        }
    });
    
    // Inisialisasi jika ada old value
    @if(old('kecamatan_id'))
        kecamatanSelect.value = "{{ old('kecamatan_id') }}";
        kecamatanSelect.dispatchEvent(new Event('change'));
        
        // Setelah kelurahan di-load, set value old
        setTimeout(() => {
            @if(old('kelurahan_id'))
                kelurahanSelect.value = "{{ old('kelurahan_id') }}";
            @endif
        }, 500);
    @endif
});
</script>
@endsection