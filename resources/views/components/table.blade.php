<div class="table-container {{ $class ?? '' }}">
    @if(isset($title) || isset($actions))
        <div class="table-header">
            @if(isset($title))
                <h3>{{ $title }}</h3>
            @endif
            @if(isset($actions))
                <div class="table-actions">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="table-responsive">
        <table class="table {{ $tableClass ?? '' }}">
            @if(isset($headers))
                <thead>
                    <tr>
                        @foreach($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if(isset($footer))
        <div class="table-footer">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
.table-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.table-header {
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h3 {
    margin: 0;
    font-size: 16px;
    color: #2c3e50;
}

.table-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    margin-bottom: 0;
}

.table thead {
    background: #2c3e50;
    color: white;
}

.table th {
    padding: 12px 15px;
    font-weight: 600;
    border-bottom: 2px solid #34495e;
}

.table td {
    padding: 10px 15px;
    border-bottom: 1px solid #e0e0e0;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

.table tbody tr:nth-child(even) {
    background: #f8f9fa;
}

.table-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    border-top: 1px solid #e0e0e0;
}
</style>