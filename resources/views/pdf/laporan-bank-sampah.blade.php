<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bank Sampah - {{ $laporan->periode->format('F Y') }}</title>
    <style>
        /* PDF Styles */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .header h2 {
            color: #3498db;
            font-size: 18px;
            margin: 0;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 8px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            background: #2c3e50;
            color: white;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th {
            background: #2c3e50;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #34495e;
        }
        
        .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        .table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            color: #7f8c8d;
            font-size: 11px;
        }
        
        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            width: 80%;
            height: 1px;
            background: #333;
            margin: 50px auto 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>BASMAN - BANK SAMPAH MANAGEMENT SYSTEM</h1>
        <h2>LAPORAN BULANAN BANK SAMPAH</h2>
        <p>Dinas Lingkungan Hidup Kota Tegal</p>
    </div>

    <!-- Informasi Laporan -->
    <div class="section">
        <div class="section-title">INFORMASI LAPORAN</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Bank Sampah</div>
                <div class="info-value">{{ $laporan->bankSampahMaster->nama_bank_sampah }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Periode</div>
                <div class="info-value">{{ $laporan->periode->translatedFormat('F Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Dibuat</div>
                <div class="info-value">{{ $laporan->created_at->translatedFormat('d F Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">{{ strtoupper(str_replace('_', ' ', $laporan->status)) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Lokasi</div>
                <div class="info-value">
                    {{ $laporan->bankSampahMaster->kecamatan->nama_kecamatan }}, 
                    {{ $laporan->bankSampahMaster->kelurahan->nama_kelurahan }}, 
                    RW {{ $laporan->bankSampahMaster->rw }}
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="section">
        <div class="section-title">DATA UTAMA</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($laporan->jumlah_sampah_masuk, 2, ',', '.') }} kg</div>
                <div class="stat-label">Sampah Masuk</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($laporan->jumlah_sampah_terkelola, 2, ',', '.') }} kg</div>
                <div class="stat-label">Sampah Terkelola</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $laporan->jumlah_nasabah }} orang</div>
                <div class="stat-label">Jumlah Nasabah</div>
            </div>
            <div class="stat-card">
                @php
                    $percentage = $laporan->jumlah_sampah_masuk > 0 
                        ? ($laporan->jumlah_sampah_terkelola / $laporan->jumlah_sampah_masuk) * 100 
                        : 0;
                @endphp
                <div class="stat-value">{{ number_format($percentage, 1) }}%</div>
                <div class="stat-label">Persentase Terkelola</div>
            </div>
        </div>
    </div>

    <!-- Rincian Jenis Sampah -->
    <div class="section">
        <div class="section-title">RINCIAN JENIS SAMPAH TERKELOLA</div>
        <table class="table">
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
                        {{ number_format($percentage, 1) }}%
                    </td>
                </tr>
                @endforeach
                
                <tr style="font-weight: bold; background: #e8f4fc;">
                    <td colspan="2" style="text-align: right;">TOTAL</td>
                    <td>{{ number_format($total, 2, ',', '.') }} kg</td>
                    <td>100%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Informasi Bank Sampah -->
    <div class="section">
        <div class="section-title">INFORMASI BANK SAMPAH</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Bank Sampah</div>
                <div class="info-value">{{ $laporan->bankSampahMaster->nama_bank_sampah }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nomor SK</div>
                <div class="info-value">{{ $laporan->bankSampahMaster->nomor_sk ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Direktur</div>
                <div class="info-value">{{ $laporan->bankSampahMaster->nama_direktur }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nomor HP</div>
                <div class="info-value">{{ $laporan->bankSampahMaster->nomor_hp }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Terbentuk</div>
                <div class="info-value">{{ $laporan->bankSampahMaster->status_terbentuk }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Keterangan</div>
                <div class="info-value">{{ $laporan->bankSampahMaster->keterangan ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Catatan Verifikasi -->
    @if($laporan->catatan_verifikasi)
    <div class="section">
        <div class="section-title">CATATAN VERIFIKASI</div>
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 4px;">
            <p style="margin: 0; color: #856404;">
                {{ $laporan->catatan_verifikasi }}
            </p>
            <p style="margin: 10px 0 0 0; font-size: 11px; color: #856404;">
                Diupdate: {{ $laporan->updated_at->translatedFormat('d F Y H:i') }}
            </p>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem BASMAN Kota Tegal</p>
        <p>Tanggal cetak: {{ date('d/m/Y H:i') }} | Halaman 1 dari 1</p>
    </div>
</body>
</html>