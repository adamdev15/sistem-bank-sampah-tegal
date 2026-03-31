@extends('layouts.bank-sampah')

@section('page-title', 'Preview Laporan')
@section('breadcrumb', 'Download / Preview Laporan')

@section('content-body')
<div class="preview-container">
    <div class="preview-header">
        <h2><i class="fas fa-eye"></i> Preview Laporan</h2>
        <p>Periode: {{ $laporan->periode->translatedFormat('F Y') }}</p>
        
        <div class="preview-actions">
            <a href="{{ route('bank-sampah.download.laporan', $laporan) }}" 
               class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
            <a href="{{ route('bank-sampah.download.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="preview-content">
        <!-- Embed PDF preview -->
        <iframe src="{{ route('bank-sampah.download.laporan', $laporan) }}" 
                width="100%" height="800px" style="border: 1px solid #ddd;">
            Browser Anda tidak mendukung preview PDF. 
            <a href="{{ route('bank-sampah.download.laporan', $laporan) }}">Download file PDF</a>
        </iframe>
    </div>
    
    <div class="preview-info">
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Nama Bank Sampah:</span>
                <span class="value">{{ $laporan->bankSampahMaster->nama_bank_sampah }}</span>
            </div>
            <div class="info-item">
                <span class="label">Periode:</span>
                <span class="value">{{ $laporan->periode->translatedFormat('F Y') }}</span>
            </div>
            <div class="info-item">
                <span class="label">Sampah Masuk:</span>
                <span class="value">{{ number_format($laporan->jumlah_sampah_masuk, 0, ',', '.') }} Kg</span>
            </div>
            <div class="info-item">
                <span class="label">Sampah Terkelola:</span>
                <span class="value">{{ number_format($laporan->jumlah_sampah_terkelola, 0, ',', '.') }} Kg</span>
            </div>
            <div class="info-item">
                <span class="label">Status:</span>
                <span class="value">
                    <span class="status-badge status-{{ $laporan->status }}">
                        {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                    </span>
                </span>
            </div>
        </div>
    </div>
</div>

<style>
.preview-container {
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #27ae60;
}

.preview-header h2 {
    color: #2c3e50;
    margin: 0;
}

.preview-header p {
    color: #7f8c8d;
    margin: 5px 0 0 0;
}

.preview-actions {
    display: flex;
    gap: 10px;
}

.preview-content {
    margin: 20px 0;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    overflow: hidden;
}

.preview-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-top: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e0e0e0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    color: #7f8c8d;
    font-weight: 500;
}

.info-item .value {
    color: #2c3e50;
    font-weight: 600;
}
</style>
@endsection