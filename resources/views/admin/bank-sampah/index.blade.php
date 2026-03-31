@extends('layouts.admin')

@section('page-title', 'Data Master Bank Sampah')
@section('breadcrumb', 'Bank Sampah / Data Master')

@section('content-body')
<div class="data-container modern-master-wrap">

    <div class="filter-section card shadow-sm border-0 mb-3">
        
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="mb-0 fw-semibold">Daftar Bank Sampah</h6>
            <small class="text-muted">Kelola data master secara cepat lewat popup modal.</small>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createBankModal">
            <i class="fas fa-plus me-1"></i> Tambah Bank Sampah
        </button>
    </div>

            <form method="GET" action="{{ route('admin.bank-sampah.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
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
                        <select name="status_terbentuk" class="form-select">
                            <option value="">Semua</option>
                            <option value="Sudah" {{ request('status_terbentuk')=='Sudah'?'selected':'' }}>Sudah</option>
                            <option value="Belum" {{ request('status_terbentuk')=='Belum'?'selected':'' }}>Belum</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nama bank / direktur / SK">
                    </div>

                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-filter me-1"></i>Filter</button>
                        <a href="{{ route('admin.bank-sampah.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>

    <div class="border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 master-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bank</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>RW</th>
                            <th>Direktur</th>
                            <th>No HP</th>
                            <th>Status</th>
                            <th>Akun</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($bankSampahs as $index => $bank)
                        <tr>
                            <td>{{ ($bankSampahs->currentPage()-1)*$bankSampahs->perPage()+$index+1 }}</td>
                            <td class="fw-semibold">{{ $bank->nama_bank_sampah }}</td>
                            <td>{{ $bank->kecamatan->nama_kecamatan }}</td>
                            <td>{{ $bank->kelurahan->nama_kelurahan }}</td>
                            <td><span class="badge bg-success-subtle text-success-emphasis">RW {{ $bank->rw }}</span></td>
                            <td>{{ $bank->nama_direktur }}</td>
                            <td>{{ $bank->nomor_hp }}</td>
                            <td>{{ $bank->status_terbentuk }}</td>
                            <td>
                                @if($bank->user)
                                    <span class="status-badge status-active">Aktif</span>
                                @else
                                    <span class="status-badge status-none">Belum</span>
                                @endif
                            </td>
                            <td class="text-center action-column">
                                <a href="{{ route('admin.bank-sampah.show', $bank->id) }}" class="btn-view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @php
                                    $bankPayload = [
                                        'id' => $bank->id,
                                        'nama_bank_sampah' => $bank->nama_bank_sampah,
                                        'nomor_sk' => $bank->nomor_sk,
                                        'status_terbentuk' => $bank->status_terbentuk,
                                        'keterangan' => $bank->keterangan,
                                        'kecamatan_id' => $bank->kecamatan_id,
                                        'kelurahan_id' => $bank->kelurahan_id,
                                        'rw' => $bank->rw,
                                        'nama_direktur' => $bank->nama_direktur,
                                        'nomor_hp' => $bank->nomor_hp,
                                        'update_url' => route('admin.bank-sampah.update', $bank->id),
                                    ];
                                @endphp

                                <button type="button"
                                        class="btn-edit"
                                        title="Edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBankModal"
                                        data-bank='@json($bankPayload)'>
                                    <i class="fas fa-edit"></i>
                                </button>

                                @if(!$bank->user)
                                    <button type="button"
                                            class="btn-delete"
                                            title="Hapus"
                                            data-delete-action="true"
                                            data-url="{{ route('admin.bank-sampah.destroy', $bank->id) }}"
                                            data-name="{{ $bank->nama_bank_sampah }}"
                                            data-type="Bank Sampah">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn-delete disabled" title="Tidak dapat menghapus bank sampah yang sudah memiliki akun" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">{{ $bankSampahs->links() }}</div>
        </div>
    </div>
</div>

<form id="global-delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<div class="modal fade" id="createBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2 text-white"></i>Tambah Bank Sampah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.bank-sampah.store') }}">
                @csrf
                <div class="modal-body">
                    @include('admin.bank-sampah.partials.form-fields', ['prefix' => 'create_'])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2 text-white"></i>Edit Bank Sampah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editBankForm" action="#">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @include('admin.bank-sampah.partials.form-fields', ['prefix' => 'edit_'])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function bindKelurahan(prefix) {
    const kecamatan = document.getElementById(prefix + 'kecamatan_id');
    const kelurahan = document.getElementById(prefix + 'kelurahan_id');
    if (!kecamatan || !kelurahan) return;

    kecamatan.addEventListener('change', function () {
        if (!this.value) {
            kelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';
            return;
        }

        fetch(`/api/kelurahan/${this.value}`)
            .then(res => res.json())
            .then(items => {
                kelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';
                items.forEach(item => {
                    kelurahan.insertAdjacentHTML('beforeend', `<option value="${item.id}">${item.nama_kelurahan}</option>`);
                });
            });
    });
}

function fillEditModal(bank) {
    document.getElementById('editBankForm').action = bank.update_url;
    document.getElementById('edit_nama_bank_sampah').value = bank.nama_bank_sampah || '';
    document.getElementById('edit_nomor_sk').value = bank.nomor_sk || '';
    document.getElementById('edit_status_terbentuk').value = bank.status_terbentuk || '';
    document.getElementById('edit_keterangan').value = bank.keterangan || '';
    document.getElementById('edit_rw').value = bank.rw || '';
    document.getElementById('edit_nama_direktur').value = bank.nama_direktur || '';
    document.getElementById('edit_nomor_hp').value = bank.nomor_hp || '';

    const kecamatan = document.getElementById('edit_kecamatan_id');
    const kelurahan = document.getElementById('edit_kelurahan_id');
    kecamatan.value = bank.kecamatan_id || '';
    fetch(`/api/kelurahan/${bank.kecamatan_id}`)
        .then(res => res.json())
        .then(items => {
            kelurahan.innerHTML = '<option value="">Pilih Kelurahan</option>';
            items.forEach(item => {
                kelurahan.insertAdjacentHTML('beforeend', `<option value="${item.id}">${item.nama_kelurahan}</option>`);
            });
            kelurahan.value = bank.kelurahan_id || '';
        });
}

document.addEventListener('DOMContentLoaded', function () {
    bindKelurahan('create_');
    bindKelurahan('edit_');

    document.querySelectorAll('[data-bank]').forEach(btn => {
        btn.addEventListener('click', function () {
            fillEditModal(JSON.parse(this.dataset.bank));
        });
    });

    document.querySelectorAll('[data-delete-action="true"]').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = document.getElementById('global-delete-form');
            form.action = this.dataset.url;

            Swal.fire({
                title: 'Hapus data?',
                html: `Bank sampah <b>${this.dataset.name}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#c62828'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
@endsection
