@props(['items', 'perPageOptions' => [15, 30, 50, 100]])

@if (method_exists($items, 'hasPages') && $items->hasPages())
    <div class="pagination-controls">
        <div class="pagination-info">
            <span class="text-muted">
                Mostrando {{ $items->firstItem() ?? 0 }} a {{ $items->lastItem() ?? 0 }}
                de {{ $items->total() }} resultados
            </span>
        </div>

        <div class="pagination-options">
            <form action="{{ request()->url() }}" method="GET" class="per-page-form">
                @foreach (request()->query() as $key => $value)
                    @if ($key !== 'per_page' && $key !== 'page')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach

                <label for="per_page" class="form-label">Itens por página:</label>
                <select name="per_page" id="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" {{ request('per_page', 15) == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
@endif

<style>
    .pagination-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background-color: var(--gray-50);
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        gap: 1rem;
    }

    .pagination-info {
        font-size: 0.875rem;
        flex: 1;
        min-width: 0;
    }

    .pagination-info .text-muted {
        word-break: break-word;
    }

    .pagination-options {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .per-page-form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .form-label {
        margin: 0;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-color);
        white-space: nowrap;
    }

    .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: var(--border-radius-sm);
        min-width: 80px;
        max-width: 120px;
    }

    /* Tablet */
    @media (max-width: 768px) {
        .pagination-controls {
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }

        .pagination-info {
            text-align: center;
        }

        .pagination-options {
            justify-content: center;
        }

        .per-page-form {
            justify-content: center;
            width: 100%;
        }
    }

    /* Mobile */
    @media (max-width: 480px) {
        .pagination-controls {
            padding: 0.5rem;
            gap: 0.5rem;
        }

        .pagination-info {
            font-size: 0.8125rem;
        }

        .per-page-form {
            flex-direction: column;
            gap: 0.25rem;
            align-items: stretch;
        }

        .form-label {
            text-align: center;
            font-size: 0.8125rem;
        }

        .form-select-sm {
            width: 100%;
            max-width: none;
            text-align: center;
        }
    }

    /* Extra small mobile */
    @media (max-width: 360px) {
        .pagination-info .text-muted {
            font-size: 0.75rem;
        }

        .form-select-sm {
            font-size: 16px;
            /* Previne zoom no iOS */
        }
    }
</style>
