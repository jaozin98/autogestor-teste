@props([
    'submitText' => 'Salvar',
    'submitIcon' => 'fas fa-save',
    'cancelRoute' => null,
    'cancelText' => 'Cancelar',
    'cancelIcon' => 'fas fa-times',
    'showCancel' => true,
])

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="{{ $submitIcon }}"></i>
        {{ $submitText }}
    </button>
    @if ($showCancel && $cancelRoute)
        <a href="{{ route($cancelRoute) }}" class="btn btn-secondary">
            <i class="{{ $cancelIcon }}"></i>
            {{ $cancelText }}
        </a>
    @endif
</div>
