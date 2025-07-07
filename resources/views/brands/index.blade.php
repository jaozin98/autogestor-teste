@extends('layouts.app')

@section('title', 'Marcas')

@push('styles')
    <x-crud-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-copyright"></i>
            Gestão de Marcas
        </h1>
        <p class="page-subtitle">Gerencie as marcas dos seus produtos</p>
    </div>

    <!-- Estatísticas -->
    <x-stats-cards :stats="$stats" title="Estatísticas de Marcas" icon="copyright" />

    <!-- Barra de Ações -->
    <x-action-bar :createRoute="route('brands.create')" createText="Nova Marca" :searchRoute="route('brands.index')"
        searchPlaceholder="Buscar por nome, país ou descrição..." :searchValue="$search" />

    <!-- Lista de Marcas -->
    <x-data-table-with-pagination :items="$brands" title="Lista de Marcas" icon="list" :search="$search"
        emptyIcon="copyright" emptyTitle="Nenhuma marca encontrada" emptyMessage="Comece criando sua primeira marca."
        :createRoute="route('brands.create')">
        <x-slot name="header">
            <th>ID</th>
            <th>Nome</th>
            <th>País</th>
            <th>Fundação</th>
            <th>Website</th>
            <th>Produtos</th>
            <th>Status</th>
            <th>Ações</th>
        </x-slot>

        <x-slot name="body">
            @foreach ($brands as $brand)
                <tr>
                    <td>
                        <span class="badge bg-secondary">#{{ $brand->id }}</span>
                    </td>
                    <td>
                        <div class="brand-name">
                            <strong>{{ $brand->name }}</strong>
                            @if ($brand->description)
                                <br>
                                <small class="text-muted">{{ Str::limit($brand->description, 50) }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if ($brand->country_of_origin)
                            <span class="badge bg-info">{{ $brand->country_of_origin }}</span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if ($brand->founded_year)
                            <div>
                                <span class="badge bg-primary">{{ $brand->founded_year }}</span>
                                @if ($brand->age)
                                    <br>
                                    <small class="text-muted">{{ $brand->age }} anos</small>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if ($brand->website)
                            <a href="{{ $brand->website }}" target="_blank" class="text-decoration-none"
                                title="Visitar website">
                                <i class="fas fa-external-link-alt"></i>
                                {{ $brand->formatted_website }}
                            </a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $brand->products_count > 0 ? 'success' : 'warning' }}">
                            {{ $brand->products_count }}
                            produto{{ $brand->products_count != 1 ? 's' : '' }}
                        </span>
                    </td>
                    <td>
                        @if ($brand->is_active)
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
                        <x-table-actions-extended :item="$brand" :showRoute="route('brands.show', $brand)" :editRoute="route('brands.edit', $brand)" :toggleRoute="route('brands.toggle-status', $brand)"
                            :deleteRoute="route('brands.destroy', $brand)" itemName="marca" />
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table-with-pagination>
@endsection

@push('styles')
    <style>
        .brand-name {
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

        .bg-primary {
            background-color: var(--primary-color);
        }
    </style>
@endpush
