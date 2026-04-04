@extends('layouts.admin')

@section('page-title', 'Data Operasional Bank Sampah')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Data Operasional
        </li>
    </ol>
</nav>
@endsection

@section('styles')
<link href="{{ asset('css/operasional.css') }}" rel="stylesheet">
<style>
.op-detail-modal .modal-header { background: linear-gradient(135deg, #1f5f46, #2f7d5a); color: #fff; border: 0; }
.op-detail-modal .modal-title { font-size: 1.05rem; }
.op-detail-modal .modal-body { min-width: 0; overflow-x: hidden; }
.op-detail-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem 1.5rem;
    align-items: start;
}
@media (max-width: 575.98px) {
    .op-detail-grid-2 { grid-template-columns: 1fr; }
}
.op-detail-col { min-width: 0; }
.op-detail-section { margin-bottom: 1.1rem; }
.op-detail-section:last-child { margin-bottom: 0; }
.op-detail-section h6 {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #1f5f46;
    font-weight: 700;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid #e3ece7;
    padding-bottom: 0.35rem;
}
.op-detail-kv {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    padding: 0.42rem 0;
    border-bottom: 1px solid #f0f4f2;
    font-size: 0.875rem;
}
.op-detail-kv:last-child { border-bottom: 0; }
.op-detail-kv .k { color: #5a6d66; flex: 0 1 auto; min-width: 0; }
.op-detail-kv .v { font-weight: 600; color: #1f3d2b; text-align: right; flex: 0 1 auto; max-width: 58%; word-break: break-word; }
.op-detail-kv.op-detail-total .k,
.op-detail-kv.op-detail-total .v { font-weight: 700; color: #1f5f46; }
</style>
@endsection

@section('content-body')
<div class="data-container modern-master-wrap">

    {{-- ================= FILTER ================= --}}
    <div class="filter-section card">
        <form method="GET" action="{{ route('admin.operasional.index') }}" class="filter-form mb-3">
            <div class="filter-row">

                <div class="filter-group">
                    <label>Kecamatan</label>
                    <select name="kecamatan_id">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}"
                                {{ request('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                {{ $kecamatan->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label>Pencarian</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nama bank sampah">
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.operasional.index') }}" class="btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>

            </div>
        </form>

        {{-- ================= TABLE ================= --}}
        <div class="table-header">
            <h3>Data Operasional Bank Sampah</h3>

            <button class="btn-primary" type="button" onclick="exportData()">
                <i class="fas fa-download"></i> Export Excel
            </button>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Bank Sampah</th>
                        <th>Kecamatan</th>
                        <th>Tenaga Kerja</th>
                        <th>Nasabah</th>
                        <th>Omset</th>
                        <th>Buku Tabungan</th>
                        <th>Sistem</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($operasionals as $i => $op)
                    @php
                        $detail = [
                            'nama_bank_sampah' => $op->bankSampahMaster->nama_bank_sampah ?? '-',
                            'tenaga_kerja_laki' => (int) $op->tenaga_kerja_laki,
                            'tenaga_kerja_perempuan' => (int) $op->tenaga_kerja_perempuan,
                            'nasabah_laki' => (int) $op->nasabah_laki,
                            'nasabah_perempuan' => (int) $op->nasabah_perempuan,
                            'omset' => 'Rp ' . number_format($op->omset, 0, ',', '.'),
                            'tempat_penjualan' => $op->tempat_penjualan_label,
                            'kegiatan_pengelolaan' => $op->kegiatan_pengelolaan ?: '-',
                            'produk_daur_ulang' => $op->produk_daur_ulang ?: '-',
                            'buku_tabungan' => $op->buku_tabungan_label,
                            'sistem_pencatatan' => $op->sistem_pencatatan ?: '-',
                            'timbangan' => $op->timbangan_label,
                            'alat_pengangkut' => $op->alat_pengangkut_label,
                        ];
                        $jsonFlags = JSON_UNESCAPED_UNICODE | (defined('JSON_INVALID_UTF8_SUBSTITUTE') ? JSON_INVALID_UTF8_SUBSTITUTE : 0);
                        $detailB64 = base64_encode(json_encode($detail, $jsonFlags) ?: '{}');
                    @endphp
                    <tr>
                        <td>
                            {{ ($operasionals->currentPage() - 1) * $operasionals->perPage() + $i + 1 }}
                        </td>
                        <td>{{ $op->bankSampahMaster->nama_bank_sampah }}</td>
                        <td>{{ $op->bankSampahMaster->kecamatan->nama_kecamatan }}</td>
                        <td>{{ $op->tenaga_kerja_laki + $op->tenaga_kerja_perempuan }}</td>
                        <td>{{ $op->nasabah_laki + $op->nasabah_perempuan }}</td>
                        <td>Rp {{ number_format($op->omset, 0, ',', '.') }}</td>
                        <td>{{ $op->buku_tabungan }}</td>
                        <td>{{ $op->sistem_pencatatan }}</td>
                        <td>
                            <button type="button"
                                    class="btn-view"
                                    title="Detail operasional"
                                    data-bs-toggle="modal"
                                    data-bs-target="#operasionalDetailModal"
                                    data-detail-b64="{{ $detailB64 }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $operasionals->links() }}
    </div>
</div>

{{-- Modal detail operasional --}}
<div class="modal fade op-detail-modal" id="operasionalDetailModal" tabindex="-1" aria-labelledby="operasionalDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="operasionalDetailModalLabel"><i class="fas fa-chart-line me-2"></i>Detail Operasional</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="operasionalDetailBody">
                <p class="text-muted small mb-0">Memuat…</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ================= HIDDEN EXPORT FORM ================= --}}
<form id="exportForm"
      method="POST"
      action="{{ route('admin.export.generate') }}"
      style="display:none;">
    @csrf
    <input type="hidden" name="type" value="operasional">
    <input type="hidden" name="format" value="excel">
    <input type="hidden" name="kecamatan_id" id="exportKecamatanId">
    <input type="hidden" name="search" id="exportSearch">
</form>

<script>
function exportData() {
    const kec = document.querySelector('[name="kecamatan_id"]');
    const sea = document.querySelector('[name="search"]');
    document.getElementById('exportKecamatanId').value = kec ? kec.value : '';
    document.getElementById('exportSearch').value = sea ? sea.value : '';
    document.getElementById('exportForm').submit();
}

function parseDetailFromB64(b64) {
    if (!b64 || typeof b64 !== 'string') return null;
    const bin = atob(b64.trim());
    const bytes = new Uint8Array(bin.length);
    for (let i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
    const json = new TextDecoder('utf-8').decode(bytes);
    return JSON.parse(json);
}

document.getElementById('operasionalDetailModal')?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    const b64 = btn?.getAttribute('data-detail-b64');
    const body = document.getElementById('operasionalDetailBody');
    if (!b64 || !body) return;
    let d;
    try {
        d = parseDetailFromB64(b64);
    } catch (e) {
        body.innerHTML = '<p class="text-danger">Data tidak valid.</p>';
        return;
    }
    if (!d || typeof d !== 'object') {
        body.innerHTML = '<p class="text-danger">Data tidak valid.</p>';
        return;
    }

    const tkTot = d.tenaga_kerja_laki + d.tenaga_kerja_perempuan;
    const nsTot = d.nasabah_laki + d.nasabah_perempuan;

    body.innerHTML = `
        <p class="fw-semibold text-dark mb-3 text-center"><i class="fas fa-university text-success me-2"></i>${escapeHtml(d.nama_bank_sampah)}</p>
        <div class="op-detail-grid-2">
            <div class="op-detail-col">
                <div class="op-detail-section">
                    <h6>Tenaga Kerja</h6>
                    <div class="op-detail-kv"><span class="k">Laki-laki</span><span class="v">${d.tenaga_kerja_laki} orang</span></div>
                    <div class="op-detail-kv"><span class="k">Perempuan</span><span class="v">${d.tenaga_kerja_perempuan} orang</span></div>
                    <div class="op-detail-kv op-detail-total"><span class="k">Total</span><span class="v">${tkTot} orang</span></div>
                </div>
                <div class="op-detail-section">
                    <h6>Nasabah</h6>
                    <div class="op-detail-kv"><span class="k">Laki-laki</span><span class="v">${d.nasabah_laki} orang</span></div>
                    <div class="op-detail-kv"><span class="k">Perempuan</span><span class="v">${d.nasabah_perempuan} orang</span></div>
                    <div class="op-detail-kv op-detail-total"><span class="k">Total</span><span class="v">${nsTot} orang</span></div>
                </div>
                <div class="op-detail-section">
                    <h6>Keuangan</h6>
                    <div class="op-detail-kv"><span class="k">Omset bulanan</span><span class="v">${escapeHtml(d.omset)}</span></div>
                    <div class="op-detail-kv"><span class="k">Tempat penjualan</span><span class="v">${escapeHtml(d.tempat_penjualan)}</span></div>
                </div>
            </div>
            <div class="op-detail-col">
                <div class="op-detail-section">
                    <h6>Kegiatan &amp; produk</h6>
                    <div class="op-detail-kv"><span class="k">Kegiatan pengelolaan</span><span class="v">${escapeHtml(d.kegiatan_pengelolaan)}</span></div>
                    <div class="op-detail-kv"><span class="k">Produk daur ulang</span><span class="v">${escapeHtml(d.produk_daur_ulang)}</span></div>
                </div>
                <div class="op-detail-section">
                    <h6>Sarana &amp; prasarana</h6>
                    <div class="op-detail-kv"><span class="k">Buku tabungan</span><span class="v">${escapeHtml(d.buku_tabungan)}</span></div>
                    <div class="op-detail-kv"><span class="k">Sistem pencatatan</span><span class="v">${escapeHtml(d.sistem_pencatatan)}</span></div>
                    <div class="op-detail-kv"><span class="k">Timbangan</span><span class="v">${escapeHtml(d.timbangan)}</span></div>
                    <div class="op-detail-kv"><span class="k">Alat pengangkut</span><span class="v">${escapeHtml(d.alat_pengangkut)}</span></div>
                </div>
            </div>
        </div>
    `;
});

function escapeHtml(s) {
    if (s == null) return '';
    const t = document.createElement('div');
    t.textContent = s;
    return t.innerHTML;
}
</script>
@endsection
