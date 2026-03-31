<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{ $size ?? '' }}">
        <div class="modal-content">
            @if(isset($title))
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            @endif
            
            <div class="modal-body">
                {{ $slot }}
            </div>
            
            @if(isset($footer))
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.modal-content {
    border: none;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.modal-header {
    background: #2c3e50;
    color: white;
    border-bottom: 1px solid #34495e;
    padding: 15px 20px;
}

.modal-title {
    margin: 0;
    font-size: 16px;
    color: white;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    background: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    padding: 15px 20px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
}

.modal-dialog-lg {
    max-width: 800px;
}

.modal-dialog-xl {
    max-width: 1140px;
}
</style>