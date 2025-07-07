@extends('layouts.app')

@section('title', 'Produtos')

@push('styles')
    <x-crud-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-box"></i>
            Gestão de Produtos
        </h1>
        <p class="page-subtitle">Gerencie seus produtos, preços e categorias</p>
    </div>

    <!-- Estatísticas -->
    <x-stats-cards :stats="$stats" title="Estatísticas de Produtos" icon="box" />

    <!-- Barra de Ações -->
    <x-action-bar :createRoute="route('products.create')" createText="Novo Produto" :searchRoute="route('products.index')" searchPlaceholder="Buscar produtos..."
        :searchValue="$search" />

    <!-- Lista de Produtos -->
    <x-data-table-with-pagination :items="$products" title="Lista de Produtos" icon="list" :search="$search" emptyIcon="box"
        emptyTitle="Nenhum produto encontrado" emptyMessage="Comece criando seu primeiro produto." :createRoute="route('products.create')">
        <x-slot name="header">
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Categoria</th>
            <th>Marca</th>
            <th>Status</th>
            <th>Criado em</th>
            <th>Ações</th>
        </x-slot>

        <x-slot name="body">
            @foreach ($products as $product)
                <tr>
                    <td>
                        <span class="badge bg-secondary">#{{ $product->id }}</span>
                    </td>
                    <td>
                        <div class="product-name">
                            <strong>{{ $product->name }}</strong>
                            @if ($product->description)
                                <br>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="price">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        @if ($product->category)
                            <span class="badge bg-success">
                                {{ $product->category->name }}
                            </span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if ($product->brand)
                            <span class="badge bg-info">
                                {{ $product->brand->name }}
                            </span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if ($product->is_active)
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Ativo
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-pause"></i> Inativo
                            </span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $product->created_at->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td>
                        <x-table-actions-extended :item="$product" :showRoute="route('products.show', $product)" :editRoute="route('products.edit', $product)" :toggleRoute="route('products.toggle-status', $product)"
                            :deleteRoute="route('products.destroy', $product)" itemName="produto" />
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table-with-pagination>
@endsection

@push('styles')
    <style>
        .product-name {
            font-weight: 600;
        }

        .price {
            font-weight: 600;
            color: var(--success-color);
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: var(--border-radius-sm);
            color: var(--white);
        }

        .bg-secondary {
            background-color: var(--gray-600);
        }

        .bg-success {
            background-color: var(--success-color);
        }

        .bg-warning {
            background-color: var(--warning-color);
        }

        .bg-info {
            background-color: var(--info-color);
        }
    </style>
@endpush
