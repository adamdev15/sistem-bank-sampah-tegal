@extends('layouts.admin')

@section('title', 'Master Bank Sampah')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Bank Sampah</h5>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bank Sampah</th>
                    <th>Alamat</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bankSampah as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_bank_sampah }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-danger btn-sm btn-delete"
                            data-form-id="delete-form-{{ $item->id }}"
                            data-message="Yakin ingin menghapus bank sampah {{ $item->nama_bank_sampah }}?"
                        >
                            Hapus
                        </button>

                        <form
                            id="delete-form-{{ $item->id }}"
                            action="{{ route('admin.bank-sampah.destroy', $item->id) }}"
                            method="POST"
                            class="d-none"
                        >
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL KONFIRMASI -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p id="deleteMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let deleteFormId = null;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));

    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            deleteFormId = this.dataset.formId;
            document.getElementById('deleteMessage').innerText = this.dataset.message;
            modal.show();
        });
    });

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteFormId) {
            document.getElementById(deleteFormId).submit();
        }
    });
});
</script>
@endpush
