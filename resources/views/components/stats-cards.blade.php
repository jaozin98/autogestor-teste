@props(['stats', 'title', 'icon', 'color' => 'primary'])

@php
    $translations = [
        'total' => 'Total',
        'active' => 'Ativos',
        'inactive' => 'Inativos',
        'with_products' => 'Com Produtos',
        'without_products' => 'Sem Produtos',
        'recent' => 'Recentes',
        'with_stock' => 'Com Estoque',
        'out_of_stock' => 'Sem Estoque',
        'low_stock' => 'Estoque Baixo',
        'total_value' => 'Valor Total',
        'avg_price' => 'Preço Médio',
        'categories_count' => 'Categorias',
        'brands_count' => 'Marcas',
        'admin' => 'Administradores',
        'user' => 'Usuários',
    ];
@endphp

<div class="stats-grid">
    @foreach ($stats as $key => $value)
        <div class="stat-card">
            <div class="stat-icon {{ $key }}">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3>
                    @if (in_array($key, ['avg_price', 'total_value']) && is_numeric($value))
                        R$ {{ number_format($value, 2, ',', '.') }}
                    @else
                        {{ $value }}
                    @endif
                </h3>
                <p>{{ $translations[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}</p>
            </div>
        </div>
    @endforeach
</div>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--white);
        background-color: var(--primary-color);
    }

    .stat-icon.total {
        background-color: var(--primary-color);
    }

    .stat-icon.active {
        background-color: var(--success-color);
    }

    .stat-icon.inactive {
        background-color: var(--warning-color);
    }

    .stat-icon.with_products {
        background-color: var(--info-color);
    }

    .stat-icon.without_products {
        background-color: var(--warning-color);
    }

    .stat-icon.recent {
        background-color: var(--info-color);
    }

    .stat-icon.low_stock {
        background-color: var(--danger-color);
    }

    .stat-icon.out_of_stock {
        background-color: var(--danger-color);
    }

    .stat-icon.admin {
        background-color: var(--purple-color, #8b5cf6);
    }

    .stat-icon.user {
        background-color: var(--info-color);
    }

    .stat-icon.custom {
        background-color: var(--purple-color, #8b5cf6);
    }

    .stat-icon.product {
        background-color: var(--success-color);
    }

    .stat-icon.category {
        background-color: var(--warning-color);
    }

    .stat-icon.brand {
        background-color: var(--primary-color);
    }

    .stat-content h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--text-color);
    }

    .stat-content p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }

        .stat-content h3 {
            font-size: 1.25rem;
        }

        .stat-content p {
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .stat-card {
            padding: 0.875rem;
        }

        .stat-icon {
            width: 35px;
            height: 35px;
            font-size: 1.125rem;
        }

        .stat-content h3 {
            font-size: 1.125rem;
        }

        .stat-content p {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 360px) {
        .stat-card {
            padding: 0.75rem;
            gap: 0.75rem;
        }

        .stat-icon {
            width: 30px;
            height: 30px;
            font-size: 1rem;
        }

        .stat-content h3 {
            font-size: 1rem;
        }

        .stat-content p {
            font-size: 0.6875rem;
        }
    }
</style>
