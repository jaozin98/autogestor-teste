@extends('layouts.app')

@section('title', 'Visualizar Role')

@push('styles')
    <x-crud-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-tag"></i>
            Role: {{ $role->name }}
        </h1>
        <p class="page-subtitle">Detalhes e informações desta role</p>
    </div>

    @include('components.alerts')

    <div class="content-grid">
        <!-- Informações da Role -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Informações da Role
                </h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">Nome:</label>
                        <span class="info-value">
                            {{ ucfirst($role->name) }}
                            @if ($role->name === 'admin')
                                <span class="badge bg-danger">Administrador</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Criada em:</label>
                        <span class="info-value">{{ $role->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Atualizada em:</label>
                        <span class="info-value">{{ $role->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Total de permissões:</label>
                        <span class="info-value">{{ $role->permissions->count() }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Total de usuários:</label>
                        <span class="info-value">{{ $role->users->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissões da Role -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-key"></i>
                    Permissões Atribuídas
                </h3>
            </div>
            <div class="card-body">
                @if ($role->permissions->count() > 0)
                    <div class="permissions-list">
                        @foreach ($role->permissions as $permission)
                            <div class="permission-badge">
                                <i class="fas fa-check-circle text-success"></i>
                                {{ $permission->name }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <p>Esta role não possui permissões atribuídas.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Usuários com esta Role -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users"></i>
                    Usuários com esta Role
                </h3>
            </div>
            <div class="card-body">
                @if ($role->users->count() > 0)
                    <div class="users-list">
                        @foreach ($role->users as $user)
                            <div class="user-item">
                                <div class="user-info">
                                    <strong>{{ $user->name }}</strong>
                                    <span class="text-muted">{{ $user->email }}</span>
                                </div>
                                <div class="user-status">
                                    @if ($user->email_verified_at)
                                        <span class="badge bg-success">Verificado</span>
                                    @else
                                        <span class="badge bg-warning">Pendente</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-user-slash text-muted"></i>
                        <p>Nenhum usuário possui esta role.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ações -->
    <div class="action-bar">
        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i>
            Editar Role
        </a>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
        @if ($role->name !== 'admin' && $role->users->count() === 0)
            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Tem certeza que deseja excluir esta role?')">
                    <i class="fas fa-trash"></i>
                    Excluir Role
                </button>
            </form>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .content-grid {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-grid {
            display: grid;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: var(--light-bg);
            border-radius: var(--border-radius);
        }

        .info-label {
            font-weight: 600;
            color: var(--text-color);
        }

        .info-value {
            color: var(--text-color);
        }

        .permissions-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .permission-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: var(--light-bg);
            border-radius: var(--border-radius);
            font-size: 0.875rem;
        }

        .users-list {
            display: grid;
            gap: 0.75rem;
        }

        .user-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: var(--light-bg);
            border-radius: var(--border-radius);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .action-bar {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-start;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }
    </style>
@endpush
