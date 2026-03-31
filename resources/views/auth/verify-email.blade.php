@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-6">
            <div class="card auth-card-basman">
                <div class="card-header-basman">
                    <h1 class="h4 mb-1 fw-bold"><i class="fas fa-envelope-open-text me-2 opacity-90"></i>Verifikasi Email</h1>
                    <p>Aktifkan akun Anda melalui link verifikasi email</p>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success small mb-4">{{ session('status') }}</div>
                    @endif

                    <p class="text-secondary mb-4">
                        Kami sudah mengirim link verifikasi ke email Anda. Klik link tersebut agar akun dapat digunakan.
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-basman-primary text-white btn-lg w-100 mb-3">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Ulang Link Verifikasi
                        </button>
                    </form>

                    <p class="text-center text-muted small mb-0">
                        <a href="{{ route('login') }}" class="auth-link-muted"><i class="fas fa-arrow-left me-1"></i>Kembali ke login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
