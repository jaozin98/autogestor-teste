@extends('layouts.app')

@section('title', 'Criar Produto')

<x-form-styles />

@section('content')
    <x-page-header title="Criar Novo Produto" subtitle="Adicione um novo produto ao sistema" icon="fas fa-plus-circle"
        :backRoute="'products.index'" />

    <x-two-column-layout>
        <x-slot name="main">
            <x-form-card title="Informações do Produto" icon="fas fa-edit">
                <form action="{{ route('products.store') }}" method="POST" class="product-form">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Nome do Produto" name="name" type="text"
                                placeholder="Digite o nome do produto" :required="true" icon="fas fa-box"
                                helpText="Nome descritivo do produto" autofocus />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Preço" name="price" type="number" placeholder="0.00" :required="true"
                                icon="fas fa-dollar-sign" :step="0.01" :min="0"
                                helpText="Preço em reais (R$)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Categoria" name="category_id" type="select" :required="true"
                                icon="fas fa-tag" helpText="Selecione a categoria do produto" :options="$categories->pluck('name', 'id')->toArray()"
                                :selected="old('category_id')" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Marca" name="brand_id" type="select" icon="fas fa-trademark"
                                helpText="Selecione a marca do produto (opcional)" :options="$brands->pluck('name', 'id')->toArray()" :selected="old('brand_id')" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="SKU" name="sku" type="text" placeholder="Código do produto"
                                icon="fas fa-barcode" helpText="Código único do produto (opcional)" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Quantidade em Estoque" name="stock" type="number" placeholder="0"
                                icon="fas fa-warehouse" :min="0" :value="old('stock', 0)"
                                helpText="Quantidade disponível em estoque" />
                        </div>
                    </div>

                    <x-form-field label="Descrição" name="description" type="textarea"
                        placeholder="Descreva o produto, suas características, etc..." icon="fas fa-align-left"
                        helpText="Descrição detalhada do produto" :rows="4" />

                    <x-form-field label="Produto ativo" name="is_active" type="checkbox" icon="fas fa-check-circle"
                        :value="true" helpText="Produtos inativos não aparecerão nas listas" />

                    <x-form-actions submitText="Criar Produto" submitIcon="fas fa-save" cancelRoute="products.index" />
                </form>
            </x-form-card>
        </x-slot>

        <x-slot name="sidebar">
            <x-info-sidebar title="Dicas" icon="fas fa-info-circle">
                <x-tips-section :tips="[
                    [
                        'icon' => 'fas fa-box',
                        'title' => 'Nome Descritivo',
                        'description' => 'Use nomes claros que facilitem a identificação do produto.',
                    ],
                    [
                        'icon' => 'fas fa-tag',
                        'title' => 'Categorização',
                        'description' => 'Organize produtos em categorias para facilitar a busca.',
                    ],
                    [
                        'icon' => 'fas fa-barcode',
                        'title' => 'SKU Único',
                        'description' => 'Códigos SKU únicos ajudam no controle de estoque.',
                    ],
                    [
                        'icon' => 'fas fa-warehouse',
                        'title' => 'Controle de Estoque',
                        'description' => 'Mantenha o estoque atualizado para evitar problemas.',
                    ],
                ]" />
            </x-info-sidebar>
        </x-slot>
    </x-two-column-layout>
@endsection
