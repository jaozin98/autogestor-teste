@props([
    'title' => 'Adicionar Novo Item',
    'action' => '',
    'method' => 'POST',
    'submitText' => 'Adicionar',
    'submitClass' => 'btn-success',
])

<div class="form-card">
    <div class="form-header">
        <h4>{{ $title }}</h4>
    </div>

    <form method="{{ $method }}" action="{{ $action }}" class="create-form">
        @csrf
        {{ $slot }}

        <div class="form-actions">
            <button type="submit" class="btn {{ $submitClass }}">
                <i class="fas fa-plus"></i>
                {{ $submitText }}
            </button>
        </div>
    </form>
</div>
