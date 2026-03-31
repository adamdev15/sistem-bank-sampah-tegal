@extends('layouts.admin')

@section('page-title', 'Verifikasi Akun Bank Sampah')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Verifikasi Akun</li>
    </ol>
</nav>
@endsection

@section('content-body')
@php
    $waitingUsers = $users->where('status','menunggu_verifikasi')->count();
    $activeUsers = $users->where('status','aktif')->count();
    $rejectedUsers = $users->where('status','ditolak')->count();
@endphp

<div class="verification-layout">
    <div class="verification-stats">
        <div class="verify-stat-card waiting">
            <div class="verify-icon"><i class="fas fa-user-clock"></i></div>
            <div><h3>{{ $waitingUsers }}</h3><p>Menunggu Verifikasi</p></div>
        </div>
        <div class="verify-stat-card approved">
            <div class="verify-icon"><i class="fas fa-user-check"></i></div>
            <div><h3>{{ $activeUsers }}</h3><p>Akun Aktif</p></div>
        </div>
        <div class="verify-stat-card revision">
            <div class="verify-icon"><i class="fas fa-user-xmark"></i></div>
            <div><h3>{{ $rejectedUsers }}</h3><p>Ditolak</p></div>
        </div>
        <div class="verify-stat-card total">
            <div class="verify-icon"><i class="fas fa-users"></i></div>
            <div><h3>{{ $users->total() }}</h3><p>Total Akun</p></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm verify-card-shell">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end mb-3">
                <div class="col-12 col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama / Email / Bank Sampah...">
                </div>

                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-filter me-1"></i>Filter</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle mb-0 data-table modern-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Bank Sampah</th>
                            <th>Kecamatan</th>
                            <th>Tgl Daftar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ ($users->currentPage()-1)*$users->perPage()+$index+1 }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong><br>
                                <small class="text-muted">Direktur: {{ $user->bankSampahMaster->nama_direktur ?? '-' }}</small>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->bankSampahMaster)
                                    {{ $user->bankSampahMaster->nama_bank_sampah }}<br>
                                    <small class="text-muted">{{ $user->bankSampahMaster->kelurahan->nama_kelurahan }}, RW {{ $user->bankSampahMaster->rw }}</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $user->bankSampahMaster->kecamatan->nama_kecamatan ?? '-' }}</td>
                            <td>{{ $user->created_at->translatedFormat('d/m/Y') }}</td>
                            <td>
                                <span class="status-badge badge-sm status-{{ $user->status }}">{{ ucfirst(str_replace('_',' ',$user->status)) }}</span>
                            </td>
                            <td class="action-column text-center">
                                @if($user->status == 'menunggu_verifikasi')
                                    <button class="btn-verify" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $user->id }}"><i class="fas fa-check"></i></button>
                                @endif
                                @if($user->status == 'aktif')
                                    <a href="{{ route('admin.users.reset', $user) }}" class="btn-reset-password" title="Reset Password"><i class="fas fa-key"></i></a>
                                @endif
                            </td>
                        </tr>

                        @if($user->status == 'menunggu_verifikasi')
                        <div class="modal fade" id="verifyModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Verifikasi Akun</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                                            <p><strong>Email:</strong> {{ $user->email }}</p>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="aktif">Aktifkan</option>
                                                    <option value="ditolak">Tolak</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">Catatan</label>
                                                <textarea name="catatan" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <tr><td colspan="8" class="text-center py-4">Tidak ada data akun</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-3">{{ $users->links() }}</div>
        </div>
    </div>
</div>
@endsection
