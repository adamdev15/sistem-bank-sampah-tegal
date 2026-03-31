@extends('layouts.admin')

@section('page-title', 'Reset Password')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Verifikasi Akun</a></li>
        <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
    </ol>
</nav>
@endsection

@section('content-body')
<div class="data-container">
    <div class="card border-0 shadow-sm verify-card-shell reset-password-shell">
        <div class="card-body p-4 p-lg-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                <div>
                    <h5 class="mb-1 fw-semibold text-success"><i class="fas fa-key me-2"></i>Reset Password User</h5>
                    <p class="mb-0 text-muted small">Gunakan password sementara yang aman dan minta user mengganti saat login.</p>
                </div>
                <span class="badge bg-success-subtle text-success-emphasis px-3 py-2">{{ ucfirst(str_replace('_', ' ', $user->status)) }}</span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6">
                    <div class="reset-info-card">
                        <div class="reset-info-label">Nama</div>
                        <div class="reset-info-value">{{ $user->name }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="reset-info-card">
                        <div class="reset-info-label">Email</div>
                        <div class="reset-info-value">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="reset-info-card">
                        <div class="reset-info-label">Role</div>
                        <div class="reset-info-value">
                            <span class="badge bg-primary-subtle text-primary-emphasis">{{ ucfirst(str_replace('_',' ', $user->role)) }}</span>
                        </div>
                    </div>
                </div>
                @if($user->bankSampahMaster)
                <div class="col-12 col-md-6">
                    <div class="reset-info-card">
                        <div class="reset-info-label">Bank Sampah</div>
                        <div class="reset-info-value">{{ $user->bankSampahMaster->nama_bank_sampah }}</div>
                    </div>
                </div>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="reset-modern-form">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label">Password Baru *</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="8" autocomplete="new-password">
                        <small class="text-muted">Minimal 8 karakter.</small>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>

                    <div class="col-12">
                        <div class="form-check mt-1">
                            <input type="checkbox" class="form-check-input text-success" id="force_password_change" name="force_password_change" value="1" checked>
                            <label class="form-check-label" for="force_password_change">
                                <i class="fas fa-shield-alt text-primary me-1"></i>
                                Wajibkan user mengganti password saat login pertama
                            </label>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success mt-3 mb-4">
                    Jika opsi di atas aktif, user hanya dapat melanjutkan ke sistem setelah mengganti password sementara.
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-key me-1"></i> Reset Password
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
