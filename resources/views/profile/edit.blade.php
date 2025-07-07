@extends('layouts.app')

@section('title', 'Perfil do Usuário')

@section('content')
    <div class="container mx-auto px-4 py-8">
        @include('components.page-header', [
            'title' => 'Perfil do Usuário',
            'description' => 'Gerencie suas informações pessoais e configurações de segurança',
            'button' => [
                'text' => 'Voltar',
                'url' => route('home'),
                'type' => 'secondary',
            ],
        ])

        @include('components.alerts')

        <!-- Informações do Usuário -->
        <div class="card mb-6">
            <div class="card-body">
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full d-flex align-items-center justify-content-center">
                        <span class="text-blue-600 text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                        <p class="text-gray-600 mb-2">{{ $user->email }}</p>
                        <div class="d-flex align-items-center gap-3">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                @if ($user->email_verified_at)
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Conta Ativa
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Conta Inativa
                                @endif
                            </span>
                            <span class="text-sm text-gray-500">
                                Membro desde {{ $user->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Roles do Usuário -->
                @if ($user->roles->count() > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Suas Permissões:</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($user->roles as $role)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($role->name) }}
                                    @if ($role->name === 'admin')
                                        <span class="ml-1 text-red-600">(Administrador)</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Grid Responsivo -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informações do Perfil -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit"></i>
                        Informações do Perfil
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-sm text-gray-600 mb-4">
                        Atualize suas informações pessoais. Você pode alterar seu nome e email.
                    </p>
                    <x-profile-form :user="$user" />
                </div>
            </div>

            <!-- Alterar Senha -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock"></i>
                        Alterar Senha
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-sm text-gray-600 mb-4">
                        Mantenha sua conta segura usando uma senha forte e única.
                    </p>
                    <x-password-form :user="$user" />
                </div>
            </div>
        </div>

        <!-- Excluir Conta -->
        <div class="mt-8">
            <div class="card border-red-200">
                <div class="card-header bg-red-50">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="card-title text-red-800">
                                Excluir Conta
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-sm text-gray-600 mb-4">
                        <p>Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente
                            excluídos.
                            Esta ação não pode ser desfeita.</p>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    @include('components.form-styles')
@endsection
