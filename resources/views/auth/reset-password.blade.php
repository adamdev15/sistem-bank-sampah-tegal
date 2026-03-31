@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-6">
            <div class="card auth-card-basman">
                <div class="card-header-basman">
                    <h1 class="h4 mb-1 fw-bold"><i class="fas fa-key me-2 opacity-90"></i>Reset Password</h1>
                    <p>Masukkan password baru untuk akun BASMAN Anda</p>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success small mb-4">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger small mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-secondary"></i></span>
                                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-secondary"></i></span>
                                <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-shield-alt text-secondary"></i></span>
                                <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-basman-primary text-white btn-lg w-100 mb-3">
                            <i class="fas fa-save me-2"></i>Simpan Password Baru
                        </button>
                        <p class="text-center text-muted small mb-0">
                            <a href="{{ route('login') }}" class="auth-link-muted"><i class="fas fa-arrow-left me-1"></i>Kembali ke login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
