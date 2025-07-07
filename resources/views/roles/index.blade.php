@extends('layouts.app')

@section('title', 'Gerenciar Roles')

@push('styles')
    <x-crud-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-tag"></i>
            Gestão de Roles
        </h1>
        <p class="page-subtitle">Visualize e gerencie todas as roles do sistema</p>
    </div>

    @include('components.alerts')

    <!-- Estatísticas -->
    <x-stats-cards :stats="$stats" title="Estatísticas de Roles" icon="user-tag" />

    <!-- Barra de Ações -->
    <x-action-bar :createRoute="route('roles.create')" createText="Nova Role" :showSearch="false" />

    <!-- Filtros Avançados -->
    <x-advanced-filters :route="route('roles.index')" :search="$search" :perPage="$perPage" :filters="[
        [
            'name' => 'type',
            'label' => 'Tipo de Role',
            'icon' => 'user-tag',
            'placeholder' => 'Todos os tipos',
            'options' => [
                'admin' => 'Administrador',
                'user' => 'Usuário',
                'custom' => 'Personalizada',
            ],
        ],
    ]" />

    <!-- Tabela de Roles -->
    <x-data-table-with-pagination :items="$roles" title="Lista de Roles" icon="user-tag" emptyIcon="user-tag"
        emptyTitle="Nenhuma role encontrada" emptyMessage="Comece criando sua primeira role." :createRoute="route('roles.create')">
        <x-slot name="header">
            <th>Nome</th>
            <th>Permissões</th>
            <th>Usuários</th>
            <th>Criada em</th>
            <th>Ações</th>
        </x-slot>

        <x-slot name="body">
            @foreach ($roles as $role)
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">
                                {{ ucfirst($role->name) }}
                                @if ($role->name === 'admin')
                                    <span
                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Administrador
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-sm text-gray-900">
                            {{ $role->permissions->count() }} permissões
                        </div>
                        @if ($role->permissions->count() > 0)
                            <div class="text-xs text-gray-500 mt-1">
                                @foreach ($role->permissions->take(3) as $permission)
                                    <span
                                        class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                                @if ($role->permissions->count() > 3)
                                    <span class="text-gray-400">+{{ $role->permissions->count() - 3 }} mais</span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="text-sm text-gray-900">{{ $role->users->count() }} usuários</span>
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $role->created_at->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td>
                        <x-table-actions-extended :item="$role" :showRoute="route('roles.show', $role)" :editRoute="route('roles.edit', $role)" :deleteRoute="route('roles.destroy', $role)"
                            itemName="role" :canDelete="$role->name !== 'admin' && $role->users->count() === 0" />
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table-with-pagination>
@endsection
