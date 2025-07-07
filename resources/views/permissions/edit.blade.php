@extends('layouts.app')

@section('title', 'Editar Permissão')

@push('styles')
    <x-crud-styles />
    <x-form-styles />
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-key"></i>
            Editar Permissão: {{ $permission->name }}
        </h1>
        <p class="page-subtitle">Modifique as informações desta permissão</p>
    </div>

    @include('components.alerts')

    <div class="form-container">
        <form action="{{ route('permissions.update', $permission) }}" method="POST" class="form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i>
                    Nome da Permissão
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Ex: user.create, product.edit, etc." required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                    Use o formato: recurso.ação (ex: user.create, product.edit, category.delete)
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Atualizar Permissão
                </button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Voltar
                </a>
            </div>
        </form>
    </div>
@endsection
