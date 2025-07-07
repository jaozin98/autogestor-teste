@props([
    'item',
    'showRoute' => null,
    'editRoute' => null,
    'toggleRoute' => null,
    'deleteRoute' => null,
    'itemName' => 'item',
    'showView' => true,
    'showEdit' => true,
    'showToggle' => true,
    'showDelete' => true,
])

<div class="btn-group" role="group">
    @if ($showView && $showRoute)
        <a href="{{ $showRoute }}" class="btn btn-sm btn-outline-info" title="Visualizar">
            <i class="fas fa-eye"></i>
        </a>
    @endif

    @if ($showEdit && $editRoute)
        <a href="{{ $editRoute }}" class="btn btn-sm btn-outline-primary" title="Editar">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    @if ($showToggle && $toggleRoute)
        <form action="{{ $toggleRoute }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-warning"
                title="{{ $item->is_active ? 'Desativar' : 'Ativar' }}">
                <i class="fas fa-{{ $item->is_active ? 'pause' : 'play' }}"></i>
            </button>
        </form>
    @endif

    @if ($showDelete && $deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="d-inline delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir"
                onclick="return confirm('Tem certeza que deseja excluir este {{ $itemName }}?')">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    @endif
</div>

<style>
    .btn-group {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
    }

    .btn-group .btn {
        border-radius: var(--border-radius);
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border: 1px solid transparent;
        transition: all 0.15s ease-in-out;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
    }

    .btn-outline-info {
        color: var(--info-color);
        border-color: var(--info-color);
        background-color: transparent;
    }

    .btn-outline-info:hover {
        color: var(--white);
        background-color: var(--info-color);
        border-color: var(--info-color);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background-color: transparent;
    }

    .btn-outline-primary:hover {
        color: var(--white);
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-warning {
        color: var(--warning-color);
        border-color: var(--warning-color);
        background-color: transparent;
    }

    .btn-outline-warning:hover {
        color: var(--white);
        background-color: var(--warning-color);
        border-color: var(--warning-color);
    }

    .btn-outline-danger {
        color: var(--danger-color);
        border-color: var(--danger-color);
        background-color: transparent;
    }

    .btn-outline-danger:hover {
        color: var(--white);
        background-color: var(--danger-color);
        border-color: var(--danger-color);
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            gap: 0.125rem;
        }

        .btn-group .btn {
            width: 100%;
            justify-content: center;
            padding: 0.375rem 0.5rem;
            font-size: 0.8125rem;
        }
    }

    /* Mobile */
    @media (max-width: 480px) {
        .btn-group {
            gap: 0.25rem;
        }

        .btn-group .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
            min-height: 2.5rem;
        }
    }

    /* Extra small mobile */
    @media (max-width: 360px) {
        .btn-group .btn {
            padding: 0.375rem;
            font-size: 0.8125rem;
            min-height: 2.25rem;
        }
    }
</style>
