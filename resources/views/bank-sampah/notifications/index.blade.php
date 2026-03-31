@extends('layouts.bank-sampah')

@section('page-title', 'Notifikasi')
@section('breadcrumb', 'Notifikasi')

@section('content-body')
<div class="notifications-container">
    <div class="notifications-header">
        <h2><i class="fas fa-bell"></i> Notifikasi</h2>
        <p>Pemberitahuan dan informasi penting untuk Anda</p>
    </div>

    <div class="notifications-list">
        @forelse($notifications as $notification)
        <div class="notification-item">
            <div class="notification-icon">
                <i class="fas fa-info-circle text-{{ $notification['type'] }}"></i>
            </div>
            <div class="notification-content">
                <p class="notification-message">{{ $notification['message'] }}</p>
                <small class="notification-time">{{ $notification['time'] }}</small>
            </div>
        </div>
        @empty
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Tidak ada notifikasi baru.
        </div>
        @endforelse
    </div>
</div>

<style>
.notifications-container {
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
}

.notifications-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #27ae60;
}

.notifications-header h2 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.notifications-header p {
    color: #7f8c8d;
    font-size: 16px;
}

.notifications-list {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.notification-item {
    display: flex;
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
    transition: background 0.3s;
}

.notification-item:hover {
    background: #f8f9fa;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-icon {
    margin-right: 15px;
    font-size: 20px;
}

.notification-content {
    flex: 1;
}

.notification-message {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 14px;
}

.notification-time {
    color: #7f8c8d;
    font-size: 12px;
}

.text-info { color: #17a2b8; }
.text-success { color: #28a745; }
.text-warning { color: #ffc107; }
.text-danger { color: #dc3545; }
</style>
@endsection