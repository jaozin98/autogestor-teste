@extends('layouts.app')

@section('title', 'Detalhes do Produto')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-eye"></i>
            Detalhes do Produto
        </h1>
        <p class="page-subtitle">Visualize as informações completas do produto</p>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-box"></i>
                {{ $product->name }}
            </h3>
            <div class="d-flex gap-2">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-group">
                        <label class="info-label">ID:</label>
                        <span class="info-value">#{{ $product->id }}</span>
                    </div>

                    <div class="info-group">
                        <label class="info-label">Nome:</label>
                        <span class="info-value">{{ $product->name }}</span>
                    </div>

                    <div class="info-group">
                        <label class="info-label">Preço:</label>
                        <span class="info-value price">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                    </div>

                    <div class="info-group">
                        <label class="info-label">SKU:</label>
                        <span class="info-value">{{ $product->sku ?: 'N/A' }}</span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-group">
                        <label class="info-label">Categoria:</label>
                        <span class="info-value">
                            @if ($product->category)
                                <span class="badge bg-success">{{ $product->category->name }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </span>
                    </div>

                    <div class="info-group">
                        <label class="info-label">Marca:</label>
                        <span class="info-value">
                            @if ($product->brand)
                                <span class="badge bg-info">{{ $product->brand->name }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </span>
                    </div>

                    <div class="info-group">
                        <label class="info-label">Estoque:</label>
                        <span class="info-value">
                            <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->stock }} unidades
                            </span>
                        </span>
                    </div>

                    <div class="info-group">
                        <label class="info-label">Status:</label>
                        <span class="info-value">
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            @if ($product->description)
                <div class="info-group mt-4">
                    <label class="info-label">Descrição:</label>
                    <div class="info-value">
                        <p class="text-gray-700">{{ $product->description }}</p>
                    </div>
                </div>
            @endif

            <div class="info-group mt-4">
                <label class="info-label">Data de Criação:</label>
                <span class="info-value">{{ $product->created_at->format('d/m/Y H:i:s') }}</span>
            </div>

            @if ($product->updated_at != $product->created_at)
                <div class="info-group">
                    <label class="info-label">Última Atualização:</label>
                    <span class="info-value">{{ $product->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .info-group {
            margin-bottom: 1rem;
        }

        .info-label {
            font-weight: 600;
            color: var(--gray-700);
            display: block;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: var(--gray-900);
            font-size: 1rem;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: var(--border-radius-sm);
            color: var(--white);
        }

        .bg-success {
            background-color: var(--success-color);
        }

        .bg-danger {
            background-color: var(--danger-color);
        }

        .bg-info {
            background-color: var(--info-color);
        }

        .price {
            font-weight: 600;
            color: var(--success-color);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background-color: var(--gray-500);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
@endpush
