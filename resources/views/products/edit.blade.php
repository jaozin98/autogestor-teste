@extends('layouts.app')

@section('title', 'Editar Produto')

<x-form-styles />

@section('content')
    <x-page-header title="Editar Produto" subtitle="Atualize as informações do produto \"{{ $product->name }}\""
        icon="fas fa-edit" :showViewButton="true" viewRoute="products.show" :viewModel="$product" :backRoute="'products.index'" />

    <x-two-column-layout>
        <x-slot name="main">
            <x-form-card title="Informações do Produto" icon="fas fa-edit">
                <form action="{{ route('products.update', $product) }}" method="POST" class="product-form">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Nome do Produto" name="name" type="text"
                                placeholder="Digite o nome do produto" :required="true" icon="fas fa-box"
                                helpText="Nome descritivo do produto" :value="old('name', $product->name)" autofocus />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Preço" name="price" type="number" placeholder="0.00" :required="true"
                                icon="fas fa-dollar-sign" :step="0.01" :min="0" :value="old('price', $product->price)"
                                helpText="Preço em reais (R$)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Categoria" name="category_id" type="select" :required="true"
                                icon="fas fa-tag" helpText="Selecione a categoria do produto" :options="$categories->pluck('name', 'id')->toArray()"
                                :selected="old('category_id', $product->category_id)" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Marca" name="brand_id" type="select" icon="fas fa-trademark"
                                helpText="Selecione a marca do produto (opcional)" :options="$brands->pluck('name', 'id')->toArray()" :selected="old('brand_id', $product->brand_id)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="SKU" name="sku" type="text" placeholder="Código do produto"
                                icon="fas fa-barcode" :value="old('sku', $product->sku)" helpText="Código único do produto (opcional)" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Quantidade em Estoque" name="stock" type="number" placeholder="0"
                                icon="fas fa-warehouse" :min="0" :value="old('stock', $product->stock)"
                                helpText="Quantidade disponível em estoque" />
                        </div>
                    </div>

                    <x-form-field label="Descrição" name="description" type="textarea"
                        placeholder="Descreva o produto, suas características, etc..." icon="fas fa-align-left"
                        :value="old('description', $product->description)" helpText="Descrição detalhada do produto" :rows="4" />

                    <x-form-field label="Produto ativo" name="is_active" type="checkbox" icon="fas fa-check-circle"
                        :value="true" helpText="Produtos inativos não aparecerão nas listas" />

                    <x-form-actions submitText="Atualizar Produto" submitIcon="fas fa-save" cancelRoute="products.index" />
                </form>
            </x-form-card>
        </x-slot>

        <x-slot name="sidebar">
            <x-info-sidebar title="Informações do Produto" icon="fas fa-info-circle">
                <x-model-info :model="$product" :infoItems="[
                    [
                        'icon' => 'fas fa-hashtag',
                        'label' => 'ID',
                        'value' => '#' . $product->id,
                    ],
                    [
                        'icon' => 'fas fa-tag',
                        'label' => 'Categoria',
                        'value' => $product->category->name ?? 'N/A',
                    ],
                    [
                        'icon' => 'fas fa-trademark',
                        'label' => 'Marca',
                        'value' => $product->brand->name ?? 'N/A',
                    ],
                    [
                        'icon' => 'fas fa-calendar',
                        'label' => 'Criado em',
                        'value' => $product->created_at->format('d/m/Y H:i'),
                    ],
                    [
                        'icon' => 'fas fa-clock',
                        'label' => 'Última atualização',
                        'value' => $product->updated_at->format('d/m/Y H:i'),
                    ],
                    [
                        'icon' => 'fas fa-toggle-on',
                        'label' => 'Status',
                        'value' => $product->is_active ? 'Ativo' : 'Inativo',
                        'badge' => [
                            'type' => $product->is_active ? 'success' : 'warning',
                            'icon' => $product->is_active ? 'fas fa-check' : 'fas fa-pause',
                        ],
                    ],
                ]" />
            </x-info-sidebar>

            <x-info-sidebar title="Aviso" icon="fas fa-exclamation-triangle" class="mt-3">
                <div class="alert alert-warning mb-0">
                    <small>
                        <i class="fas fa-info-circle"></i>
                        <strong>Atenção:</strong> Alterações no produto podem afetar relatórios e histórico.
                    </small>
                </div>
            </x-info-sidebar>
        </x-slot>
    </x-two-column-layout>
@endsection
