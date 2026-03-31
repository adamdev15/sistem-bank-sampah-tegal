<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        {!! file_get_contents(public_path('css/pdf-laporan-bulanan.css')) !!}
    </style>
</head>
<body>
    <div class="pdf-header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">BASMAN - Dinas Lingkungan Hidup Kota Tegal</div>
        <div class="pdf-meta">
            <span>Dicetak: {{ $exportDate }}</span>
            <span>Total Data: {{ $totalData }}</span>
            @if($filterKecamatan)
                <span>Kecamatan: {{ $filterKecamatan }}</span>
            @endif
            @if($filterPeriode)
                <span>Periode: {{ $filterPeriode }}</span>
            @endif
        </div>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Nama Bank Sampah</th>
                    <th>Kecamatan</th>
                    <th>Sampah Masuk (kg)</th>
                    <th>Sampah Terkelola (kg)</th>
                    <th>Jumlah Nasabah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    @php
                        $bank = $item->bankSampahMaster;
                        $statusClass = 'status-' . str_replace('_', '', $item->status);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ Carbon\Carbon::parse($item->periode)->translatedFormat('F Y') }}</td>
                        <td>{{ $bank->nama_bank_sampah ?? '-' }}</td>
                        <td>{{ $bank->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td class="text-right">{{ number_format($item->jumlah_sampah_masuk, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->jumlah_sampah_terkelola, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->jumlah_nasabah }}</td>
                        <td class="text-center {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($data->count() > 0)
    <div class="summary">
        <h3>Rincian Jenis Sampah Terkelola</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Bank Sampah</th>
                    <th>Periode</th>
                    <th>Plastik Keras</th>
                    <th>Plastik Fleksibel</th>
                    <th>Kertas/Karton</th>
                    <th>Logam</th>
                    <th>Kaca</th>
                    <th>Karet/Kulit</th>
                    <th>Kain/Tekstil</th>
                    <th>Lainnya</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    @php
                        $bank = $item->bankSampahMaster;
                        $details = $item->details->groupBy('jenis_sampah')->map(function ($group) {
                            return $group->sum('jumlah');
                        });
                    @endphp
                    <tr>
                        <td>{{ $bank->nama_bank_sampah ?? '-' }}</td>
                        <td class="text-center">{{ Carbon\Carbon::parse($item->periode)->format('m/Y') }}</td>
                        <td class="text-right">{{ number_format($details['plastik_keras'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($details['plastik_fleksibel'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($details['kertas_karton'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($details['logam'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($details['kaca'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($details['karet_kulit'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($details['kain_tekstil'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($details['lainnya'] ?? 0, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item->jumlah_sampah_terkelola, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <div class="footer">
        <p>© {{ date('Y') }} - Dinas Lingkungan Hidup Kota Tegal</p>
        <p>Sistem BASMAN - Bank Sampah Management System</p>
    </div>
</body>
</html>