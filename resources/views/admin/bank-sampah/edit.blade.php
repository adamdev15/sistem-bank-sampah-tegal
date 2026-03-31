@extends('layouts.admin')

@section('page-title', 'Edit Bank Sampah')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.bank-sampah.index') }}">Bank Sampah</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.bank-sampah.show', $bankSampah) }}">Detail</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<link rel="stylesheet" href="{{ asset('css/bank-sampah.css') }}">
@endsection

@section('content-body')
<div class="bank-sampah-edit">

    {{-- HEADER --}}
    <div class="edit-header">
        <div>
            <h2>Edit Bank Sampah</h2>
            <p>{{ $bankSampah->nama_bank_sampah }}</p>
        </div>
    </div>

    <form method="POST"
          action="{{ route('admin.bank-sampah.update', $bankSampah) }}"
          class="edit-form-layout">
        @csrf
        @method('PUT')

        {{-- GRID UTAMA --}}
        <div class="edit-grid">

            {{-- KIRI --}}
            <div class="edit-column">

                <div class="edit-card">
                    <h3>Informasi Umum</h3>

                    <div class="form-group">
                        <label>Nama Bank Sampah *</label>
                        <input type="text" name="nama_bank_sampah"
                               value="{{ old('nama_bank_sampah', $bankSampah->nama_bank_sampah) }}" required>
                        @error('nama_bank_sampah')<span class="error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Nomor SK</label>
                        <input type="text" name="nomor_sk"
                               value="{{ old('nomor_sk', $bankSampah->nomor_sk) }}">
                    </div>

                    <div class="form-group">
                        <label>Status Terbentuk *</label>
                        <select name="status_terbentuk" required>
                            <option value="">Pilih Status</option>
                            <option value="Sudah" {{ old('status_terbentuk', $bankSampah->status_terbentuk) == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                            <option value="Belum" {{ old('status_terbentuk', $bankSampah->status_terbentuk) == 'Belum' ? 'selected' : '' }}>Belum</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="3">{{ old('keterangan', $bankSampah->keterangan) }}</textarea>
                    </div>
                </div>

            </div>

            {{-- KANAN --}}
            <div class="edit-column">

                <div class="edit-card">
                    <h3>Lokasi</h3>

                    <div class="form-group">
                        <label>Kecamatan *</label>
                        <select id="kecamatan_id" name="kecamatan_id" required>
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}"
                                    {{ old('kecamatan_id', $bankSampah->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                                    {{ $kecamatan->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kelurahan *</label>
                        <select id="kelurahan_id" name="kelurahan_id" required>
                            <option value="">Pilih Kelurahan</option>
                            @foreach($kelurahans as $kelurahan)
                                <option value="{{ $kelurahan->id }}"
                                    {{ old('kelurahan_id', $bankSampah->kelurahan_id) == $kelurahan->id ? 'selected' : '' }}>
                                    {{ $kelurahan->nama_kelurahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>RW *</label>
                        <input type="text" name="rw"
                               value="{{ old('rw', $bankSampah->rw) }}" required>
                    </div>
                </div>

            </div>
        </div>

        {{-- KONTAK --}}
        <div class="edit-card">
            <h3>Penanggung Jawab</h3>

            <div class="form-row">
                <div class="form-group">
                    <label>Nama Direktur *</label>
                    <input type="text" name="nama_direktur"
                           value="{{ old('nama_direktur', $bankSampah->nama_direktur) }}" required>
                </div>

                <div class="form-group">
                    <label>Nomor HP *</label>
                    <input type="text" name="nomor_hp"
                           value="{{ old('nomor_hp', $bankSampah->nomor_hp) }}" required>
                </div>
            </div>
        </div>

        {{-- ACTION --}}
        <div class="edit-actions">
            <a href="{{ route('admin.bank-sampah.show', $bankSampah) }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection