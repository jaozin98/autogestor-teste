@extends('layouts.app')

@section('title', 'Visualizar Permissão')

@push('styles')
    <x-crud-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-key"></i>
            Permissão: {{ $permission->name }}
        </h1>
        <p class="page-subtitle">Detalhes e informações desta permissão</p>
    </div>

    @include('components.alerts')

    <div class="content-grid">
        <!-- Informações da Permissão -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Informações da Permissão
                </h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">Nome:</label>
                        <span class="info-value">{{ $permission->name }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Tipo:</label>
                        <span class="info-value">
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
                        </span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Criada em:</label>
                        <span class="info-value">{{ $permission->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Atualizada em:</label>
                        <span class="info-value">{{ $permission->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <label class="info-label">Total de roles:</label>
                        <span class="info-value">{{ $permission->roles->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles que possuem esta Permissão -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-tag"></i>
                    Roles com esta Permissão
                </h3>
            </div>
            <div class="card-body">
                @if ($permission->roles->count() > 0)
                    <div class="roles-list">
                        @foreach ($permission->roles as $role)
                            <div class="role-item">
                                <div class="role-info">
                                    <strong>{{ ucfirst($role->name) }}</strong>
                                    @if ($role->name === 'admin')
                                        <span class="badge bg-danger">Administrador</span>
                                    @endif
                                </div>
                                <div class="role-stats">
                                    <small class="text-muted">
                                        {{ $role->users->count() }} usuários
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <p>Nenhuma role possui esta permissão.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ações -->
    <div class="action-bar">
        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i>
            Editar Permissão
        </a>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
        @if ($permission->roles->count() === 0)
            <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Tem certeza que deseja excluir esta permissão?')">
                    <i class="fas fa-trash"></i>
                    Excluir Permissão
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

        .roles-list {
            display: grid;
            gap: 0.75rem;
        }

        .role-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: var(--light-bg);
            border-radius: var(--border-radius);
        }

        .role-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
