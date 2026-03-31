@extends('layouts.admin')

@section('page-title', 'Detail Laporan')
@section('breadcrumb', 'Laporan / Detail')

@section('styles')
<link href="{{ asset('css/laporan.css') }}" rel="stylesheet">
@endsection

@section('content-body')
<div class="laporan-container">
    <!-- Header Laporan -->
    <div class="laporan-header">
        <div class="header-info">
            <h2>Detail Laporan Bulanan</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Bank Sampah:</span>
                    <span class="value">{{ $laporan->bankSampahMaster->nama_bank_sampah }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Periode:</span>
                    <span class="value">{{ $laporan->periode->translatedFormat('F Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Status:</span>
                    <span class="status-badge status-{{ str_replace('_', '-', $laporan->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="label">Tanggal Input:</span>
                    <span class="value">{{ $laporan->created_at->translatedFormat('d F Y H:i') }}</span>
                </div>
            </div>
        </div>
        
        <div class="header-actions">
            @if($laporan->status == 'menunggu_verifikasi')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verifyModal">
                    <i class="fas fa-check"></i> Verifikasi Laporan
                </button>
            @endif
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Data Utama -->
    <div class="laporan-section">
        <div class="section-header">
            <h3>Data Utama</h3>
        </div>
        <div class="data-grid">
            <div class="data-card">
                <div class="data-icon" style="background: #3498db;">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <div class="data-info">
                    <h4>{{ number_format($laporan->jumlah_sampah_masuk, 2, ',', '.') }} kg</h4>
                    <p>Sampah Masuk</p>
                </div>
            </div>
            
            <div class="data-card">
                <div class="data-icon" style="background: #2ecc71;">
                    <i class="fas fa-recycle"></i>
                </div>
                <div class="data-info">
                    <h4>{{ number_format($laporan->jumlah_sampah_terkelola, 2, ',', '.') }} kg</h4>
                    <p>Sampah Terkelola</p>
                </div>
            </div>
            
            <div class="data-card">
                <div class="data-icon" style="background: #9b59b6;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="data-info">
                    <h4>{{ $laporan->jumlah_nasabah }} orang</h4>
                    <p>Jumlah Nasabah</p>
                </div>
            </div>
            
            <div class="data-card">
                <div class="data-icon" style="background: #e74c3c;">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="data-info">
                    @php
                        $percentage = $laporan->jumlah_sampah_masuk > 0 
                            ? ($laporan->jumlah_sampah_terkelola / $laporan->jumlah_sampah_masuk) * 100 
                            : 0;
                    @endphp
                    <h4>{{ number_format($percentage, 1) }}%</h4>
                    <p>Persentase Terkelola</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rincian Jenis Sampah -->
    <div class="laporan-section">
        <div class="section-header">
            <h3>Rincian Jenis Sampah Terkelola</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Sampah</th>
                        <th>Jumlah (kg)</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jenisLabels = [
                            'plastik_keras' => 'Plastik Keras',
                            'plastik_fleksibel' => 'Plastik Fleksibel',
                            'kertas_karton' => 'Kertas/Karton',
                            'logam' => 'Logam',
                            'kaca' => 'Kaca',
                            'karet_kulit' => 'Karet/Kulit',
                            'kain_tekstil' => 'Kain/Tekstil',
                            'lainnya' => 'Lainnya'
                        ];
                        $total = $laporan->jumlah_sampah_terkelola;
                    @endphp
                    
                    @foreach($laporan->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $jenisLabels[$detail->jenis_sampah] ?? ucfirst(str_replace('_', ' ', $detail->jenis_sampah)) }}</td>
                        <td>{{ number_format($detail->jumlah, 2, ',', '.') }}</td>
                        <td>
                            @php
                                $percentage = $total > 0 ? ($detail->jumlah / $total) * 100 : 0;
                            @endphp
                            <div class="percentage-bar">
                                <div class="bar-bg">
                                    <div class="bar-fill" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="percentage-text">{{ number_format($percentage, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    
                    <tr class="table-success">
                        <td colspan="2" class="text-end"><strong>TOTAL</strong></td>
                        <td><strong>{{ number_format($total, 2, ',', '.') }} kg</strong></td>
                        <td><strong>100%</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Bank Sampah -->
    <div class="laporan-section">
        <div class="section-header">
            <h3>Informasi Bank Sampah</h3>
        </div>
        <div class="info-grid">
            <div class="info-item">
                <span class="label">Nama Bank Sampah:</span>
                <span class="value">{{ $laporan->bankSampahMaster->nama_bank_sampah }}</span>
            </div>
            <div class="info-item">
                <span class="label">Kecamatan:</span>
                <span class="value">{{ $laporan->bankSampahMaster->kecamatan->nama_kecamatan }}</span>
            </div>
            <div class="info-item">
                <span class="label">Kelurahan:</span>
                <span class="value">{{ $laporan->bankSampahMaster->kelurahan->nama_kelurahan }}</span>
            </div>
            <div class="info-item">
                <span class="label">RW:</span>
                <span class="value">{{ $laporan->bankSampahMaster->rw }}</span>
            </div>
            <div class="info-item">
                <span class="label">Direktur:</span>
                <span class="value">{{ $laporan->bankSampahMaster->nama_direktur }}</span>
            </div>
            <div class="info-item">
                <span class="label">No. HP:</span>
                <span class="value">{{ $laporan->bankSampahMaster->nomor_hp }}</span>
            </div>
        </div>
    </div>

    <!-- Catatan Verifikasi -->
    @if($laporan->catatan_verifikasi || $laporan->status != 'menunggu_verifikasi')
    <div class="laporan-section">
        <div class="section-header">
            <h3>Catatan Verifikasi</h3>
            @if($laporan->status != 'menunggu_verifikasi')
                <span class="badge bg-info">Sudah Diverifikasi</span>
            @endif
        </div>
        <div class="verifikasi-note">
            @if($laporan->catatan_verifikasi)
                <p>{{ $laporan->catatan_verifikasi }}</p>
                <small class="text-muted">
                    Diupdate: {{ $laporan->updated_at->translatedFormat('d F Y H:i') }}
                </small>
            @else
                <p class="text-muted">Tidak ada catatan verifikasi.</p>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Modal Verifikasi -->
@if($laporan->status == 'menunggu_verifikasi')
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.laporan.verify', $laporan) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="status">Status Verifikasi</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="perlu_perbaikan">Perlu Perbaikan</option>
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="catatan_verifikasi">Catatan (Opsional)</label>
                        <textarea class="form-control" id="catatan_verifikasi" 
                                  name="catatan_verifikasi" rows="3" 
                                  placeholder="Berikan catatan jika diperlukan..."></textarea>
                        <small class="text-muted">Catatan akan dikirim ke bank sampah.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
.laporan-header {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
}

.header-info {
    flex: 1;
    min-width: 300px;
}

.header-info h2 {
    margin: 0 0 15px 0;
    color: #2c3e50;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-item .label {
    font-weight: 600;
    color: #7f8c8d;
    font-size: 14px;
    margin-bottom: 5px;
}

.info-item .value {
    color: #2c3e50;
    font-size: 15px;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

/* Laporan Section */
.laporan-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.section-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
}

/* Data Grid */
.data-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.data-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
    border-left: 4px solid;
}

.data-card .data-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.data-card .data-info h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 20px;
}

.data-card .data-info p {
    margin: 0;
    color: #7f8c8d;
    font-size: 14px;
}

/* Percentage Bar */
.percentage-bar {
    display: flex;
    align-items: center;
    gap: 10px;
}

.bar-bg {
    flex: 1;
    height: 8px;
    background: #ecf0f1;
    border-radius: 4px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #3498db, #2ecc71);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.percentage-text {
    min-width: 50px;
    text-align: right;
    font-weight: 600;
    color: #2c3e50;
}

/* Verifikasi Note */
.verifikasi-note {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 15px;
    border-radius: 4px;
}

.verifikasi-note p {
    margin: 0 0 10px 0;
    color: #1976d2;
}

/* Table Styling */
.table {
    margin-bottom: 0;
}

.table thead {
    background: #2c3e50;
    color: white;
}

.table th {
    font-weight: 600;
    padding: 12px 15px;
    border-color: #34495e;
}

.table td {
    padding: 10px 15px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Responsive */
@media (max-width: 768px) {
    .laporan-header {
        flex-direction: column;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .data-grid {
        grid-template-columns: 1fr;
    }
    
    .data-card {
        padding: 12px;
    }
    
    .table-responsive {
        font-size: 14px;
    }
    
    .percentage-bar {
        flex-direction: column;
        gap: 5px;
        align-items: flex-start;
    }
    
    .percentage-text {
        text-align: left;
    }
}
</style>

<script>
// Tampilkan loading saat submit verifikasi
document.addEventListener('DOMContentLoaded', function() {
    const verifyForm = document.querySelector('#verifyModal form');
    if (verifyForm) {
        verifyForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        });
    }
    
    // Dynamic status label
    const statusSelect = document.getElementById('status');
    const catatanTextarea = document.getElementById('catatan_verifikasi');
    
    if (statusSelect && catatanTextarea) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'perlu_perbaikan') {
                catatanTextarea.placeholder = 'Harap jelaskan bagian yang perlu diperbaiki...';
                catatanTextarea.required = true;
            } else {
                catatanTextarea.placeholder = 'Berikan catatan jika diperlukan...';
                catatanTextarea.required = false;
            }
        });
    }
});
</script>
@endsection