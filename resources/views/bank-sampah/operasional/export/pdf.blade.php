<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Operasional - {{ $bankSampah->nama_bank_sampah }}</title>
    <style>
        {!! $css !!}
    </style>
</head>
<body>
    <div class="pdf-container">
        <!-- Kop Surat -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        <img src="{{ public_path('images/logo/logo-basman.png') }}" alt="Logo" class="logo">
                    </td>
                    <td class="title-cell">
                        <h1>DATA OPERASIONAL BANK SAMPAH</h1>
                        <h2>{{ $bankSampah->nama_bank_sampah }}</h2>
                        <p class="subtitle">
                            Dinas Lingkungan Hidup Kota Tegal<br>
                            Sistem BASMAN (Bank Sampah Management System)
                        </p>
                    </td>
                </tr>
            </table>
            
            <div class="metadata">
                <table class="meta-table">
                    <tr>
                        <td><strong>Kecamatan:</strong> {{ $bankSampah->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td><strong>Kelurahan:</strong> {{ $bankSampah->kelurahan->nama_kelurahan ?? '-' }}</td>
                        <td><strong>RW:</strong> {{ $bankSampah->rw }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Export:</strong> {{ date('d/m/Y H:i') }}</td>
                        <td><strong>Periode Data:</strong> {{ $operasional->updated_at->format('F Y') }}</td>
                        <td><strong>Status:</strong> Aktif</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Data Utama -->
        <div class="section">
            <h3 class="section-title">
                <span class="section-number">1.</span>
                TENAGA KERJA & NASABAH
            </h3>
            
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-cell">
                        <span class="label">Tenaga Kerja Laki-laki</span>
                        <span class="value">{{ $operasional->tenaga_kerja_laki }} orang</span>
                    </div>
                    <div class="data-cell">
                        <span class="label">Tenaga Kerja Perempuan</span>
                        <span class="value">{{ $operasional->tenaga_kerja_perempuan }} orang</span>
                    </div>
                    <div class="data-cell highlight">
                        <span class="label">Total Tenaga Kerja</span>
                        <span class="value">{{ $operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan }} orang</span>
                    </div>
                </div>
                
                <div class="data-row">
                    <div class="data-cell">
                        <span class="label">Nasabah Laki-laki</span>
                        <span class="value">{{ $operasional->nasabah_laki }} orang</span>
                    </div>
                    <div class="data-cell">
                        <span class="label">Nasabah Perempuan</span>
                        <span class="value">{{ $operasional->nasabah_perempuan }} orang</span>
                    </div>
                    <div class="data-cell highlight">
                        <span class="label">Total Nasabah</span>
                        <span class="value">{{ $operasional->nasabah_laki + $operasional->nasabah_perempuan }} orang</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Omset & Penjualan -->
        <div class="section">
            <h3 class="section-title">
                <span class="section-number">2.</span>
                OMSET & PENJUALAN
            </h3>
            
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-cell large">
                        <span class="label">Omset Bulanan</span>
                        <span class="value">Rp {{ number_format($operasional->omset, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="data-row">
                    <div class="data-cell">
                        <span class="label">Tempat Penjualan</span>
                        <span class="value">
                            @switch($operasional->tempat_penjualan)
                                @case('bank_sampah_induk')
                                    Bank Sampah Induk
                                    @break
                                @case('pengepul')
                                    Pengepul
                                    @break
                                @case('lainnya')
                                    {{ $operasional->tempat_penjualan_lainnya ?? 'Lainnya' }}
                                    @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kegiatan & Produk -->
        <div class="section">
            <h3 class="section-title">
                <span class="section-number">3.</span>
                KEGIATAN & PRODUK
            </h3>
            
            <div class="text-section">
                <div class="text-item">
                    <h4>Kegiatan Pengelolaan Sampah</h4>
                    <div class="text-content">{{ $operasional->kegiatan_pengelolaan ?: 'Belum diisi' }}</div>
                </div>
                
                <div class="text-item">
                    <h4>Produk Daur Ulang/Kerajinan</h4>
                    <div class="text-content">{{ $operasional->produk_daur_ulang ?: 'Belum diisi' }}</div>
                </div>
            </div>
        </div>

        <!-- Sarana & Prasarana -->
        <div class="section">
            <h3 class="section-title">
                <span class="section-number">4.</span>
                SARANA & PRASARANA
            </h3>
            
            <table class="facility-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Sarana</th>
                        <th>Status/Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Buku Tabungan</td>
                        <td>{{ $operasional->buku_tabungan == 'ada' ? 'Ada' : 'Tidak Ada' }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Sistem Pencatatan</td>
                        <td>{{ $operasional->sistem_pencatatan }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Timbangan</td>
                        <td>
                            @switch($operasional->timbangan)
                                @case('tidak_ada')
                                    Tidak Ada
                                    @break
                                @case('timbangan_gantung')
                                    Timbangan Gantung
                                    @break
                                @case('timbangan_digital')
                                    Timbangan Digital
                                    @break
                                @case('timbangan_posyandu')
                                    Timbangan Posyandu
                                    @break
                                @case('timbangan_duduk')
                                    Timbangan Duduk
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Alat Pengangkut</td>
                        <td>
                            @switch($operasional->alat_pengangkut)
                                @case('Tidak_ada')
                                    Tidak Ada
                                    @break
                                @case('Becak')
                                    Becak
                                    @break
                                @case('Gerobak')
                                    Gerobak
                                    @break
                                @case('Tossa')
                                    Tossa
                                    @break
                                @case('Lainnya')
                                    {{ $operasional->alat_pengangkut_lainnya ?? 'Lainnya' }}
                                    @break
                            @endswitch
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Ringkasan -->
        <div class="section summary">
            <h3 class="section-title">
                <span class="section-number">5.</span>
                RINGKASAN
            </h3>
            
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">Total SDM</div>
                    <div class="summary-value">
                        {{ ($operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan) + 
                           ($operasional->nasabah_laki + $operasional->nasabah_perempuan) }} orang
                    </div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Sarana Tersedia</div>
                    <div class="summary-value">
                        @php
                            $saranaCount = 0;
                            if($operasional->buku_tabungan == 'ada') $saranaCount++;
                            if($operasional->timbangan != 'tidak_ada') $saranaCount++;
                            if($operasional->alat_pengangkut != 'Tidak_ada') $saranaCount++;
                        @endphp
                        {{ $saranaCount }} dari 3 sarana
                    </div>
                </div>
                
                <div class="summary-item">
                    <div class="summary-label">Status Data</div>
                    <div class="summary-value complete">LENGKAP</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-left">
                        <strong>BASMAN - Bank Sampah Management System</strong><br>
                        Dinas Lingkungan Hidup Kota Tegal<br>
                        © {{ date('Y') }} - Sistem Informasi Terintegrasi
                    </td>
                    <td class="footer-right">
                        <div class="signature">
                            <div class="signature-line"></div>
                            <div class="signature-label">Direktur Bank Sampah</div>
                            <div class="signature-name">{{ $bankSampah->nama_direktur }}</div>
                        </div>
                    </td>
                </tr>
            </table>
            
            <div class="footer-info">
                Dokumen ini dicetak secara otomatis dari sistem BASMAN.<br>
                Tanggal cetak: {{ date('d/m/Y H:i:s') }} | Halaman: <span class="page-number"></span>
            </div>
        </div>
    </div>
</body>
</html>