@extends('layouts.app')

@section('title', 'Gerenciar Permissões')

@push('styles')
    <x-crud-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-key"></i>
            Gestão de Permissões
        </h1>
        <p class="page-subtitle">Visualize e gerencie todas as permissões do sistema</p>
    </div>

    @include('components.alerts')

    <!-- Estatísticas -->
    <x-stats-cards :stats="$stats" title="Estatísticas de Permissões" icon="key" />

    <!-- Barra de Ações -->
    <x-action-bar :createRoute="route('permissions.create')" createText="Nova Permissão" :showSearch="false" />

    <!-- Filtros Avançados -->
    <x-advanced-filters :route="route('permissions.index')" :search="$search" :perPage="$perPage" :filters="[]" />

    <!-- Tabela de Permissões -->
    <x-data-table-with-pagination :items="$permissions" title="Lista de Permissões" icon="key" emptyIcon="key"
        emptyTitle="Nenhuma permissão encontrada" emptyMessage="Comece criando sua primeira permissão." :createRoute="route('permissions.create')">
        <x-slot name="header">
            <th>Nome</th>
            <th>Tipo</th>
            <th>Roles</th>
            <th>Criada em</th>
            <th>Ações</th>
        </x-slot>

        <x-slot name="body">
            @foreach ($permissions as $permission)
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $permission->name }}
                            </div>
                        </div>
                    </td>
                    <td>
                        @if (str_contains($permission->name, 'user'))
                            <span class="badge bg-info">Usuário</span>
                        @elseif (str_contains($permission->name, 'product'))
                            <span class="badge bg-success">Produto</span>
                        @elseif (str_contains($permission->name, 'category'))
                            <span class="badge bg-warning">Categoria</span>
                        @elseif (str_contains($permission->name, 'brand'))
                            <span class="badge bg-primary">Marca</span>
                        @else
                            <span class="badge bg-secondary">Sistema</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-sm text-gray-900">
                            {{ $permission->roles->count() }} roles
                        </div>
                        @if ($permission->roles->count() > 0)
                            <div class="text-xs text-gray-500 mt-1">
                                @foreach ($permission->roles->take(3) as $role)
                                    <span
                                        class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                                @if ($permission->roles->count() > 3)
                                    <span class="text-gray-400">+{{ $permission->roles->count() - 3 }} mais</span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $permission->created_at->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td>
                        <x-table-actions-extended :item="$permission" :showRoute="route('permissions.show', $permission)" :editRoute="route('permissions.edit', $permission)" :deleteRoute="route('permissions.destroy', $permission)"
                            itemName="permissão" :canDelete="$permission->roles->count() === 0" />
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table-with-pagination>
@endsection
