@extends('layouts.app')

@section('title', 'Editar Categoria')

<x-form-styles />

@section('content')
    <x-page-header title="Editar Categoria" subtitle="Atualize as informações da categoria \"{{ $category->name }}\""
        icon="fas fa-edit" :showViewButton="true" viewRoute="categories.show" :viewModel="$category" :backRoute="'categories.index'" />

    <x-two-column-layout>
        <x-slot name="main">
            <x-form-card title="Informações da Categoria" icon="fas fa-edit">
                <form action="{{ route('categories.update', $category) }}" method="POST" class="category-form">
                    @csrf
                    @method('PUT')

                    <x-form-field label="Nome da Categoria" name="name" type="text"
                        placeholder="Digite o nome da categoria" :required="true" icon="fas fa-tag"
                        helpText="O nome deve ser único e descritivo para facilitar a organização" :value="old('name', $category->name)"
                        autofocus />

                    <x-form-field label="Descrição" name="description" type="textarea"
                        placeholder="Descreva brevemente esta categoria (opcional)" icon="fas fa-align-left"
                        :value="old('description', $category->description)" helpText="Uma descrição ajuda a identificar melhor o propósito da categoria"
                        :rows="4" />

                    <x-form-field label="Categoria ativa" name="is_active" type="checkbox" icon="fas fa-check-circle"
                        :value="true" helpText="Categorias inativas não aparecerão nas listas de seleção de produtos" />

                    <x-form-actions submitText="Atualizar Categoria" submitIcon="fas fa-save"
                        cancelRoute="categories.index" />
                </form>
            </x-form-card>
        </x-slot>

        <x-slot name="sidebar">
            <x-info-sidebar title="Informações da Categoria" icon="fas fa-info-circle">
                <x-model-info :model="$category" :infoItems="[
                    [
                        'icon' => 'fas fa-hashtag',
                        'label' => 'ID',
                        'value' => '#' . $category->id,
                    ],
                    [
                        'icon' => 'fas fa-box',
                        'label' => 'Produtos',
                        'value' => ($category->products_count ?? 0) . ' produtos',
                        'badge' => [
                            'type' => ($category->products_count ?? 0) > 0 ? 'success' : 'warning',
                        ],
                    ],
                    [
                        'icon' => 'fas fa-calendar',
                        'label' => 'Criada em',
                        'value' => $category->created_at->format('d/m/Y H:i'),
                    ],
                    [
                        'icon' => 'fas fa-clock',
                        'label' => 'Última atualização',
                        'value' => $category->updated_at->format('d/m/Y H:i'),
                    ],
                    [
                        'icon' => 'fas fa-toggle-on',
                        'label' => 'Status',
                        'value' => $category->is_active ? 'Ativa' : 'Inativa',
                        'badge' => [
                            'type' => $category->is_active ? 'success' : 'warning',
                            'icon' => $category->is_active ? 'fas fa-check' : 'fas fa-pause',
                        ],
                    ],
                ]" />
            </x-info-sidebar>

            <x-info-sidebar title="Ações Rápidas" icon="fas fa-exclamation-triangle" class="mt-3">
                <div class="quick-actions">
                    <form action="{{ route('categories.toggle-status', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="btn btn-sm btn-{{ $category->is_active ? 'warning' : 'success' }} w-100 mb-2">
                            <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                            {{ $category->is_active ? 'Desativar' : 'Ativar' }} Categoria
                        </button>
                    </form>

                    <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info w-100 mb-2">
                        <i class="fas fa-eye"></i>
                        Visualizar Detalhes
                    </a>

                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100"
                            onclick="return confirm('Tem certeza que deseja excluir esta categoria?')">
                            <i class="fas fa-trash"></i>
                            Excluir Categoria
                        </button>
                    </form>
                </div>
            </x-info-sidebar>
        </x-slot>
    </x-two-column-layout>
@endsection

@push('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header-content h1 {
            margin: 0;
        }

        .page-header-content p {
            margin: 0.5rem 0 0 0;
            color: var(--text-muted);
        }

        .page-header-actions {
            display: flex;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-check {
            margin-bottom: 0.5rem;
        }

        .form-check-input {
            margin-right: 0.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .category-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-value {
            font-weight: 500;
        }

        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .page-header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-resize textarea
            const textarea = document.getElementById('description');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            }

            // Form validation
            const form = document.querySelector('.category-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const name = document.getElementById('name').value.trim();
                    if (!name) {
                        e.preventDefault();
                        alert('Por favor, preencha o nome da categoria.');
                        document.getElementById('name').focus();
                    }
                });
            }
        });
    </script>
@endpush
