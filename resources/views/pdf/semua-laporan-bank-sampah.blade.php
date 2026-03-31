<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Semua Laporan Bank Sampah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #2c3e50;
        }
        .header h2 {
            margin: 3px 0;
            font-size: 14px;
            color: #3498db;
        }
        .header p {
            margin: 2px 0;
            color: #7f8c8d;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            page-break-inside: auto;
        }
        .table th {
            background: #2c3e50;
            color: white;
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        .table td {
            padding: 4px 6px;
            border: 1px solid #ddd;
            font-size: 9px;
            page-break-inside: avoid;
        }
        .table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .table tr {
            page-break-inside: avoid;
        }
        .summary {
            background: #e8f4fc;
            border: 1px solid #3498db;
            padding: 10px;
            border-radius: 3px;
            margin: 15px 0;
            page-break-inside: avoid;
        }
        .summary h3 {
            margin: 0 0 5px 0;
            color: #2980b9;
            font-size: 12px;
            border-bottom: 1px solid #3498db;
            padding-bottom: 3px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 8px;
        }
        .summary-item {
            text-align: center;
            padding: 8px;
            background: white;
            border-radius: 2px;
            border: 1px solid #ddd;
        }
        .summary-item .label {
            font-size: 8px;
            color: #7f8c8d;
            margin-bottom: 3px;
            display: block;
        }
        .summary-item .value {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
            display: block;
        }
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #7f8c8d;
            font-size: 8px;
        }
        .bank-info {
            margin: 10px 0;
            padding: 8px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 3px;
            page-break-before: always;
        }
        .bank-info h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
            color: #2c3e50;
        }
        .page-break {
            page-break-before: always;
        }
        .status-badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 8px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-disetujui { background: #d4edda; color: #155724; }
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-perbaikan { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    @php
        $bankSampah = $laporans->first()->bankSampahMaster ?? null;
        $totalSampahMasuk = $laporans->sum('jumlah_sampah_masuk');
        $totalSampahTerkelola = $laporans->sum('jumlah_sampah_terkelola');
        $totalNasabah = $laporans->sum('jumlah_nasabah');
        $persentase = $totalSampahMasuk > 0 ? ($totalSampahTerkelola / $totalSampahMasuk) * 100 : 0;
    @endphp

    <div class="header">
        <h1>REKAPITULASI SEMUA LAPORAN BANK SAMPAH</h1>
        <h2>{{ $bankSampah->nama_bank_sampah ?? 'Bank Sampah' }}</h2>
        <p>Kecamatan: {{ $bankSampah->kecamatan->nama_kecamatan ?? '-' }} | 
           Kelurahan: {{ $bankSampah->kelurahan->nama_kelurahan ?? '-' }} | 
           RW: {{ $bankSampah->rw ?? '-' }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }} | Jumlah Laporan: {{ $laporans->count() }}</p>
    </div>

    <div class="summary">
        <h3>Total Keseluruhan</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="label">Total Sampah Masuk</span>
                <span class="value">{{ number_format($totalSampahMasuk, 0, ',', '.') }} Kg</span>
            </div>
            <div class="summary-item">
                <span class="label">Total Sampah Terkelola</span>
                <span class="value">{{ number_format($totalSampahTerkelola, 0, ',', '.') }} Kg</span>
            </div>
            <div class="summary-item">
                <span class="label">Rata-rata Terkelola</span>
                <span class="value">{{ number_format($persentase, 1) }}%</span>
            </div>
            <div class="summary-item">
                <span class="label">Rata-rata Nasabah</span>
                <span class="value">{{ number_format($laporans->avg('jumlah_nasabah'), 0) }} Orang</span>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Periode</th>
                <th>Sampah Masuk (Kg)</th>
                <th>Sampah Terkelola (Kg)</th>
                <th>%</th>
                <th>Nasabah</th>
                <th>Status</th>
                <th>Plastik Keras</th>
                <th>Plastik Fleksibel</th>
                <th>Kertas/Karton</th>
                <th>Logam</th>
                <th>Kaca</th>
                <th>Karet/Kulit</th>
                <th>Kain/Tekstil</th>
                <th>Lainnya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
                @php
                    $details = [];
                    foreach ($laporan->details as $detail) {
                        $details[$detail->jenis_sampah] = $detail->jumlah;
                    }
                    
                    $persentaseLaporan = $laporan->jumlah_sampah_masuk > 0 
                        ? ($laporan->jumlah_sampah_terkelola / $laporan->jumlah_sampah_masuk) * 100 
                        : 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $laporan->periode->format('M Y') }}</td>
                    <td>{{ number_format($laporan->jumlah_sampah_masuk, 0, ',', '.') }}</td>
                    <td>{{ number_format($laporan->jumlah_sampah_terkelola, 0, ',', '.') }}</td>
                    <td>{{ number_format($persentaseLaporan, 1) }}%</td>
                    <td>{{ $laporan->jumlah_nasabah }}</td>
                    <td>
                        <span class="status-badge status-{{ $laporan->status }}">
                            {{ substr(strtoupper(str_replace('_', ' ', $laporan->status)), 0, 1) }}
                        </span>
                    </td>
                    <td>{{ number_format($details['plastik_keras'] ?? 0, 0) }}</td>
                    <td>{{ number_format($details['plastik_fleksibel'] ?? 0, 0) }}</td>
                    <td>{{ number_format($details['kertas_karton'] ?? 0, 0) }}</td>
                    <td>{{ number_format($details['logam'] ?? 0, 0) }}</td>
                    <td>{{ number_format($details['kaca'] ?? 0, 0) }}</td>
                    <td>{{ number_format($details['karet_kulit'] ?? 0, 0) }}</td>
                    <td>{{ number_format($details['kain_tekstil'] ?? 0, 0) }}</td>
                    <td>{{ number_format($details['lainnya'] ?? 0, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem BASMAN Kota Tegal</p>
        <p>Dinas Lingkungan Hidup Kota Tegal</p>
    </div>
</body>
</html>