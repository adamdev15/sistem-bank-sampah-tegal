@extends('layouts.admin')

@section('page-title', 'Data Kecamatan')
@section('breadcrumb', 'Data Wilayah / Kecamatan')

@section('content-body')
<div class="verification-layout">
    <div class="verification-stats">
        <div class="verify-stat-card total">
            <div class="verify-icon"><i class="fas fa-map-marked-alt"></i></div>
            <div><h3>{{ $kecamatans->count() }}</h3><p>Total Kecamatan</p></div>
        </div>
        <div class="verify-stat-card approved">
            <div class="verify-icon"><i class="fas fa-city"></i></div>
            <div><h3>{{ $kecamatans->sum('kelurahans_count') }}</h3><p>Total Kelurahan</p></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm verify-card-shell">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2 text-success"></i>Data Kecamatan</h5>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createKecamatanModal">
                    <i class="fas fa-plus me-1"></i>Tambah Kecamatan
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle modern-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px;">No</th>
                            <th>Nama Kecamatan</th>
                            <th style="width:180px;">Jumlah Kelurahan</th>
                            <th style="width:160px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kecamatans as $idx => $kecamatan)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="fw-semibold">{{ $kecamatan->nama_kecamatan }}</td>
                            <td><span class="badge bg-success-subtle text-success-emphasis">{{ $kecamatan->kelurahans_count }} kelurahan</span></td>
                            <td class="text-center action-column">
                                <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editKecamatanModal{{ $kecamatan->id }}"><i class="fas fa-edit"></i></button>
                                <button class="btn-delete js-delete-wilayah" data-delete-url="{{ route('admin.wilayah.kecamatan.destroy', $kecamatan) }}" data-delete-label="{{ $kecamatan->nama_kecamatan }}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editKecamatanModal{{ $kecamatan->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kecamatan</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.wilayah.kecamatan.update', $kecamatan) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <label class="form-label">Nama Kecamatan</label>
                                            <input type="text" name="nama_kecamatan" class="form-control" value="{{ $kecamatan->nama_kecamatan }}" required>
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
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data kecamatan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm verify-card-shell">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-map-pin me-2 text-success"></i>Data Kelurahan</h5>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createKelurahanModal">
                    <i class="fas fa-plus me-1"></i>Tambah Kelurahan
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle modern-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px;">No</th>
                            <th>Nama Kelurahan</th>
                            <th>Kecamatan</th>
                            <th style="width:160px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelurahans as $idx => $kelurahan)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="fw-semibold">{{ $kelurahan->nama_kelurahan }}</td>
                            <td>{{ $kelurahan->kecamatan->nama_kecamatan ?? '-' }}</td>
                            <td class="text-center action-column">
                                <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editKelurahanModal{{ $kelurahan->id }}"><i class="fas fa-edit"></i></button>
                                <button class="btn-delete js-delete-kelurahan" data-delete-url="{{ route('admin.wilayah.kelurahan.destroy', $kelurahan) }}" data-delete-label="{{ $kelurahan->nama_kelurahan }}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <div class="modal fade" id="editKelurahanModal{{ $kelurahan->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kelurahan</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.wilayah.kelurahan.update', $kelurahan) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Kecamatan</label>
                                                <select name="kecamatan_id" class="form-select" required>
                                                    @foreach($kecamatans as $kecamatan)
                                                        <option value="{{ $kecamatan->id }}" {{ $kelurahan->kecamatan_id == $kecamatan->id ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label class="form-label">Nama Kelurahan</label>
                                            <input type="text" name="nama_kelurahan" class="form-control" value="{{ $kelurahan->nama_kelurahan }}" required>
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
                        <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data kelurahan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createKecamatanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kecamatan</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.wilayah.kecamatan.store') }}">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Nama Kecamatan</label>
                    <input type="text" name="nama_kecamatan" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="createKelurahanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kelurahan</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.wilayah.kelurahan.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <select name="kecamatan_id" class="form-select" required>
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="form-label">Nama Kelurahan</label>
                    <input type="text" name="nama_kelurahan" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="wilayahDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-delete-wilayah').forEach(btn => {
        btn.addEventListener('click', function () {
            const deleteUrl = this.dataset.deleteUrl;
            const label = this.dataset.deleteLabel;
            Swal.fire({
                title: 'Hapus data wilayah?',
                html: `Data <b>${label}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#c62828'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('wilayahDeleteForm');
                    form.action = deleteUrl;
                    form.submit();
                }
            });
        });
    });

    document.querySelectorAll('.js-delete-kelurahan').forEach(btn => {
        btn.addEventListener('click', function () {
            const deleteUrl = this.dataset.deleteUrl;
            const label = this.dataset.deleteLabel;
            Swal.fire({
                title: 'Hapus data kelurahan?',
                html: `Data <b>${label}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#c62828'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('wilayahDeleteForm');
                    form.action = deleteUrl;
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
