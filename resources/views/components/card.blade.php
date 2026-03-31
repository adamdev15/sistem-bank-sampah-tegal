<div class="card {{ $class ?? '' }}">
    @if(isset($header))
        <div class="card-header">
            <h5 class="card-title mb-0">
                @if(isset($icon))
                    <i class="{{ $icon }} me-2"></i>
                @endif
                {{ $header }}
            </h5>
            @if(isset($headerAction))
                <div class="card-header-action">
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
.card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    background: white;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    margin: 0;
    font-size: 16px;
    color: #2c3e50;
    font-weight: 600;
}

.card-body {
    padding: 20px;
}

.card-footer {
    background: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    padding: 15px 20px;
}

.card-header-action {
    display: flex;
    gap: 10px;
    align-items: center;
}
</style>