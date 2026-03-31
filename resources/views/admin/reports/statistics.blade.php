@extends('layouts.admin')

@section('page-title', 'Statistik & Laporan')
@section('breadcrumb', 'Reports / Statistik')

@section('content-body')
<div class="reports-container">
    <div class="reports-header">
        <h2><i class="fas fa-chart-bar"></i> Statistik Sistem BASMAN</h2>
        
        <!-- Year Filter -->
        <form method="GET" action="{{ route('admin.reports.statistics') }}" class="year-filter">
            <label for="year">Tahun:</label>
            <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon" style="background: #3498db;">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_bank_sampah'] }}</h3>
                <p>Total Bank Sampah</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #2ecc71;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['bank_sampah_aktif'] }}</h3>
                <p>Bank Sampah Aktif</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #f39c12;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['bank_sampah_belum_aktif'] }}</h3>
                <p>Menunggu Verifikasi</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-card">
            <div class="chart-header">
                <h3>Laporan per Bulan ({{ $year }})</h3>
            </div>
            <div class="chart-body">
                <canvas id="monthlyChart" height="250"></canvas>
            </div>
        </div>
        
        <div class="chart-card">
            <div class="chart-header">
                <h3>Bank Sampah per Kecamatan</h3>
            </div>
            <div class="chart-body">
                <canvas id="kecamatanChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="data-tables">
        <div class="table-card">
            <div class="table-header">
                <h3>Data Kecamatan</h3>
            </div>
            <div class="table-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kecamatan</th>
                            <th>Jumlah Bank Sampah</th>
                            <th>Bank Sampah Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kecamatanData as $kecamatan)
                        <tr>
                            <td>{{ $kecamatan->nama_kecamatan }}</td>
                            <td>{{ $kecamatan->bank_sampah_masters_count }}</td>
                            <td>
                                {{ $kecamatan->bank_sampah_masters_count }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyLabels = @json(array_column($monthlyData, 'month'));
    const monthlyReportData = @json(array_column($monthlyData, 'laporan_count'));
    const monthlyWasteData = @json(array_column($monthlyData, 'sampah_terkelola'));
    
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Jumlah Laporan',
                data: monthlyReportData,
                backgroundColor: '#3498db',
                borderColor: '#2980b9',
                borderWidth: 1
            }, {
                label: 'Sampah Terkelola (Kg)',
                data: monthlyWasteData,
                backgroundColor: '#2ecc71',
                borderColor: '#27ae60',
                borderWidth: 1,
                type: 'line',
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Laporan'
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Sampah Terkelola (Kg)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Kecamatan Chart
    const kecamatanCtx = document.getElementById('kecamatanChart').getContext('2d');
    const kecamatanLabels = @json($kecamatanData->pluck('nama_kecamatan'));
    const kecamatanData = @json($kecamatanData->pluck('bank_sampah_masters_count'));
    
    new Chart(kecamatanCtx, {
        type: 'doughnut',
        data: {
            labels: kecamatanLabels,
            datasets: [{
                data: kecamatanData,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
});
</script>

<style>
.reports-container {
    padding: 20px;
}

.reports-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #3498db;
}

.reports-header h2 {
    color: #2c3e50;
    margin: 0;
}

.year-filter {
    display: flex;
    align-items: center;
    gap: 10px;
}

.year-filter .form-select {
    width: 120px;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stat-info h3 {
    margin: 0;
    font-size: 28px;
    color: #2c3e50;
}

.stat-info p {
    margin: 5px 0 0 0;
    color: #7f8c8d;
}

.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.chart-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chart-header {
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
}

.chart-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 16px;
}

.chart-body {
    padding: 20px;
}

.data-tables {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.table-card {
    padding: 20px;
}

.table-header {
    margin-bottom: 15px;
}

.table-header h3 {
    color: #2c3e50;
    margin: 0 0 10px 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #e0e0e0;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #2c3e50;
    color: white;
    border: none;
}

.table td {
    vertical-align: middle;
}
</style>
@endsection