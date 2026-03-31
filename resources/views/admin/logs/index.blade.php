@extends('layouts.admin')

@section('page-title', 'Log Aktivitas Sistem')
@section('breadcrumb', 'Log Aktivitas')

@section('content-body')
<div class="logs-container">
    <div class="card">
        <div class="card-header">
            <h3>Log Aktivitas Sistem</h3>
            <p>Catatan semua aktivitas dalam sistem BASMAN</p>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section mb-4">
                <form method="GET" action="{{ route('admin.logs.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date" 
                               value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="modul" class="form-label">Modul</label>
                        <select class="form-control" id="modul" name="modul">
                            <option value="">Semua Modul</option>
                            <option value="Auth" {{ request('modul') == 'Auth' ? 'selected' : '' }}>Auth</option>
                            <option value="Bank Sampah Master" {{ request('modul') == 'Bank Sampah Master' ? 'selected' : '' }}>Bank Sampah</option>
                            <option value="Laporan" {{ request('modul') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="Users" {{ request('modul') == 'Users' ? 'selected' : '' }}>Users</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">User</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">Semua User</option>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->role }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Logs Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Modul</th>
                            <th>Deskripsi</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <small>{{ $log->created_at->format('d/m/Y') }}</small><br>
                                <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                @if($log->user)
                                    {{ $log->user->name }}<br>
                                    <small class="text-muted">{{ $log->user->email }}</small>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>{{ $log->aktivitas }}</td>
                            <td>
                                <span class="badge bg-info">{{ $log->modul }}</span>
                            </td>
                            <td>{{ $log->deskripsi }}</td>
                            <td>
                                <small class="text-muted">{{ $log->ip_address }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada log aktivitas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.logs-container {
    padding: 20px;
}

.filter-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.table th {
    background: #2c3e50;
    color: white;
    border: none;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.8em;
    padding: 4px 8px;
}
</style>
@endsection