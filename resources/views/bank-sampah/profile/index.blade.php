@extends('layouts.bank-sampah')

@section('page-title', 'Profil Bank Sampah')
@section('breadcrumb', 'Profil')

@section('styles')
<link href="{{ asset('css/bank/profile.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="profile-container">

    {{-- ================= PROFILE HEADER ================= --}}
    <div class="profile-header" style="background: linear-gradient(135deg, #1f5f46, #2f7d5a);">
        <div class="profile-avatar">
            <div class="avatar-icon">
                <i class="fas fa-recycle"></i>
            </div>
            <div class="avatar-info">
                <h2>{{ $bankSampah->nama_bank_sampah }}</h2>
                <p class="role-badge">Bank Sampah</p>
            </div>
        </div>

        <div class="profile-status">
            <div class="status-badge status-active">
                <i class="fas fa-check-circle"></i> Akun Aktif
            </div>
            <p class="member-since">
                Member sejak: {{ $user->created_at->translatedFormat('d F Y') }}
            </p>
        </div>
    </div>

    {{-- ================= TABS ================= --}}
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info">
                    <i class="fas fa-info-circle"></i> Informasi Bank
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#account">
                    <i class="fas fa-user-circle"></i> Akun Login
                </button>
            </li>
        </ul>

        <div class="tab-content">

            {{-- ================= TAB INFORMASI ================= --}}
            <div class="tab-pane fade show active" id="info">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-building"></i> Data Bank Sampah</h4>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">

                            <div class="info-group">
                                <h5>Identitas</h5>
                                <div class="info-item">
                                    <span class="label">Nama Bank Sampah</span>
                                    <span class="value">{{ $bankSampah->nama_bank_sampah }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Nomor SK</span>
                                    <span class="value">{{ $bankSampah->nomor_sk ?? '-' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Status</span>
                                    <span class="badge bg-success">Sudah</span>
                                </div>
                            </div>

                            <div class="info-group">
                                <h5>Lokasi</h5>
                                <div class="info-item">
                                    <span class="label">Kecamatan</span>
                                    <span class="value">{{ $bankSampah->kecamatan->nama_kecamatan }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Kelurahan</span>
                                    <span class="value">{{ $bankSampah->kelurahan->nama_kelurahan }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">RW</span>
                                    <span class="value">{{ $bankSampah->rw }}</span>
                                </div>
                            </div>

                            <div class="info-group">
                                <h5>Kontak</h5>
                                <div class="info-item">
                                    <span class="label">Direktur</span>
                                    <span class="value">{{ $bankSampah->nama_direktur }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">No. HP</span>
                                    <span class="value">{{ $bankSampah->nomor_hp }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Keterangan</span>
                                    <span class="value">{{ $bankSampah->keterangan ?? '-' }}</span>
                                </div>
                            </div>

                        </div>

                        <div class="info-actions">
                            <i class="fas fa-info-circle"></i>
                            Perubahan data bank sampah dilakukan oleh Admin DLH.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TAB AKUN ================= --}}
            <div class="tab-pane fade" id="account">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-user-cog"></i> Informasi Akun</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('bank-sampah.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', $user->name) }}">
                                    @error('name') <span class="error">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email', $user->email) }}">
                                    @error('email') <span class="error">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-actions">
                                <button class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>

                                <a href="#password-section" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-key"></i> Ganti Password
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    {{-- ================= PASSWORD SECTION ================= --}}
    <div class="card mt-4" id="password-section">
        <div class="card-header">
            <h4><i class="fas fa-key"></i> Ganti Password</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('bank-sampah.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-control">
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="new_password" class="form-control">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-key"></i> Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
