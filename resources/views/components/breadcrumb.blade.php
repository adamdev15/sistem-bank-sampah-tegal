@php
    $breadcrumbs = isset($breadcrumbs) ? $breadcrumbs : [];
    $currentPage = isset($currentPage) ? $currentPage : '';
@endphp

<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        
        @foreach($breadcrumbs as $breadcrumb)
            <li class="breadcrumb-item">
                @if(isset($breadcrumb['url']))
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                @else
                    {{ $breadcrumb['title'] }}
                @endif
            </li>
        @endforeach
        
        @if($currentPage)
            <li class="breadcrumb-item active" aria-current="page">
                {{ $currentPage }}
            </li>
        @endif
    </ol>
</nav>

<style>
.breadcrumb-nav {
    margin-bottom: 1.5rem;
}

.breadcrumb {
    background: transparent;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    margin-bottom: 0;
}

.breadcrumb-item {
    font-size: 0.875rem;
}

.breadcrumb-item a {
    color: #3498db;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: #2980b9;
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #7f8c8d;
    font-weight: 600;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: #bdc3c7;
}
</style>