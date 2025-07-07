@extends('layouts.app')

@section('title', 'Nova Categoria')

<x-form-styles />

@section('content')
    <x-page-header title="Nova Categoria" subtitle="Crie uma nova categoria para organizar seus produtos"
        icon="fas fa-plus-circle" :backRoute="'categories.index'" />

    <x-two-column-layout>
        <x-slot name="main">
            <x-form-card title="Informações da Categoria" icon="fas fa-edit">
                <form action="{{ route('categories.store') }}" method="POST" class="category-form">
                    @csrf

                    <x-form-field label="Nome da Categoria" name="name" type="text"
                        placeholder="Digite o nome da categoria" :required="true" icon="fas fa-tag"
                        helpText="O nome deve ser único e descritivo para facilitar a organização" autofocus />

                    <x-form-field label="Descrição" name="description" type="textarea"
                        placeholder="Descreva brevemente esta categoria (opcional)" icon="fas fa-align-left"
                        helpText="Uma descrição ajuda a identificar melhor o propósito da categoria" :rows="4" />

                    <x-form-field label="Categoria ativa" name="is_active" type="checkbox" icon="fas fa-check-circle"
                        :value="true" helpText="Categorias inativas não aparecerão nas listas de seleção de produtos" />

                    <x-form-actions submitText="Criar Categoria" submitIcon="fas fa-save" cancelRoute="categories.index" />
                </form>
            </x-form-card>
        </x-slot>

        <x-slot name="sidebar">
            <x-info-sidebar title="Dicas" icon="fas fa-info-circle">
                <x-tips-section :tips="[
                    [
                        'icon' => 'fas fa-lightbulb',
                        'title' => 'Nome Descritivo',
                        'description' => 'Use nomes claros e específicos que facilitem a identificação dos produtos.',
                    ],
                    [
                        'icon' => 'fas fa-sitemap',
                        'title' => 'Organização',
                        'description' => 'Crie categorias que agrupem produtos similares ou relacionados.',
                    ],
                    [
                        'icon' => 'fas fa-eye',
                        'title' => 'Visibilidade',
                        'description' => 'Categorias inativas ficam ocultas mas não são excluídas do sistema.',
                    ],
                ]" />
            </x-info-sidebar>
        </x-slot>
    </x-two-column-layout>
@endsection
