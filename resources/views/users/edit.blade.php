@extends('layouts.app')

@section('title', 'Editar Usuário')

<x-form-styles />
<x-user-form-styles />

@section('content')
    <x-page-header title="Editar Usuário" subtitle="Modifique as informações do usuário" icon="fas fa-user-edit"
        backRoute="users.index" />

    <x-two-column-layout>
        <x-slot name="main">
            <x-form-card title="Informações do Usuário" icon="fas fa-user-edit">
                <form method="POST" action="{{ route('users.update', $user) }}" class="user-form">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Nome Completo" name="name" type="text"
                                placeholder="Nome completo do usuário" :required="true" icon="fas fa-user"
                                helpText="Nome completo do usuário (máximo 255 caracteres)" :value="old('name', $user->name)" autofocus />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Email" name="email" type="email" placeholder="email@exemplo.com"
                                :required="true" icon="fas fa-envelope" helpText="Email único para login no sistema"
                                :value="old('email', $user->email)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form-field label="Nova Senha (opcional)" name="password" type="password"
                                placeholder="Deixe em branco para manter a atual" :required="false" icon="fas fa-lock"
                                helpText="Deixe em branco para manter a senha atual" minlength="8" />
                        </div>
                        <div class="col-md-6">
                            <x-form-field label="Confirmar Nova Senha" name="password_confirmation" type="password"
                                placeholder="Confirme a nova senha" :required="false" icon="fas fa-lock"
                                helpText="Confirme a nova senha digitada acima" minlength="8" />
                        </div>
                    </div>

                    <x-form-field label="Usuário ativo" name="is_active" type="checkbox" icon="fas fa-check-circle"
                        :value="!is_null($user->email_verified_at)" helpText="Usuários inativos não podem fazer login no sistema" />

                    <x-role-selector :roles="$roles" :selectedRoles="old('roles', $user->roles->pluck('name')->toArray())" />

                    <x-form-actions submitText="Atualizar Usuário" submitIcon="fas fa-save" cancelRoute="users.index" />
                </form>
            </x-form-card>

            <x-system-info :user="$user" />
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
                        'title' => 'Senha Opcional',
                        'description' => 'Deixe em branco para manter a senha atual do usuário.',
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
                    [
                        'icon' => 'fas fa-info-circle',
                        'title' => 'Informações do Sistema',
                        'description' => 'Visualize dados como ID, datas de criação e verificação de email.',
                    ],
                ]" />
            </x-info-sidebar>
        </x-slot>
    </x-two-column-layout>
@endsection
