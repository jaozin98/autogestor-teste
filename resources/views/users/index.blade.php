@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@push('styles')
    <x-crud-styles />
@endpush

@section('content')

    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-users"></i>
            Gestão de Usuários
        </h1>
        <p class="page-subtitle">Visualize e gerencie todos os usuários do sistema</p>
    </div>

    @include('components.alerts')

    <!-- Estatísticas -->
    <x-stats-cards :stats="$stats" title="Estatísticas de Usuários" icon="users" />

    <!-- Barra de Ações -->
    <x-action-bar :createRoute="route('users.create')" createText="Novo Usuário" :showSearch="false" />

    <!-- Filtros Avançados -->
    <x-advanced-filters :route="route('users.index')" :search="$search" :perPage="$perPage" :filters="[
        [
            'name' => 'role',
            'label' => 'Filtrar por Role',
            'icon' => 'user-tag',
            'placeholder' => 'Todas as roles',
            'options' => [
                'admin' => 'Administrador',
                'user' => 'Usuário',
            ],
        ],
    ]" />

    <!-- Tabela de Usuários -->
    <x-data-table-with-pagination :items="$users" title="Lista de Usuários" icon="users" :search="$search" emptyIcon="user"
        emptyTitle="Nenhum usuário encontrado" emptyMessage="Comece criando seu primeiro usuário." :createRoute="route('users.create')">
        <x-slot name="header">
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Status</th>
            <th>Roles</th>
            <th>Criado em</th>
            <th>Ações</th>
        </x-slot>

        <x-slot name="body">
            @foreach ($users as $user)
                <tr>
                    <td>
                        <span class="badge bg-secondary">#{{ $user->id }}</span>
                    </td>
                    <td>
                        <div class="user-name">
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="text-muted">{{ $user->email }}</span>
                    </td>
                    <td>
                        @if ($user->email_verified_at)
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Verificado
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-clock"></i> Pendente
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($user->roles->count() > 0)
                            @foreach ($user->roles as $role)
                                <span class="badge bg-info">{{ $role->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Sem roles</span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td>
                        <x-table-actions-extended :item="$user" :showRoute="route('users.show', $user)" :editRoute="route('users.edit', $user)"
                            :toggleRoute="route('users.toggle-status', $user)" :deleteRoute="route('users.destroy', $user)" itemName="usuário" />
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table-with-pagination>
@endsection

@push('styles')
    <style>
        .user-name {
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
