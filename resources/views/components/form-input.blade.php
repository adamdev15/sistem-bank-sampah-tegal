@props([
    'label' => '',
    'name' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => '',
    'error' => '',
    'class' => '',
    'options' => [],
])

<div class="form-group {{ $class }}">
    @if($label)
        <label for="{{ $name }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    
    @if($type === 'select')
        <select 
            id="{{ $name }}" 
            name="{{ $name }}"
            class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
        >
            <option value="">-- Pilih {{ $label }} --</option>
            @foreach($options as $key => $option)
                <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea 
            id="{{ $name }}" 
            name="{{ $name }}"
            class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            rows="3"
        >{{ old($name, $value) }}</textarea>
    @else
        <input 
            type="{{ $type }}" 
            id="{{ $name }}" 
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
        >
    @endif
    
    @if($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif
    
    @if($errors->has($name))
        <div class="invalid-feedback">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>

<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #34495e;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.form-control.is-invalid {
    border-color: #e74c3c;
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
}

.invalid-feedback {
    display: block;
    margin-top: 5px;
    color: #e74c3c;
    font-size: 13px;
}

.form-text {
    display: block;
    margin-top: 5px;
    color: #7f8c8d;
    font-size: 12px;
}
</style>