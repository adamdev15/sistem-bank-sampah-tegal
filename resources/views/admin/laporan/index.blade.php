@extends('layouts.admin')

@section('page-title', 'Verifikasi Laporan')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Verifikasi Laporan</li>
    </ol>
</nav>
@endsection

@section('content-body')
@php
    $waitingCount = $laporans->where('status', 'menunggu_verifikasi')->count();
    $approvedCount = $laporans->where('status', 'disetujui')->count();
    $revisionCount = $laporans->where('status', 'perlu_perbaikan')->count();
@endphp

<div class="verification-layout">
    <div class="verification-stats">
        <div class="verify-stat-card waiting">
            <div class="verify-icon"><i class="fas fa-hourglass-half"></i></div>
            <div>
                <h3>{{ $waitingCount }}</h3>
                <p>Menunggu Verifikasi</p>
            </div>
        </div>
        <div class="verify-stat-card approved">
            <div class="verify-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <h3>{{ $approvedCount }}</h3>
                <p>Disetujui</p>
            </div>
        </div>
        <div class="verify-stat-card revision">
            <div class="verify-icon"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <h3>{{ $revisionCount }}</h3>
                <p>Perlu Perbaikan</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm verify-card-shell">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3 align-items-end mb-3">
                <div class="col-12 col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="perlu_perbaikan" {{ request('status') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label">Kecamatan</label>
                    <select name="kecamatan_id" class="form-select">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                {{ $kecamatan->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label">Periode</label>
                    <input type="month" name="periode" class="form-control" value="{{ request('periode') }}">
                </div>

                <div class="col-12 col-md-4">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama bank sampah...">
                </div>

                <div class="col-12 col-md-2">
                    <label for="from-label"> Filtering</label>
                    <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100">Filter</button>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle mb-0 data-table modern-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Bank Sampah</th>
                            <th>Kecamatan</th>
                            <th>Sampah Masuk</th>
                            <th>Sampah Terkelola</th>
                            <th>Status</th>
                            <th>Tanggal Laporkan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporans as $index => $laporan)
                        <tr>
                            <td>{{ ($laporans->currentPage() - 1) * $laporans->perPage() + $index + 1 }}</td>
                            <td><strong>{{ $laporan->periode->translatedFormat('F Y') }}</strong></td>
                            <td>
                                {{ $laporan->bankSampahMaster->nama_bank_sampah }}<br>
                                <small class="text-muted">{{ $laporan->bankSampahMaster->kelurahan->nama_kelurahan }}, RW {{ $laporan->bankSampahMaster->rw }}</small>
                            </td>
                            <td>{{ $laporan->bankSampahMaster->kecamatan->nama_kecamatan }}</td>
                            <td class="text-end">{{ number_format($laporan->jumlah_sampah_masuk, 0, ',', '.') }} kg</td>
                            <td class="text-end">{{ number_format($laporan->jumlah_sampah_terkelola, 0, ',', '.') }} kg</td>
                            <td>
                                <span class="status-badge status-{{ str_replace('_', '-', $laporan->status) }}">{{ ucfirst(str_replace('_', ' ', $laporan->status)) }}</span>
                            </td>
                            <td>{{ $laporan->created_at->translatedFormat('d/m/Y H:i') }}</td>
                            <td class="text-center action-column">
                                <a href="{{ route('admin.laporan.show', $laporan) }}" class="btn-view" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                @if($laporan->status == 'menunggu_verifikasi')
                                    <button class="btn-verify" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $laporan->id }}" title="Verifikasi">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="verifyModal{{ $laporan->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Verifikasi Laporan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.laporan.verify', $laporan) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p><strong>Bank Sampah:</strong> {{ $laporan->bankSampahMaster->nama_bank_sampah }}</p>
                                            <p><strong>Periode:</strong> {{ $laporan->periode->translatedFormat('F Y') }}</p>
                                            <div class="mb-3">
                                                <label class="form-label">Status Verifikasi</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="disetujui">Disetujui</option>
                                                    <option value="perlu_perbaikan">Perlu Perbaikan</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label">Catatan (Opsional)</label>
                                                <textarea name="catatan_verifikasi" class="form-control" rows="3" placeholder="Berikan catatan jika diperlukan..."></textarea>
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
                        @empty
                        <tr><td colspan="9" class="text-center py-4">Tidak ada laporan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-3">{{ $laporans->links() }}</div>
        </div>
    </div>
</div>
@endsection
