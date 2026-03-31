@extends('layouts.guest')

@section('title', 'Registrasi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card auth-card-basman">
                <div class="card-header-basman">
                    <h1 class="h4 mb-1 fw-bold"><i class="fas fa-user-plus me-2 opacity-90"></i>Registrasi Bank Sampah</h1>
                    <p>Pendaftaran akun pengelola BASMAN — Kota Tegal</p>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success small mb-4">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Sesuai identitas">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password" placeholder="Minimal sesuai ketentuan">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                            </div>
                            <div class="col-12">
                                <label for="bank_sampah_master_id" class="form-label fw-semibold">Bank Sampah</label>
                                <select class="form-select @error('bank_sampah_master_id') is-invalid @enderror" id="bank_sampah_master_id" name="bank_sampah_master_id" required>
                                    <option value="">— Pilih Bank Sampah —</option>
                                    @foreach($bankSampahs as $group => $items)
                                        <optgroup label="{{ $group }}">
                                            @foreach($items as $bank)
                                                <option value="{{ $bank->id }}" {{ old('bank_sampah_master_id') == $bank->id ? 'selected' : '' }}>
                                                    {{ $bank->nama_bank_sampah }} — RW {{ $bank->rw }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('bank_sampah_master_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" value="1" required {{ old('terms') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="terms">
                                        Saya menyatakan data yang diisi benar dan akan digunakan untuk keperluan administrasi BASMAN.
                                    </label>
                                    @error('terms')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-basman-primary text-white btn-lg w-100 mt-4 mb-3">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Pendaftaran
                        </button>
                        <p class="text-center text-muted small mb-0">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="auth-link-muted">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
