@extends('layouts.bank-sampah')

@section('page-title', 'Isi Data Operasional')
@section('breadcrumb', 'Data Operasional / Isi Data Baru')

@section('styles')
<link href="{{ asset('css/bank/operasional.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="operasional-create-container">
    <div class="edit-header">
        <h2><i class="fas fa-database"></i> Isi Data Operasional</h2>
        <p>Lengkapi data operasional {{ $bankSampah->nama_bank_sampah }}</p>
    </div>

    <form method="POST" action="{{ route('bank-sampah.operasional.store') }}" class="operasional-form" id="operasionalForm">
        @csrf
        <div class="form-section">
            <h3><i class="fas fa-users"></i> Tenaga Kerja & Nasabah</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="tenaga_kerja_laki">Tenaga Kerja Laki-laki <span class="required">*</span></label>
                    <input type="number" id="tenaga_kerja_laki" name="tenaga_kerja_laki" value="{{ old('tenaga_kerja_laki', 0) }}" min="0" required class="form-control">
                    @error('tenaga_kerja_laki') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="tenaga_kerja_perempuan">Tenaga Kerja Perempuan <span class="required">*</span></label>
                    <input type="number" id="tenaga_kerja_perempuan" name="tenaga_kerja_perempuan" value="{{ old('tenaga_kerja_perempuan', 0) }}" min="0" required class="form-control">
                    @error('tenaga_kerja_perempuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="nasabah_laki">Nasabah Laki-laki <span class="required">*</span></label>
                    <input type="number" id="nasabah_laki" name="nasabah_laki" value="{{ old('nasabah_laki', 0) }}" min="0" required class="form-control">
                    @error('nasabah_laki') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="nasabah_perempuan">Nasabah Perempuan <span class="required">*</span></label>
                    <input type="number" id="nasabah_perempuan" name="nasabah_perempuan" value="{{ old('nasabah_perempuan', 0) }}" min="0" required class="form-control">
                    @error('nasabah_perempuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-money-bill-wave"></i> Omset & Penjualan</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="omset">Omset Bulanan (Rp) <span class="required">*</span></label>
                    <input type="number" id="omset" name="omset" value="{{ old('omset', 0) }}" min="0" step="1000" required class="form-control">
                    @error('omset') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="tempat_penjualan">Tempat Penjualan <span class="required">*</span></label>
                    <select id="tempat_penjualan" name="tempat_penjualan" class="form-control" required onchange="toggleLainnya(this, 'tempat_penjualan_lainnya')">
                        <option value="">-- Pilih Tempat Penjualan --</option>
                        <option value="bank_sampah_induk" {{ old('tempat_penjualan') == 'bank_sampah_induk' ? 'selected' : '' }}>Bank Sampah Induk</option>
                        <option value="pengepul" {{ old('tempat_penjualan') == 'pengepul' ? 'selected' : '' }}>Pengepul</option>
                        <option value="lainnya" {{ old('tempat_penjualan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('tempat_penjualan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group" id="tempat_penjualan_lainnya_container" style="display:none;">
                    <label for="tempat_penjualan_lainnya">Tempat Penjualan Lainnya</label>
                    <input type="text" id="tempat_penjualan_lainnya" name="tempat_penjualan_lainnya" value="{{ old('tempat_penjualan_lainnya') }}" class="form-control" placeholder="Contoh: Industri daur ulang">
                    @error('tempat_penjualan_lainnya') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-recycle"></i> Kegiatan & Produk</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="kegiatan_pengelolaan">Kegiatan Pengelolaan Sampah <span class="required">*</span></label>
                    <textarea id="kegiatan_pengelolaan" name="kegiatan_pengelolaan" rows="4" class="form-control" required>{{ old('kegiatan_pengelolaan') }}</textarea>
                    @error('kegiatan_pengelolaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="produk_daur_ulang">Produk Daur Ulang/Kerajinan</label>
                    <textarea id="produk_daur_ulang" name="produk_daur_ulang" rows="4" class="form-control">{{ old('produk_daur_ulang') }}</textarea>
                    @error('produk_daur_ulang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-tools"></i> Sarana & Prasarana</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="buku_tabungan">Buku Tabungan</label>
                    <select id="buku_tabungan" name="buku_tabungan" class="form-control">
                        <option value="ada" {{ old('buku_tabungan') == 'ada' ? 'selected' : '' }}>Ada</option>
                        <option value="tidak_ada" {{ old('buku_tabungan', 'tidak_ada') == 'tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                    </select>
                    @error('buku_tabungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="sistem_pencatatan">Sistem Pencatatan</label>
                    <select id="sistem_pencatatan" name="sistem_pencatatan" class="form-control">
                        <option value="Manual" {{ old('sistem_pencatatan', 'Manual') == 'Manual' ? 'selected' : '' }}>Manual (Buku)</option>
                        <option value="Komputerisasi" {{ old('sistem_pencatatan') == 'Komputerisasi' ? 'selected' : '' }}>Komputerisasi</option>
                        <option value="Aplikasi" {{ old('sistem_pencatatan') == 'Aplikasi' ? 'selected' : '' }}>Aplikasi</option>
                    </select>
                    @error('sistem_pencatatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="timbangan">Timbangan</label>
                    <select id="timbangan" name="timbangan" class="form-control">
                        <option value="tidak_ada" {{ old('timbangan', 'tidak_ada') == 'tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                        <option value="timbangan_gantung" {{ old('timbangan') == 'timbangan_gantung' ? 'selected' : '' }}>Timbangan Gantung</option>
                        <option value="timbangan_digital" {{ old('timbangan') == 'timbangan_digital' ? 'selected' : '' }}>Timbangan Digital</option>
                        <option value="timbangan_posyandu" {{ old('timbangan') == 'timbangan_posyandu' ? 'selected' : '' }}>Timbangan Posyandu</option>
                        <option value="timbangan_duduk" {{ old('timbangan') == 'timbangan_duduk' ? 'selected' : '' }}>Timbangan Duduk</option>
                    </select>
                    @error('timbangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label for="alat_pengangkut">Alat Pengangkut Sampah</label>
                    <select id="alat_pengangkut" name="alat_pengangkut" class="form-control" onchange="toggleLainnya(this, 'alat_pengangkut_lainnya')">
                        <option value="Tidak_ada" {{ old('alat_pengangkut', 'Tidak_ada') == 'Tidak_ada' ? 'selected' : '' }}>Tidak Ada</option>
                        <option value="Becak" {{ old('alat_pengangkut') == 'Becak' ? 'selected' : '' }}>Becak</option>
                        <option value="Gerobak" {{ old('alat_pengangkut') == 'Gerobak' ? 'selected' : '' }}>Gerobak</option>
                        <option value="Tossa" {{ old('alat_pengangkut') == 'Tossa' ? 'selected' : '' }}>Tossa</option>
                        <option value="Lainnya" {{ old('alat_pengangkut') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('alat_pengangkut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group" id="alat_pengangkut_lainnya_container" style="display:none;">
                    <label for="alat_pengangkut_lainnya">Alat Pengangkut Lainnya</label>
                    <input type="text" id="alat_pengangkut_lainnya" name="alat_pengangkut_lainnya" value="{{ old('alat_pengangkut_lainnya') }}" class="form-control" placeholder="Contoh: Motor roda tiga">
                    @error('alat_pengangkut_lainnya') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Data Operasional
            </button>
            <a href="{{ route('bank-sampah.operasional.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
function toggleLainnya(selectElement, targetId) {
    const container = document.getElementById(`${targetId}_container`);
    const input = document.getElementById(targetId);
    
    if (selectElement.value === 'lainnya' || selectElement.value === 'Lainnya') {
        container.style.display = 'block';
        if (input) input.required = true;
    } else {
        container.style.display = 'none';
        if (input) {
            input.required = false;
            input.value = '';
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check initial values for "lainnya" fields
    const tempatSelect = document.getElementById('tempat_penjualan');
    const alatSelect = document.getElementById('alat_pengangkut');
    
    if (tempatSelect) toggleLainnya(tempatSelect, 'tempat_penjualan_lainnya');
    if (alatSelect) toggleLainnya(alatSelect, 'alat_pengangkut_lainnya');
});
</script>
@endsection