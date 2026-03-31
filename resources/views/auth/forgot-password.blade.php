@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-6">
            <div class="card auth-card-basman">
                <div class="card-header-basman">
                    <h1 class="h4 mb-1 fw-bold"><i class="fas fa-unlock-alt me-2 opacity-90"></i>Lupa Password</h1>
                    <p>Reset password akun BASMAN melalui admin DLH</p>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success small mb-4">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email terdaftar</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-secondary"></i></span>
                                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email yang digunakan saat daftar">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="auth-info-box mb-4 text-secondary">
                            <p class="fw-semibold text-dark mb-2"><i class="fas fa-info-circle me-1 text-success"></i> Alur reset password</p>
                            <ol class="small ps-3 mb-0">
                                <li>Masukkan email akun BASMAN Anda.</li>
                                <li>Sistem mengirim link reset ke email Anda.</li>
                                <li>Klik link, lalu isi password baru.</li>
                                <li>Login menggunakan password baru.</li>
                            </ol>
                        </div>

                        <button type="submit" class="btn btn-basman-primary text-white btn-lg w-100 mb-3">
                            <i class="fas fa-paper-plane me-2"></i>Kirim link reset
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
