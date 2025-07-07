@extends('layouts.app')

@section('title', 'Criar Usuário')

<x-form-styles />
<x-user-form-styles />

@section('content')
    <x-page-header title="Criar Usuário" subtitle="Adicione um novo usuário ao sistema" icon="fas fa-user-plus"
        backRoute="users.index" />

    <x-two-column-layout>
        <x-slot name="main">
            <x-form-card title="Informações do Usuário" icon="fas fa-user-edit">
                <form method="POST" action="{{ route('users.store') }}" class="user-form">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Nome Completo" name="name" type="text"
                                placeholder="Nome completo do usuário" :required="true" icon="fas fa-user"
                                helpText="Nome completo do usuário (máximo 255 caracteres)" :value="old('name')" autofocus />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Email" name="email" type="email" placeholder="email@exemplo.com"
                                :required="true" icon="fas fa-envelope" helpText="Email único para login no sistema"
                                :value="old('email')" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Senha" name="password" type="password" placeholder="Mínimo 8 caracteres"
                                :required="true" icon="fas fa-lock" helpText="Senha deve ter pelo menos 8 caracteres"
                                minlength="8" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Confirmar Senha" name="password_confirmation" type="password"
                                placeholder="Confirme a senha" :required="true" icon="fas fa-lock"
                                helpText="Confirme a senha digitada acima" minlength="8" />
                        </div>
                    </div>

                    <x-form-field label="Usuário ativo" name="is_active" type="checkbox" icon="fas fa-check-circle"
                        :value="old('is_active', true)" helpText="Usuários inativos não podem fazer login no sistema" />

                    <x-role-selector :roles="$roles" :selectedRoles="old('roles', [])" />

                    <x-form-actions submitText="Criar Usuário" submitIcon="fas fa-user-plus" cancelRoute="users.index" />
                </form>
            </x-form-card>
        </x-slot>

        <x-slot name="sidebar">
            <x-info-sidebar title="Dicas" icon="fas fa-info-circle">
                <x-tips-section :tips="[
                    [
                        'icon' => 'fas fa-user',
                        'title' => 'Nome Completo',
                        'description' => 'Use o nome completo do usuário para facilitar a identificação.',
                    ],
                    [
                        'icon' => 'fas fa-envelope',
                        'title' => 'Email Único',
                        'description' => 'Cada usuário deve ter um email único no sistema.',
                    ],
                    [
                        'icon' => 'fas fa-lock',
                        'title' => 'Senha Segura',
                        'description' => 'Use senhas fortes com pelo menos 8 caracteres.',
                    ],
                    [
                        'icon' => 'fas fa-user-tag',
                        'title' => 'Roles',
                        'description' => 'Selecione as permissões adequadas para cada usuário.',
                    ],
                    [
                        'icon' => 'fas fa-check-circle',
                        'title' => 'Status Ativo',
                        'description' => 'Usuários inativos não podem acessar o sistema.',
                    ],
                ]" />
            </x-info-sidebar>
        </x-slot>
    </x-two-column-layout>
@endsection
