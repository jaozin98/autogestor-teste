@extends('layouts.app')

@section('title', 'Categorias')

@push('styles')
    <x-crud-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-tags"></i>
            Gestão de Categorias
        </h1>
        <p class="page-subtitle">Organize seus produtos em categorias</p>
    </div>

    <!-- Estatísticas -->
    <x-stats-cards :stats="$stats" title="Estatísticas de Categorias" />

    <!-- Barra de Ações -->
    <x-action-bar :createRoute="route('categories.create')" createText="Nova Categoria" :searchRoute="route('categories.index')" searchPlaceholder="Buscar categorias..."
        :searchValue="$search" />

    <!-- Lista de Categorias -->
    <x-data-table-with-pagination :items="$categories" title="Lista de Categorias" icon="list" :search="$search"
        emptyIcon="tags" emptyTitle="Nenhuma categoria encontrada" emptyMessage="Comece criando sua primeira categoria."
        :createRoute="route('categories.create')">
        <x-slot name="header">
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Produtos</th>
            <th>Status</th>
            <th>Criada em</th>
            <th>Ações</th>
        </x-slot>

        <x-slot name="body">
            @foreach ($categories as $category)
                <tr>
                    <td>
                        <span class="badge bg-secondary">#{{ $category->id }}</span>
                    </td>
                    <td>
                        <div class="category-name">
                            <strong>{{ $category->name }}</strong>
                        </div>
                    </td>
                    <td>
                        @if ($category->description)
                            <span class="text-muted">{{ Str::limit($category->description, 50) }}</span>
                        @else
                            <span class="text-muted">Sem descrição</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info">
                            {{ $category->products_count ?? 0 }} produtos
                        </span>
                    </td>
                    <td>
                        @if ($category->is_active)
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Ativa
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-pause"></i> Inativa
                            </span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $category->created_at->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td>
                        <x-table-actions-extended :item="$category" :showRoute="route('categories.show', $category)" :editRoute="route('categories.edit', $category)" :toggleRoute="route('categories.toggle-status', $category)"
                            :deleteRoute="route('categories.destroy', $category)" itemName="categoria" />
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table-with-pagination>
@endsection

@push('styles')
    <style>
        .category-name {
            font-weight: 600;
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
