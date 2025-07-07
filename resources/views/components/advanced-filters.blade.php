@props(['route', 'search' => '', 'filters' => [], 'perPage' => 15, 'showSearch' => true, 'showPerPage' => true])

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-filter"></i>
            Filtros e Busca
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ $route }}" class="filters-form">
            <div class="filters-grid">
                @if ($showSearch)
                    <div class="filter-group">
                        <label for="search" class="form-label">
                            <i class="fas fa-search"></i>
                            Buscar
                        </label>
                        <input type="text" id="search" name="search" value="{{ $search }}"
                            placeholder="Digite para buscar..." class="form-control">
                    </div>
                @endif

                @foreach ($filters as $filter)
                    <div class="filter-group">
                        <label for="{{ $filter['name'] }}" class="form-label">
                            <i class="fas fa-{{ $filter['icon'] ?? 'filter' }}"></i>
                            {{ $filter['label'] }}
                        </label>
                        <select id="{{ $filter['name'] }}" name="{{ $filter['name'] }}" class="form-control">
                            <option value="">{{ $filter['placeholder'] ?? 'Todos' }}</option>
                            @foreach ($filter['options'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                @if ($showPerPage)
                    <div class="filter-group">
                        <label for="per_page" class="form-label">
                            <i class="fas fa-list"></i>
                            Por página
                        </label>
                        <select id="per_page" name="per_page" class="form-control">
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                @endif
            </div>

            <div class="filters-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                <a href="{{ $route }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .filters-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-color);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        font-size: 0.875rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filters-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    @media (max-width: 768px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }

        .filters-actions {
            flex-direction: column;
        }
    }
</style>
