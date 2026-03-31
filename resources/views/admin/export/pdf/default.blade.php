<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        
        body {
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .meta {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 11px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background: #f0f0f0;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="meta">
            <span>Dicetak: {{ $exportDate }}</span>
            <span>Total Data: {{ $totalData }}</span>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ json_encode($item) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>© {{ date('Y') }} - Dinas Lingkungan Hidup Kota Tegal</p>
        <p>Sistem BASMAN</p>
    </div>
</body>
</html>