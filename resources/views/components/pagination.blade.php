@if($paginator->hasPages())
    <nav class="pagination-nav" aria-label="Page navigation">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fas fa-chevron-left"></i> Prev
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left"></i> Prev
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if(is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if(is_array($element))
                    @foreach($element as $page => $url)
                        @if($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        Next <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
        
        <div class="pagination-info">
            Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} 
            dari {{ $paginator->total() }} data
        </div>
    </nav>
@endif

<style>
.pagination-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding: 15px 0;
    border-top: 1px solid #e0e0e0;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 5px;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    display: block;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #3498db;
    min-width: 40px;
    text-align: center;
    transition: all 0.3s;
}

.page-link:hover {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.page-item.active .page-link {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.page-item.disabled .page-link {
    color: #95a5a6;
    background: #f8f9fa;
    cursor: not-allowed;
}

.pagination-info {
    color: #7f8c8d;
    font-size: 14px;
}
</style>