@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('content')
    <div class="container mx-auto px-4 py-8">
        @include('components.page-header', [
            'title' => 'Detalhes do Usuário',
            'description' => 'Visualize as informações completas do usuário',
            'button' => [
                'text' => 'Voltar',
                'url' => route('users.index'),
                'type' => 'secondary',
            ],
        ])

        @include('components.alerts')

        <div class="max-w-4xl mx-auto">
            <!-- Informações Principais -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="flex space-x-2">
                        @can('users.edit')
                            <a href="{{ route('users.edit', $user) }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Editar
                            </a>
                        @endcan

                        @can('users.edit')
                            <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-4 py-2 {{ $user->email_verified_at ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                    {{ $user->email_verified_at ? 'Desativar' : 'Ativar' }}
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        @if ($user->email_verified_at)
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Ativo
                        @else
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Inativo
                        @endif
                    </span>
                </div>

                <!-- Informações Detalhadas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Básicas</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID</dt>
                                <dd class="text-sm text-gray-900">{{ $user->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email Verificado</dt>
                                <dd class="text-sm text-gray-900">
                                    @if ($user->email_verified_at)
                                        {{ $user->email_verified_at->format('d/m/Y H:i:s') }}
                                    @else
                                        <span class="text-red-600">Não verificado</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                                <dd class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i:s') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                                <dd class="text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i:s') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Membro desde</dt>
                                <dd class="text-sm text-gray-900">{{ $user->created_at->diffForHumans() }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            @if (auth()->user() && auth()->user()->hasRole('admin'))
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Roles e Permissões</h3>
                    @if ($user->roles->count() > 0)
                        <div class="space-y-4">
                            @foreach ($user->roles as $role)
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-md font-medium text-gray-900">
                                            {{ ucfirst($role->name) }}
                                            @if ($role->name === 'admin')
                                                <span
                                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Administrador</span>
                                            @endif
                                        </h4>
                                    </div>
                                    @if ($role->permissions->count() > 0)
                                        <div class="mt-3">
                                            <h5 class="text-sm font-medium text-gray-700 mb-2">Permissões:</h5>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($role->permissions as $permission)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500">Esta role não possui permissões específicas.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Este usuário não possui roles atribuídas.</p>
                    @endif
                </div>
            @endif

            <!-- Ações Rápidas -->
            @can('users.edit')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                    <div class="flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('users.reset-password', $user) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                onclick="return confirm('Tem certeza que deseja redefinir a senha deste usuário?')"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                Redefinir Senha
                            </button>
                        </form>

                        @can('users.delete')
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Excluir Usuário
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            @endcan
        </div>
    </div>

    @include('components.crud-styles')
@endsection
