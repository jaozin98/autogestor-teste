@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">
                <i class="fas fa-tag"></i>
                {{ $category->name }}
            </h1>
            <p class="page-subtitle">Detalhes da categoria</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Editar
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Informações da Categoria -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Informações da Categoria
                    </h3>
                    <div class="card-actions">
                        @if ($category->is_active)
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Ativa
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-pause"></i> Inativa
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="category-details">
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-hashtag"></i>
                                ID da Categoria
                            </div>
                            <div class="detail-value">
                                #{{ $category->id }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-tag"></i>
                                Nome
                            </div>
                            <div class="detail-value">
                                <strong>{{ $category->name }}</strong>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-align-left"></i>
                                Descrição
                            </div>
                            <div class="detail-value">
                                @if ($category->description)
                                    {{ $category->description }}
                                @else
                                    <span class="text-muted">Sem descrição</span>
                                @endif
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-box"></i>
                                Total de Produtos
                            </div>
                            <div class="detail-value">
                                <span class="badge bg-info">
                                    {{ $category->products_count ?? 0 }} produtos
                                </span>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-calendar"></i>
                                Criada em
                            </div>
                            <div class="detail-value">
                                {{ $category->created_at->format('d/m/Y \à\s H:i') }}
                                <small class="text-muted">({{ $category->created_at->diffForHumans() }})</small>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fas fa-clock"></i>
                                Última atualização
                            </div>
                            <div class="detail-value">
                                {{ $category->updated_at->format('d/m/Y \à\s H:i') }}
                                <small class="text-muted">({{ $category->updated_at->diffForHumans() }})</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produtos da Categoria -->
            @if ($category->products && $category->products->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-box"></i>
                            Produtos nesta Categoria
                        </h3>
                        <div class="card-actions">
                            <span class="badge bg-primary">{{ $category->products->count() }} produtos</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Preço</th>
                                        <th>Marca</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category->products as $product)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">#{{ $product->id }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="price">
                                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($product->brand)
                                                    <span class="badge bg-success">
                                                        {{ $product->brand->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('products.show', $product) }}"
                                                    class="btn btn-sm btn-outline-info" title="Visualizar produto">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h3>Nenhum produto nesta categoria</h3>
                            <p>Esta categoria ainda não possui produtos associados.</p>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                Adicionar Produto
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Ações Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cogs"></i>
                        Ações
                    </h3>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-edit"></i>
                            Editar Categoria
                        </a>

                        <form action="{{ route('categories.toggle-status', $category) }}" method="POST"
                            class="d-inline w-100 mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                                {{ $category->is_active ? 'Desativar' : 'Ativar' }} Categoria
                            </button>
                        </form>

                        @if ($category->products_count == 0)
                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                class="d-inline w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.')">
                                    <i class="fas fa-trash"></i>
                                    Excluir Categoria
                                </button>
                            </form>
                        @else
                            <button class="btn btn-danger w-100" disabled
                                title="Não é possível excluir categoria com produtos">
                                <i class="fas fa-trash"></i>
                                Excluir Categoria
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i>
                        Estatísticas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="stats-list">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $category->products_count ?? 0 }}</div>
                                <div class="stat-label">Produtos</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">
                                    R$ {{ number_format($category->products->sum('price'), 2, ',', '.') }}
                                </div>
                                <div class="stat-label">Valor Total</div>
                            </div>
                        </div>

                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">
                                    {{ $category->products->unique('brand_id')->count() }}
                                </div>
                                <div class="stat-label">Marcas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Histórico -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        Histórico
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Categoria criada</div>
                                <div class="timeline-date">{{ $category->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>

                        @if ($category->updated_at != $category->created_at)
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">Última atualização</div>
                                    <div class="timeline-date">{{ $category->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header-content h1 {
            margin: 0;
        }

        .page-header-content p {
            margin: 0.5rem 0 0 0;
            color: var(--text-muted);
        }

        .page-header-actions {
            display: flex;
            gap: 0.5rem;
        }

        .category-details {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 150px;
        }

        .detail-value {
            font-weight: 500;
            text-align: right;
            flex: 1;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stats-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--light-bg);
            border-radius: var(--border-radius);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--text-color);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .timeline {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .timeline-icon {
            width: 30px;
            height: 30px;
            background: var(--success-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.75rem;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-title {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .timeline-date {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
        }

        .empty-state-icon {
            font-size: 3rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .price {
            font-weight: 600;
            color: var(--success-color);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .page-header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .detail-value {
                text-align: left;
            }
        }
    </style>
@endpush
