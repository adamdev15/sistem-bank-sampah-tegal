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

            {{-- ✅ BUTTON EXPORT --}}
            <button class="btn-primary" onclick="exportData()">
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
                            <a href="{{ route('admin.bank-sampah.show', $op->bankSampahMaster->id) }}"
                               class="btn-view">
                                <i class="fas fa-eye"></i>
                            </a>
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

{{-- ================= SCRIPT EXPORT ================= --}}
<script>
function exportData() {
    document.getElementById('exportKecamatanId').value =
        document.querySelector('[name="kecamatan_id"]').value;

    document.getElementById('exportSearch').value =
        document.querySelector('[name="search"]').value;

    document.getElementById('exportForm').submit();
}
</script>
@endsection
