@props([
    'items',
    'title',
    'icon' => 'list',
    'search' => '',
    'emptyIcon' => 'box',
    'emptyTitle' => 'Nenhum item encontrado',
    'emptyMessage' => 'Comece criando seu primeiro item.',
    'createRoute' => null,
])

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-{{ $icon }}"></i>
            {{ $title }}
            @if ($search)
                <span class="search-results">Resultados para: "{{ $search }}"</span>
            @endif
        </h3>
        <div class="card-actions">
            <span class="badge bg-primary">
                {{ method_exists($items, 'total') ? $items->total() : $items->count() }} itens
            </span>
        </div>
    </div>

    <div class="card-body">
        @if ($items->count() > 0)
            <!-- Controles de Paginação -->
            @if (method_exists($items, 'hasPages') && $items->hasPages())
                <x-pagination-controls :items="$items" />
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            {{ $header }}
                        </tr>
                    </thead>
                    <tbody>
                        {{ $body }}
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if (method_exists($items, 'hasPages') && $items->hasPages())
                <div class="pagination-wrapper">
                    {{ $items->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-{{ $emptyIcon }}"></i>
                </div>
                <h3>{{ $emptyTitle }}</h3>
                <p>
                    @if ($search)
                        Não foram encontrados itens para "{{ $search }}".
                        <a href="{{ request()->url() }}">Limpar busca</a>
                    @else
                        {{ $emptyMessage }}
                    @endif
                </p>
                @if (!$search && $createRoute)
                    <a href="{{ $createRoute }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Criar Primeiro Item
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<style>
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        background-color: var(--gray-50);
    }

    .card-title {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .search-results {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-left: 1rem;
        font-weight: normal;
    }

    .card-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-responsive {
        overflow-x: auto;
        border-radius: var(--border-radius);
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table th {
        background-color: var(--gray-50);
        border-bottom: 2px solid var(--border-color);
        padding: 0.75rem;
        font-weight: 600;
        text-align: left;
        color: var(--text-color);
    }

    .table td {
        padding: 0.75rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: var(--gray-50);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        margin-bottom: 0.5rem;
        color: var(--text-color);
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }

    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.25rem;
    }

    .pagination li {
        display: flex;
    }

    .pagination a,
    .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        text-decoration: none;
        color: var(--text-color);
        transition: all 0.2s ease;
    }

    .pagination a:hover {
        background-color: var(--gray-50);
        border-color: var(--primary-color);
    }

    .pagination .active span {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: var(--white);
    }

    .pagination .disabled span {
        color: var(--text-muted);
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .table th,
        .table td {
            padding: 0.5rem;
        }

        .card-title {
            font-size: 1rem;
        }

        .search-results {
            font-size: 0.75rem;
            margin-left: 0;
            margin-top: 0.25rem;
        }
    }

    /* Mobile */
    @media (max-width: 480px) {
        .card-header {
            padding: 0.75rem;
        }

        .card-body {
            padding: 0.75rem;
        }

        .table-responsive {
            font-size: 0.8125rem;
            margin: 0 -0.75rem;
        }

        .table th,
        .table td {
            padding: 0.375rem 0.5rem;
        }

        .card-title {
            font-size: 0.9375rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .card-actions {
            align-self: flex-start;
        }

        .empty-state {
            padding: 2rem 0.5rem;
        }

        .empty-state-icon {
            font-size: 3rem;
        }

        .empty-state h3 {
            font-size: 1.125rem;
        }

        .empty-state p {
            font-size: 0.875rem;
        }
    }

    /* Extra small mobile */
    @media (max-width: 360px) {
        .table-responsive {
            font-size: 0.75rem;
        }

        .table th,
        .table td {
            padding: 0.25rem 0.375rem;
        }

        .empty-state-icon {
            font-size: 2.5rem;
        }

        .empty-state h3 {
            font-size: 1rem;
        }

        .empty-state p {
            font-size: 0.8125rem;
        }
    }
</style>
