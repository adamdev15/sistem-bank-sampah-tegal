@extends('layouts.guest')

@section('title', 'Ganti Password Wajib')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">
            <div class="card auth-card-basman">
                <div class="card-header-basman">
                    <h1 class="h4 mb-1 fw-bold"><i class="fas fa-shield-alt me-2 opacity-90"></i>Ganti password wajib</h1>
                    <p>Anda masuk dengan password sementara</p>
                </div>
                <div class="card-body">
                    @if (session('warning'))
                        <div class="alert alert-warning small">{{ session('warning') }}</div>
                    @endif

                    <div class="alert alert-info small mb-4">
                        <strong>Perhatian.</strong> Untuk keamanan, silakan ganti password sementara dengan password baru Anda.
                    </div>

                    <form method="POST" action="{{ route('password.change.force') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Password sementara</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required autocomplete="current-password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label fw-semibold">Password baru</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required minlength="8" autocomplete="new-password">
                            <div class="form-text">Minimal 8 karakter.</div>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi password baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-basman-primary btn-lg w-100">
                            <i class="fas fa-check me-2"></i>Simpan password baru
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
