<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        {!! file_get_contents(public_path('css/pdf-operasional.css')) !!}
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
        </div>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bank Sampah</th>
                    <th>Kecamatan</th>
                    <th>Tenaga Kerja L</th>
                    <th>Tenaga Kerja P</th>
                    <th>Total TK</th>
                    <th>Nasabah L</th>
                    <th>Nasabah P</th>
                    <th>Total Nasabah</th>
                    <th>Omset (Rp)</th>
                    <th>Tempat Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    @php
                        $bank = $item->bankSampahMaster;
                        $totalTK = $item->tenaga_kerja_laki + $item->tenaga_kerja_perempuan;
                        $totalNasabah = $item->nasabah_laki + $item->nasabah_perempuan;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $bank->nama_bank_sampah ?? '-' }}</td>
                        <td>{{ $bank->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td class="text-center">{{ $item->tenaga_kerja_laki }}</td>
                        <td class="text-center">{{ $item->tenaga_kerja_perempuan }}</td>
                        <td class="text-center">{{ $totalTK }}</td>
                        <td class="text-center">{{ $item->nasabah_laki }}</td>
                        <td class="text-center">{{ $item->nasabah_perempuan }}</td>
                        <td class="text-center">{{ $totalNasabah }}</td>
                        <td class="text-right">{{ number_format($item->omset, 0, ',', '.') }}</td>
                        <td>{{ $item->tempat_penjualan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="summary">
        <h3>Data Fasilitas & Prasarana</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Bank Sampah</th>
                    <th>Buku Tabungan</th>
                    <th>Sistem Pencatatan</th>
                    <th>Timbangan</th>
                    <th>Alat Pengangkut</th>
                    <th>Kegiatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    @php
                        $bank = $item->bankSampahMaster;
                    @endphp
                    <tr>
                        <td>{{ $bank->nama_bank_sampah ?? '-' }}</td>
                        <td class="text-center">{{ $item->buku_tabungan }}</td>
                        <td>{{ $item->sistem_pencatatan }}</td>
                        <td class="text-center">{{ $item->timbangan }}</td>
                        <td class="text-center">{{ $item->alat_pengangkut }}</td>
                        <td>{{ Str::limit($item->kegiatan_pengelolaan, 50) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} - Dinas Lingkungan Hidup Kota Tegal</p>
        <p>Sistem BASMAN - Bank Sampah Management System</p>
    </div>
</body>
</html>