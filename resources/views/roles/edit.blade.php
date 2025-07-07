@extends('layouts.app')

@section('title', 'Editar Role')

@push('styles')
    <x-crud-styles />
    <x-form-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-tag"></i>
            Editar Role: {{ $role->name }}
        </h1>
        <p class="page-subtitle">Modifique as informações e permissões desta role</p>
    </div>

    @include('components.alerts')

    <div class="form-container">
        <form action="{{ route('roles.update', $role) }}" method="POST" class="form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Nome da Role
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}"
                    class="form-control @error('name') is-invalid @enderror" placeholder="Ex: editor, manager, etc."
                    required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-key"></i>
                    Permissões
                </label>
                <div class="permissions-grid">
                    @foreach ($permissions as $permission)
                        <div class="permission-item">
                            <label class="permission-checkbox">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
                                <span class="permission-name">{{ $permission->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Atualizar Role
                </button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Voltar
                </a>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .permission-item {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 0.75rem;
        }

        .permission-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .permission-checkbox input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
        }

        .permission-name {
            font-size: 0.875rem;
            color: var(--text-color);
        }
    </style>
@endpush
