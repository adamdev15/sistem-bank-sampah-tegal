<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Operasional - {{ $operasional->bankSampahMaster->nama_bank_sampah }}</title>
    <style>
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
            width: 40%;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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
            font-size: 20px;
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>BASMAN - BANK SAMPAH MANAGEMENT SYSTEM</h1>
        <h2>DATA OPERASIONAL BANK SAMPAH</h2>
        <p>Dinas Lingkungan Hidup Kota Tegal</p>
    </div>

    <!-- Informasi Bank Sampah -->
    <div class="section">
        <div class="section-title">INFORMASI BANK SAMPAH</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Bank Sampah</div>
                <div class="info-value">{{ $operasional->bankSampahMaster->nama_bank_sampah }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nomor SK</div>
                <div class="info-value">{{ $operasional->bankSampahMaster->nomor_sk ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Direktur</div>
                <div class="info-value">{{ $operasional->bankSampahMaster->nama_direktur }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nomor HP</div>
                <div class="info-value">{{ $operasional->bankSampahMaster->nomor_hp }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Lokasi</div>
                <div class="info-value">
                    {{ $operasional->bankSampahMaster->kecamatan->nama_kecamatan }}, 
                    {{ $operasional->bankSampahMaster->kelurahan->nama_kelurahan }}, 
                    RW {{ $operasional->bankSampahMaster->rw }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Terbentuk</div>
                <div class="info-value">{{ $operasional->bankSampahMaster->status_terbentuk }}</div>
            </div>
        </div>
    </div>

    <!-- Tenaga Kerja -->
    <div class="section">
        <div class="section-title">TENAGA KERJA</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $operasional->tenaga_kerja_laki }} orang</div>
                <div class="stat-label">Laki-laki</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $operasional->tenaga_kerja_perempuan }} orang</div>
                <div class="stat-label">Perempuan</div>
            </div>
        </div>
        <div class="stat-card" style="grid-column: span 2; background: #e8f4fc;">
            <div class="stat-value">{{ $operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan }} orang</div>
            <div class="stat-label">Total Tenaga Kerja</div>
        </div>
    </div>

    <!-- Nasabah -->
    <div class="section">
        <div class="section-title">NASAABAH</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $operasional->nasabah_laki }} orang</div>
                <div class="stat-label">Laki-laki</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $operasional->nasabah_perempuan }} orang</div>
                <div class="stat-label">Perempuan</div>
            </div>
        </div>
        <div class="stat-card" style="grid-column: span 2; background: #e8f4fc;">
            <div class="stat-value">{{ $operasional->nasabah_laki + $operasional->nasabah_perempuan }} orang</div>
            <div class="stat-label">Total Nasabah</div>
        </div>
    </div>

    <!-- Omset -->
    <div class="section">
        <div class="section-title">OMSET BULANAN</div>
        <div class="stat-card" style="grid-column: span 2; background: #e8f4fc; margin: 0 auto; max-width: 300px;">
            <div class="stat-value">Rp {{ number_format($operasional->omset, 0, ',', '.') }}</div>
            <div class="stat-label">Total Omset</div>
        </div>
    </div>

    <!-- Informasi Operasional -->
    <div class="section">
        <div class="section-title">INFORMASI OPERASIONAL</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Tempat Penjualan</div>
                <div class="info-value">{{ $operasional->tempat_penjualan ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kegiatan Pengelolaan</div>
                <div class="info-value">{{ $operasional->kegiatan_pengelolaan ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Produk Daur Ulang</div>
                <div class="info-value">{{ $operasional->produk_daur_ulang ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Buku Tabungan</div>
                <div class="info-value">{{ $operasional->buku_tabungan }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Sistem Pencatatan</div>
                <div class="info-value">{{ $operasional->sistem_pencatatan }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Timbangan</div>
                <div class="info-value">{{ $operasional->timbangan }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Alat Pengangkut</div>
                <div class="info-value">{{ $operasional->alat_pengangkut }}</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem BASMAN Kota Tegal</p>
        <p>Tanggal cetak: {{ date('d/m/Y H:i') }} | Data terakhir diupdate: {{ $operasional->updated_at->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>