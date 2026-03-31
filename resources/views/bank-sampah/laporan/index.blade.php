@extends('layouts.bank-sampah')

@section('title', 'Laporan Bulanan')
@section('breadcrumb', 'Laporan')

@section('styles')
<link href="{{ asset('css/bank/laporan.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="laporan-index-container">
    @php
        $fmt = fn($v) => rtrim(rtrim(number_format((float) $v, 2, ',', '.'), '0'), ',');
    @endphp


    {{-- ======================================================
        PAGE HEADER
    ====================================================== --}}
    <div class="page-header">
        <div class="header-content">
            <h1>Laporan Bulanan</h1>
            <p>Kelola dan pantau laporan sampah bulanan bank sampah Anda</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('bank-sampah.laporan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Laporan Baru</span>
            </a>
        </div>
    </div>

    {{-- ======================================================
        FILTER DATA
    ====================================================== --}}
    <div class="filter-section">
        <form method="GET"
              action="{{ route('bank-sampah.laporan.index') }}"
              class="filter-form mb-3">

            <div class="row g-3">

                <div class="col-md-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}"
                                {{ request('tahun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu_verifikasi"
                            {{ request('status')=='menunggu_verifikasi'?'selected':'' }}>
                            Menunggu Verifikasi
                        </option>
                        <option value="disetujui"
                            {{ request('status')=='disetujui'?'selected':'' }}>
                            Disetujui
                        </option>
                        <option value="perlu_perbaikan"
                            {{ request('status')=='perlu_perbaikan'?'selected':'' }}>
                            Perlu Perbaikan
                        </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text"
                           name="search"
                           id="search"
                           class="form-control"
                           placeholder="Cari berdasarkan periode..."
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('bank-sampah.laporan.index') }}"
                           class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>

            </div>
        </form>


        <div class="table-header">
            <h3>Daftar Laporan</h3>
            <div class="table-info">
                Menampilkan {{ $laporans->firstItem() ?? 0 }}
                – {{ $laporans->lastItem() ?? 0 }}
                dari {{ $laporans->total() }} laporan
            </div>
        </div>

        @if($laporans->count())
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Periode</th>
                            <th class="text-end">Sampah Masuk (kg)</th>
                            <th class="text-end">Sampah Terkelola (kg)</th>
                            <th class="text-end">Nasabah</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $laporan)
                        <tr>
                            <td>{{ ($laporans->currentPage()-1)*$laporans->perPage()+$loop->iteration }}</td>

                            <td>
                                <strong>{{ $laporan->periode->translatedFormat('F Y') }}</strong><br>
                                <small class="text-muted">
                                    Dibuat: {{ $laporan->created_at->translatedFormat('d/m/Y') }}
                                </small>
                            </td>

                            <td class="text-end">
                                {{ $fmt($laporan->jumlah_sampah_masuk) }}
                            </td>

                            <td class="text-end">
                                {{ $fmt($laporan->jumlah_sampah_terkelola) }}
                            </td>

                            <td class="text-end">
                                {{ $laporan->jumlah_nasabah }}
                            </td>

                            <td>
                                <span class="status-badge status-{{ $laporan->status }}">
                                    {{ ucfirst(str_replace('_',' ',$laporan->status)) }}
                                </span>

                                @if($laporan->catatan_verifikasi && $laporan->status=='perlu_perbaikan')
                                    <br>
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-circle"></i> Ada catatan
                                    </small>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="{{ route('bank-sampah.laporan.show',$laporan->id) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($laporan->status !== 'disetujui')
                                        <a href="{{ route('bank-sampah.laporan.edit',$laporan->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    <a href="{{ route('bank-sampah.download.laporan',$laporan->id) }}"
                                       class="btn btn-sm btn-danger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-section">
                {{ $laporans->links('components.pagination') }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h4>Belum ada laporan</h4>
                <p>Mulai dengan membuat laporan pertama Anda</p>
                <a href="{{ route('bank-sampah.laporan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Laporan
                </a>
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('tahun')?.addEventListener('change', e => e.target.form.submit());
    document.getElementById('status')?.addEventListener('change', e => e.target.form.submit());
});
</script>
@endsection