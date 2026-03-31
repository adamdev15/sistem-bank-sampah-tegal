@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')
@section('breadcrumb', 'Dashboard')

@section('content-body')
@php
    $activePercent = $totalBankSampah > 0 ? round(($totalBankSampahAktif / $totalBankSampah) * 100) : 0;
    $sampahProgress = min(100, (int) round($totalSampah / 100));
    $pendingPercent = $totalBankSampah > 0 ? min(100, round(($pendingUsers / $totalBankSampah) * 100)) : 0;
@endphp

<div class="dashboard">

    <section class="dashboard-hero">
        <div class="hero-main">
            <h2>Ringkasan Kinerja BASMAN</h2>
            <p>Pantau status bank sampah, progres laporan, dan aktivitas verifikasi dalam satu panel.</p>
            <div class="hero-meta">
                <span><i class="fas fa-calendar-alt"></i> {{ now()->translatedFormat('l, d F Y') }}</span>
                <span><i class="fas fa-clock"></i> Update: {{ now()->format('H:i') }}</span>
            </div>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-light">
                <i class="fas fa-file-alt me-1"></i> Verifikasi Laporan
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-light">
                <i class="fas fa-users me-1"></i> Verifikasi Akun
            </a>
        </div>
    </section>

    <section class="dashboard-stats">
        <div class="stats-grid">
            <div class="verify-stat-card total dashboard-stat-card">
                <div class="verify-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalBankSampah }}</h3>
                    <p>Total Bank Sampah</p>
                    <div class="stat-progress">
                        <span class="progress-label">Cakupan data</span>
                        <div class="progress mini-progress" role="progressbar" aria-label="Cakupan data">
                            <div class="progress-bar bg-success" style="width: 100%;">100%</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="verify-stat-card approved dashboard-stat-card">
                <div class="verify-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalBankSampahAktif }}</h3>
                    <p>Bank Sampah Aktif</p>
                    <div class="stat-progress">
                        <span class="progress-label">Persentase aktif</span>
                        <div class="progress mini-progress" role="progressbar" aria-label="Persentase aktif">
                            <div class="progress-bar bg-success" style="width: {{ $activePercent }}%;">{{ $activePercent }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="verify-stat-card waiting dashboard-stat-card">
                <div class="verify-icon">
                    <i class="fas fa-recycle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($totalSampah, 0, ',', '.') }} Kg</h3>
                    <p>Sampah Terkelola {{ date('Y') }}</p>
                    <div class="stat-progress">
                        <span class="progress-label">Target tahunan</span>
                        <div class="progress mini-progress" role="progressbar" aria-label="Target tahunan">
                            <div class="progress-bar bg-warning text-dark" style="width: {{ $sampahProgress }}%;">{{ $sampahProgress }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="verify-stat-card revision dashboard-stat-card">
                <div class="verify-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $pendingUsers }}</h3>
                    <p>Akun Menunggu Verifikasi</p>
                    <div class="stat-progress">
                        <span class="progress-label">Beban verifikasi</span>
                        <div class="progress mini-progress" role="progressbar" aria-label="Beban verifikasi">
                            <div class="progress-bar bg-danger" style="width: {{ $pendingPercent }}%;">{{ $pendingPercent }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard-charts">
        <div class="charts-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <h3>
                        <i class="fas fa-map-marked-alt me-2 text-success"></i>
                        Bank Sampah per Kecamatan
                    </h3>
                    <small class="text-muted">Distribusi kontribusi per wilayah</small>
                </div>
                <div class="chart-body">
                    <canvas id="kecamatanChart"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h3>
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Laporan per Bulan ({{ date('Y') }})
                    </h3>
                    <small class="text-muted">Tren pengiriman laporan bulanan</small>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <section class="dashboard-table">
        <div class="table-container">
            <div class="table-header">
                <h3>
                    <i class="fas fa-exclamation-circle me-2 text-warning"></i>
                    Bank Sampah Belum Melapor Bulan Ini
                </h3>
                <span class="badge">{{ $belumMelapor }}</span>
            </div>

            <div class="table-body">
                <div class="table-responsive">
                    <table class="data-table modern-table">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Bank Sampah</th>
                                <th>Kecamatan</th>
                                <th>Kelurahan</th>
                                <th width="80">RW</th>
                                <th width="100" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $belumMelaporList = \App\Models\BankSampahMaster::whereHas('user', function($q) {
                                    $q->where('status', 'aktif');
                                })
                                ->whereDoesntHave('laporans', function($q) {
                                    $q->whereMonth('periode', now()->month)
                                      ->whereYear('periode', now()->year);
                                })
                                ->limit(10)
                                ->get();
                            @endphp

                            @forelse($belumMelaporList as $index => $bank)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="table-bank-name">
                                        <i class="fas fa-recycle text-success me-2"></i>
                                        <strong>{{ $bank->nama_bank_sampah }}</strong>
                                    </div>
                                </td>
                                <td>{{ $bank->kecamatan->nama_kecamatan }}</td>
                                <td>{{ $bank->kelurahan->nama_kelurahan }}</td>
                                <td><span class="badge bg-success-subtle text-success-emphasis">RW {{ $bank->rw }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('admin.bank-sampah.show', $bank->id) }}" class="btn-view" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    Semua bank sampah sudah melapor
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
const kecamatanLabels = @json($kecamatans->pluck('nama_kecamatan'));
const kecamatanValues = @json($kecamatans->pluck('bank_sampah_masters_count'));
const kecamatanColors = kecamatanLabels.map((name) => {
    const n = String(name).toLowerCase();
    if (n.includes('tegal timur')) return '#facc15';
    if (n.includes('tegal barat')) return '#22c55e';
    if (n.includes('tegal selatan')) return '#16a34a';
    if (n.includes('margadana')) return '#15803d';
    return '#4ade80';
});

const kecamatanData = {
    labels: kecamatanLabels,
    datasets: [{
        label: 'Jumlah Bank Sampah',
        data: kecamatanValues,
        backgroundColor: kecamatanColors,
        borderWidth: 0
    }]
};

const bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const monthlyData = {
    labels: bulanLabels,
    datasets: [{
        label: 'Jumlah Laporan',
        data: @json(array_values($monthlyReports)),
        borderColor: '#2f7d5a',
        backgroundColor: 'rgba(46,125,50,0.15)',
        fill: true,
        tension: 0.4
    }]
};

document.addEventListener('DOMContentLoaded', function() {
    if (window.ChartDataLabels) {
        Chart.register(ChartDataLabels);
    }
    const totalKecamatan = kecamatanValues.reduce((acc, val) => acc + val, 0);

    new Chart(document.getElementById('kecamatanChart'), {
        type: 'doughnut',
        data: kecamatanData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '58%',
            plugins: {
                legend: { position: 'bottom' },
                datalabels: {
                    color: '#1f3d2b',
                    font: { weight: '700', size: 10 },
                    formatter: (value) => {
                        if (!totalKecamatan) return '0';
                        const p = Math.round((value / totalKecamatan) * 100);
                        return `${value} (${p}%)`;
                    },
                    anchor: 'end',
                    align: 'end',
                    offset: 2,
                    clamp: true
                }
            }
        }
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: monthlyData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                datalabels: {
                    align: 'top',
                    anchor: 'end',
                    color: '#1f3d2b',
                    formatter: (value) => value,
                    font: { size: 10, weight: '600' }
                }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
});
</script>

@endsection
