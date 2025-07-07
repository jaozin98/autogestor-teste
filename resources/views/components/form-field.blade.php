@props([
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'icon' => null,
    'helpText' => null,
    'rows' => null,
    'min' => null,
    'max' => null,
    'step' => null,
    'options' => [],
    'selected' => null,
])

<div class="form-group">
    <label for="{{ $name }}" class="form-label">
        @if ($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows ?? 4 }}"
            class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }} {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select id="{{ $name }}" name="{{ $name }}"
            class="form-control @error($name) is-invalid @enderror" {{ $required ? 'required' : '' }}
            {{ $attributes }}>
            <option value="">{{ $placeholder ?: 'Selecione uma opção' }}</option>
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $selected) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    @elseif($type === 'checkbox')
        <div class="form-check">
            <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1"
                class="form-check-input @error($name) is-invalid @enderror" {{ old($name, $value) ? 'checked' : '' }}
                {{ $attributes }}>
            <label class="form-check-label" for="{{ $name }}">
                @if ($icon)
                    <i class="{{ $icon }}"></i>
                @endif
                {{ $label }}
            </label>
        </div>
    @else
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
            class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}" {{ $required ? 'required' : '' }}
            @if ($min) min="{{ $min }}" @endif
            @if ($max) max="{{ $max }}" @endif
            @if ($step) step="{{ $step }}" @endif {{ $attributes }}>
    @endif

    @error($name)
        <div class="invalid-feedback">
            <i class="fas fa-exclamation-triangle"></i>
            {{ $message }}
        </div>
    @enderror

    @if ($helpText)
        <small class="form-text text-muted">
            {{ $helpText }}
        </small>
    @endif
</div>
