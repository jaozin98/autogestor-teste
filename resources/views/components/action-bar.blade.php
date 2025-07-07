@props([
    'createRoute',
    'createText',
    'searchRoute',
    'searchPlaceholder',
    'searchValue' => '',
    'showCreate' => true,
    'showSearch' => true,
])

<div class="action-bar">
    @if ($showCreate)
        <div class="action-bar-left">
            <a href="{{ $createRoute }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                {{ $createText }}
            </a>
        </div>
    @endif

    @if ($showSearch)
        <div class="action-bar-right">
            <form action="{{ $searchRoute }}" method="GET" class="search-form">
                <div class="search-input-group">
                    <input type="text" name="search" value="{{ $searchValue }}"
                        placeholder="{{ $searchPlaceholder }}" class="form-control search-input">
                    <button type="submit" class="btn btn-secondary search-btn">
                        <i class="fas fa-search"></i>
                        <span class="search-text">Buscar</span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

<style>
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .action-bar-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .action-bar-right {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
    }

    .search-form {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 400px;
    }

    .search-input-group {
        display: flex;
        align-items: center;
        width: 100%;
        gap: 0;
    }

    .search-input-group .form-control,
    .search-input-group .search-input {
        flex: 2 1 0%;
        min-height: 2.5rem;
        font-size: 16px;
        padding: 0.75rem 1rem;
        box-sizing: border-box;
    }

    .search-input-group .btn {
        border-radius: 0 var(--border-radius) var(--border-radius) 0;
        border-left: none;
        flex-shrink: 0;
        white-space: nowrap;
        min-height: 2.5rem;
    }

    .search-btn {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .search-text {
        display: inline;
    }

    /* Tablet */
    @media (max-width: 768px) {
        .action-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .action-bar-left {
            width: 100%;
            justify-content: center;
        }

        .action-bar-right {
            width: 100%;
        }

        .search-form {
            max-width: none;
        }

        .search-input-group {
            width: 100%;
            flex-direction: column;
            gap: 0.5rem;
        }

        .search-input-group .btn {
            border-radius: var(--border-radius) !important;
        }
    }

    /* Mobile */
    @media (max-width: 480px) {
        .action-bar {
            gap: 0.75rem;
        }

        .search-input-group {
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }

        .search-input-group .form-control,
        .search-input-group .search-input {
            min-width: 0;
            width: 100%;
            font-size: 16px;
            min-height: 2.75rem;
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius) !important;
        }

        .search-input-group .btn {
            width: 100%;
            padding: 0.75rem 0;
            font-size: 1.1rem;
            min-height: 2.75rem;
            justify-content: center;
            border-radius: var(--border-radius) !important;
        }

        .search-btn {
            padding: 0.5rem 0.75rem;
            width: 100%;
            justify-content: center;
        }

        .search-text {
            display: none;
        }

        .search-btn i {
            margin: 0;
        }
    }

    /* Extra small mobile */
    @media (max-width: 360px) {
        .action-bar-left .btn {
            width: 100%;
            justify-content: center;
        }

        .search-input-group .form-control,
        .search-input-group .search-input {
            font-size: 16px;
            min-height: 2.5rem;
            padding: 0.5rem 0.75rem;
        }
    }
</style>
