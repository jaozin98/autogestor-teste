@extends('layouts.app')

@section('title', 'Editar Marca')

<x-form-styles />

@section('content')
    <x-page-header title="Editar Marca" subtitle="Atualize as informações da marca \"{{ $brand->name }}\"" icon="fas fa-edit"
        :showViewButton="true" viewRoute="brands.show" :viewModel="$brand" :backRoute="'brands.index'" />

    <x-two-column-layout>
        <x-slot name="main">
            <x-form-card title="Informações da Marca" icon="fas fa-edit">
                <form action="{{ route('brands.update', $brand) }}" method="POST" class="brand-form">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <x-form-field label="Nome da Marca" name="name" type="text"
                                placeholder="Ex: Apple, Samsung, Nike" :required="true" icon="fas fa-tag"
                                helpText="Nome único da marca (máximo 255 caracteres)" :value="old('name', $brand->name)" autofocus />
                        </div>
                        <div class="col-md-4">
                            <x-form-field label="Ano de Fundação" name="founded_year" type="number" placeholder="Ex: 1990"
                                icon="fas fa-calendar" :min="1800" :max="date('Y') + 1" :value="old('founded_year', $brand->founded_year)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="País de Origem" name="country_of_origin" type="text"
                                placeholder="Ex: Brasil, Estados Unidos, Alemanha" icon="fas fa-flag" :value="old('country_of_origin', $brand->country_of_origin)" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Website" name="website" type="url"
                                placeholder="https://www.exemplo.com" icon="fas fa-globe" :value="old('website', $brand->website)" />
                        </div>
                    </div>

                    <x-form-field label="Descrição" name="description" type="textarea"
                        placeholder="Descreva a marca, sua história, valores, etc..." icon="fas fa-align-left"
                        :value="old('description', $brand->description)" helpText="Descrição opcional da marca (máximo 1000 caracteres)"
                        :rows="4" />

                    <x-form-field label="Marca ativa" name="is_active" type="checkbox" icon="fas fa-check-circle"
                        :value="true" helpText="Marcas inativas não aparecerão nas listas de seleção de produtos" />

                    <x-form-actions submitText="Atualizar Marca" submitIcon="fas fa-save" cancelRoute="brands.index" />
                </form>
            </x-form-card>
        </x-slot>

        <x-slot name="sidebar">
            <x-info-sidebar title="Informações da Marca" icon="fas fa-info-circle">
                <x-model-info :model="$brand" :infoItems="[
                    [
                        'icon' => 'fas fa-hashtag',
                        'label' => 'ID',
                        'value' => '#' . $brand->id,
                    ],
                    [
                        'icon' => 'fas fa-box',
                        'label' => 'Produtos',
                        'value' => ($brand->products_count ?? 0) . ' produtos',
                        'badge' => [
                            'type' => ($brand->products_count ?? 0) > 0 ? 'success' : 'warning',
                        ],
                    ],
                    [
                        'icon' => 'fas fa-calendar',
                        'label' => 'Criada em',
                        'value' => $brand->created_at->format('d/m/Y H:i'),
                    ],
                    [
                        'icon' => 'fas fa-clock',
                        'label' => 'Última atualização',
                        'value' => $brand->updated_at->format('d/m/Y H:i'),
                    ],
                    [
                        'icon' => 'fas fa-toggle-on',
                        'label' => 'Status',
                        'value' => $brand->is_active ? 'Ativa' : 'Inativa',
                        'badge' => [
                            'type' => $brand->is_active ? 'success' : 'warning',
                            'icon' => $brand->is_active ? 'fas fa-check' : 'fas fa-pause',
                        ],
                    ],
                ]" />
            </x-info-sidebar>

            <x-info-sidebar title="Aviso" icon="fas fa-exclamation-triangle" class="mt-3">
                <div class="alert alert-warning mb-0">
                    <small>
                        <i class="fas fa-info-circle"></i>
                        <strong>Atenção:</strong> Alterações no nome da marca podem afetar produtos associados.
                    </small>
                </div>
            </x-info-sidebar>
        </x-slot>
    </x-two-column-layout>
@endsection
