@extends('layouts.admin')

@section('page-title', 'Detail Laporan')
@section('breadcrumb', 'Laporan / Detail')

@section('styles')
<link href="{{ asset('css/laporan.css') }}" rel="stylesheet">
@endsection

@section('content-body')
@php
    $fmtKg = fn ($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');
    $bs = $laporan->bankSampahMaster;
    $pctTerkelola = $laporan->jumlah_sampah_masuk > 0
        ? ($laporan->jumlah_sampah_terkelola / $laporan->jumlah_sampah_masuk) * 100
        : 0;
    $jenisLabels = [
        'plastik_keras' => 'Plastik Keras',
        'plastik_fleksibel' => 'Plastik Fleksibel',
        'kertas_karton' => 'Kertas/Karton',
        'logam' => 'Logam',
        'kaca' => 'Kaca',
        'karet_kulit' => 'Karet/Kulit',
        'kain_tekstil' => 'Kain/Tekstil',
        'lainnya' => 'Lainnya',
    ];
    $totalTerkelola = $laporan->jumlah_sampah_terkelola;
@endphp

<div class="admin-laporan-show modern-master-wrap">


    {{-- Gabungan: ringkas laporan + info bank sampah --}}
    <div class="admin-laporan-card mb-4">
        <div class="admin-laporan-card-head d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h3 class="mb-0"><i class="fas fa-file-alt me-2 text-success"></i>Ringkasan & Bank Sampah</h3>
            <div class="d-flex flex-wrap gap-2">
            @if($laporan->status == 'menunggu_verifikasi')
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal">
                    <i class="fas fa-check me-1"></i> Verifikasi Laporan
                </button>
            @endif
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        </div>
        <div class="admin-laporan-card-body">
        <div class="admin-laporan-card-body pt-2">
            <div class="admin-utama-grid">
                <div class="admin-utama-item">
                    <div class="admin-utama-icon"><i class="fas fa-trash-alt"></i></div>
                    <div class="admin-utama-meta">
                        <span class="admin-utama-label">Sampah masuk</span>
                        <strong class="admin-utama-num">{{ $fmtKg($laporan->jumlah_sampah_masuk) }} kg</strong>
                    </div>
                </div>
                <div class="admin-utama-item">
                    <div class="admin-utama-icon admin-utama-icon--green"><i class="fas fa-recycle"></i></div>
                    <div class="admin-utama-meta">
                        <span class="admin-utama-label">Sampah terkelola</span>
                        <strong class="admin-utama-num">{{ $fmtKg($laporan->jumlah_sampah_terkelola) }} kg</strong>
                    </div>
                </div>
                <div class="admin-utama-item">
                    <div class="admin-utama-icon admin-utama-icon--purple"><i class="fas fa-users"></i></div>
                    <div class="admin-utama-meta">
                        <span class="admin-utama-label">Jumlah nasabah</span>
                        <strong class="admin-utama-num">{{ $laporan->jumlah_nasabah }} orang</strong>
                    </div>
                </div>
                <div class="admin-utama-item">
                    <div class="admin-utama-icon admin-utama-icon--amber"><i class="fas fa-percentage"></i></div>
                    <div class="admin-utama-meta">
                        <span class="admin-utama-label">Persentase terkelola</span>
                        <strong class="admin-utama-num">{{ number_format($pctTerkelola, 1, ',', '.') }}%</strong>
                    </div>
                </div>
            </div>
        </div>
            <div class="admin-detail-grid">
                <div class="admin-detail-row">
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">Nama bank sampah</span>
                        <span class="admin-detail-value">{{ $bs->nama_bank_sampah }}</span>
                    </div>
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">Periode</span>
                        <span class="admin-detail-value"><span class="badge rounded-pill bg-light text-dark border">{{ $laporan->periode->translatedFormat('F Y') }}</span></span>
                    </div>
                </div>
                <div class="admin-detail-row">
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">Status</span>
                        <span class="admin-detail-value">
                            <span class="status-badge status-{{ str_replace('_', '-', $laporan->status) }}">{{ ucfirst(str_replace('_', ' ', $laporan->status)) }}</span>
                        </span>
                    </div>
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">Tanggal input</span>
                        <span class="admin-detail-value"><span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary">{{ $laporan->created_at->translatedFormat('d F Y H:i') }}</span></span>
                    </div>
                </div>
                <div class="admin-detail-row">
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">Kecamatan</span>
                        <span class="admin-detail-value">{{ $bs->kecamatan->nama_kecamatan }}</span>
                    </div>
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">Kelurahan</span>
                        <span class="admin-detail-value">{{ $bs->kelurahan->nama_kelurahan }}</span>
                    </div>
                </div>
                <div class="admin-detail-row">
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">RW</span>
                        <span class="admin-detail-value"><span class="badge bg-light text-dark border">{{ $bs->rw }}</span></span>
                    </div>
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">Direktur</span>
                        <span class="admin-detail-value">{{ $bs->nama_direktur ?: '—' }}</span>
                    </div>
                </div>
                <div class="admin-detail-row admin-detail-row-last">
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">No. HP</span>
                        <span class="admin-detail-value">{{ $bs->nomor_hp ?: '—' }}</span>
                    </div>
                    <div class="admin-detail-cell">
                        <span class="admin-detail-label">Nomor SK</span>
                        <span class="admin-detail-value">{{ $bs->nomor_sk ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Rincian --}}
    <div class="admin-laporan-card mb-4">
        <div class="admin-laporan-card-head">
            <h3 class="mb-0"><i class="fas fa-list-ul me-2 text-success"></i>Rincian Jenis Sampah Terkelola</h3>
        </div>
        <div class="admin-laporan-card-body p-0">
            <div class="table-responsive">
                <table class="table admin-rincian-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:56px;">No</th>
                            <th>Jenis sampah</th>
                            <th class="text-end">Jumlah (kg)</th>
                            <th style="min-width: 200px;">Komposisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporan->details as $index => $detail)
                            @php
                                $pct = $totalTerkelola > 0 ? ($detail->jumlah / $totalTerkelola) * 100 : 0;
                                $label = $jenisLabels[$detail->jenis_sampah] ?? ucfirst(str_replace('_', ' ', $detail->jenis_sampah));
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td><strong>{{ $label }}</strong></td>
                                <td class="text-end fw-semibold">{{ $fmtKg($detail->jumlah) }}</td>
                                <td>
                                    <div class="admin-rincian-bar-wrap">
                                        <div class="admin-rincian-bar" role="progressbar" aria-valuenow="{{ round($pct) }}" aria-valuemin="0" aria-valuemax="100">
                                            <span style="width: {{ min(100, $pct) }}%;"></span>
                                        </div>
                                        <span class="admin-rincian-pct">{{ number_format($pct, 1, ',', '.') }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="admin-rincian-total">
                            <td colspan="2" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold">{{ $fmtKg($totalTerkelola) }} kg</td>
                            <td class="fw-bold">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($laporan->catatan_verifikasi || $laporan->status != 'menunggu_verifikasi')
        <div class="admin-laporan-card mb-4">
            <div class="admin-laporan-card-head d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="mb-0"><i class="fas fa-comment-dots me-2 text-success"></i>Catatan Verifikasi</h3>
                @if($laporan->status != 'menunggu_verifikasi')
                    <span class="badge bg-info text-dark">Sudah diverifikasi</span>
                @endif
            </div>
            <div class="admin-laporan-card-body">
                @if($laporan->catatan_verifikasi)
                    <p class="mb-2">{{ $laporan->catatan_verifikasi }}</p>
                    <small class="text-muted">Diperbarui: {{ $laporan->updated_at->translatedFormat('d F Y H:i') }}</small>
                @else
                    <p class="text-muted mb-0">Tidak ada catatan verifikasi.</p>
                @endif
            </div>
        </div>
    @endif
</div>

@if($laporan->status == 'menunggu_verifikasi')
<div class="modal fade" id="verifyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #1f5f46, #2f7d5a);">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Verifikasi Laporan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.laporan.verify', $laporan) }}" id="verifyLaporanForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status verifikasi</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">— Pilih —</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="perlu_perbaikan">Perlu perbaikan</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label for="catatan_verifikasi" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="catatan_verifikasi" name="catatan_verifikasi" rows="3" placeholder="Catatan untuk bank sampah…"></textarea>
                        <small class="text-muted">Catatan akan ditampilkan kepada pengguna bank sampah.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="verifySubmitBtn">Simpan verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('verifyLaporanForm');
    if (form) {
        form.addEventListener('submit', function () {
            const btn = document.getElementById('verifySubmitBtn');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memproses…'; }
        });
    }
    const statusSelect = document.getElementById('status');
    const catatan = document.getElementById('catatan_verifikasi');
    if (statusSelect && catatan) {
        statusSelect.addEventListener('change', function () {
            if (this.value === 'perlu_perbaikan') {
                catatan.placeholder = 'Jelaskan bagian yang perlu diperbaikan…';
                catatan.required = true;
            } else {
                catatan.placeholder = 'Catatan untuk bank sampah…';
                catatan.required = false;
            }
        });
    }
});
</script>
@endif
@endsection
