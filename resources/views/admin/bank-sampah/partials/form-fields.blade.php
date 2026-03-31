<div class="row g-3">
    <div class="col-md-6">
        <label for="{{ $prefix }}nama_bank_sampah" class="form-label">Nama Bank Sampah *</label>
        <input type="text" id="{{ $prefix }}nama_bank_sampah" name="nama_bank_sampah" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="{{ $prefix }}nomor_sk" class="form-label">Nomor SK</label>
        <input type="text" id="{{ $prefix }}nomor_sk" name="nomor_sk" class="form-control">
    </div>
    <div class="col-md-6">
        <label for="{{ $prefix }}status_terbentuk" class="form-label">Status Terbentuk *</label>
        <select id="{{ $prefix }}status_terbentuk" name="status_terbentuk" class="form-select" required>
            <option value="">Pilih Status</option>
            <option value="Sudah">Sudah</option>
            <option value="Belum">Belum</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="{{ $prefix }}rw" class="form-label">RW *</label>
        <input type="text" id="{{ $prefix }}rw" name="rw" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="{{ $prefix }}kecamatan_id" class="form-label">Kecamatan *</label>
        <select id="{{ $prefix }}kecamatan_id" name="kecamatan_id" class="form-select" required>
            <option value="">Pilih Kecamatan</option>
            @foreach($kecamatans as $kecamatan)
                <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="{{ $prefix }}kelurahan_id" class="form-label">Kelurahan *</label>
        <select id="{{ $prefix }}kelurahan_id" name="kelurahan_id" class="form-select" required>
            <option value="">Pilih Kelurahan</option>
            @foreach($kelurahans as $kelurahan)
                <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama_kelurahan }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="{{ $prefix }}nama_direktur" class="form-label">Nama Direktur *</label>
        <input type="text" id="{{ $prefix }}nama_direktur" name="nama_direktur" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label for="{{ $prefix }}nomor_hp" class="form-label">Nomor HP *</label>
        <input type="text" id="{{ $prefix }}nomor_hp" name="nomor_hp" class="form-control" required>
    </div>
    <div class="col-12">
        <label for="{{ $prefix }}keterangan" class="form-label">Keterangan</label>
        <textarea id="{{ $prefix }}keterangan" name="keterangan" rows="3" class="form-control"></textarea>
    </div>
</div>
