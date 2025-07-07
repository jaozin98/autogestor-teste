@extends('layouts.app')

@section('title', 'Nova Marca')

<x-form-styles />

@section('content')
    <x-page-header title="Nova Marca" subtitle="Crie uma nova marca para organizar seus produtos" icon="fas fa-plus-circle"
        :backRoute="'brands.index'" />

    <x-two-column-layout>
        <x-slot name="main">
            <x-form-card title="Informações da Marca" icon="fas fa-edit">
                <form action="{{ route('brands.store') }}" method="POST" class="brand-form">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <x-form-field label="Nome da Marca" name="name" type="text"
                                placeholder="Ex: Apple, Samsung, Nike" :required="true" icon="fas fa-tag"
                                helpText="Nome único da marca (máximo 255 caracteres)" autofocus />
                        </div>
                        <div class="col-md-4">
                            <x-form-field label="Ano de Fundação" name="founded_year" type="number" placeholder="Ex: 1990"
                                icon="fas fa-calendar" :min="1800" :max="date('Y') + 1" :value="old('founded_year')" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="País de Origem" name="country_of_origin" type="text"
                                placeholder="Ex: Brasil, Estados Unidos, Alemanha" icon="fas fa-flag" :value="old('country_of_origin')" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Website" name="website" type="url"
                                placeholder="https://www.exemplo.com" icon="fas fa-globe" :value="old('website')" />
                        </div>
                    </div>

                    <x-form-field label="Descrição" name="description" type="textarea"
                        placeholder="Descreva a marca, sua história, valores, etc..." icon="fas fa-align-left"
                        :value="old('description')" helpText="Descrição opcional da marca (máximo 1000 caracteres)"
                        :rows="4" />

                    <x-form-field label="Marca ativa" name="is_active" type="checkbox" icon="fas fa-check-circle"
                        :value="true" helpText="Marcas inativas não aparecerão nas listas de seleção de produtos" />

                    <x-form-actions submitText="Criar Marca" submitIcon="fas fa-save" cancelRoute="brands.index" />
                </form>
            </x-form-card>
        </x-slot>

        <x-slot name="sidebar">
            <x-info-sidebar title="Dicas" icon="fas fa-info-circle">
                <x-tips-section :tips="[
                    [
                        'icon' => 'fas fa-lightbulb',
                        'title' => 'Nome Único',
                        'description' => 'Cada marca deve ter um nome único para evitar confusões.',
                    ],
                    [
                        'icon' => 'fas fa-globe',
                        'title' => 'Website',
                        'description' => 'Use URLs completas com http:// ou https:// para facilitar o acesso.',
                    ],
                    [
                        'icon' => 'fas fa-calendar',
                        'title' => 'Ano de Fundação',
                        'description' => 'Deve ser entre 1800 e o ano atual para manter a precisão histórica.',
                    ],
                    [
                        'icon' => 'fas fa-align-left',
                        'title' => 'Descrição',
                        'description' => 'Opcional, mas ajuda na identificação e compreensão da marca.',
                    ],
                ]" />
            </x-info-sidebar>
        </x-slot>
    </x-two-column-layout>
@endsection
