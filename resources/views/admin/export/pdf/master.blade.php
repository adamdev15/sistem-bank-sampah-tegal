<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        {!! file_get_contents(public_path('css/pdf-bank-sampah.css')) !!}
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
                    <th>Kelurahan</th>
                    <th>RW</th>
                    <th>Status Terbentuk</th>
                    <th>Nomor SK</th>
                    <th>Nama Direktur</th>
                    <th>Nomor HP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->nama_bank_sampah }}</td>
                        <td>{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td>{{ $item->kelurahan->nama_kelurahan ?? '-' }}</td>
                        <td class="text-center">{{ $item->rw }}</td>
                        <td class="text-center">{{ $item->status_terbentuk }}</td>
                        <td>{{ $item->nomor_sk ?? '-' }}</td>
                        <td>{{ $item->nama_direktur }}</td>
                        <td>{{ $item->nomor_hp }}</td>
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