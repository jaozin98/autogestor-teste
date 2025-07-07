@extends('layouts.guest')

@section('title', 'Cadastro')

@section('auth-nav')
    <a href="{{ route('login') }}" class="auth-nav-link">
        <i class="fas fa-sign-in-alt"></i>
        Entrar
    </a>
@endsection

@section('auth-icon')
    <i class="fas fa-user-plus"></i>
@endsection

@section('auth-title')
    Criar Nova Conta
@endsection

@section('auth-subtitle')
    Cadastre-se para começar a gerenciar seus produtos
@endsection

@section('content')
    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="auth-form-group">
            <label for="name">
                <i class="fas fa-user"></i>
                Nome Completo
            </label>
            <input id="name" type="text" name="name" class="auth-form-control @error('name') error @enderror"
                value="{{ old('name') }}" placeholder="Digite seu nome completo" required autofocus autocomplete="name">
        </div>

        <div class="auth-form-group">
            <label for="email">
                <i class="fas fa-envelope"></i>
                E-mail
            </label>
            <input id="email" type="email" name="email" class="auth-form-control @error('email') error @enderror"
                value="{{ old('email') }}" placeholder="Digite seu e-mail" required autocomplete="email">
        </div>

        <div class="auth-form-group">
            <label for="password">
                <i class="fas fa-lock"></i>
                Senha
            </label>
            <input id="password" type="password" name="password"
                class="auth-form-control @error('password') error @enderror" placeholder="Crie uma senha forte" required
                autocomplete="new-password">
            <x-password-strength />
        </div>

        <div class="auth-form-group">
            <label for="password_confirmation">
                <i class="fas fa-lock"></i>
                Confirmar Senha
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="auth-form-control"
                placeholder="Confirme sua senha" required autocomplete="new-password">
            <x-password-toggle target="password_confirmation" />
            <x-password-confirmation />
        </div>

        <button type="submit" class="auth-submit-btn">
            <i class="fas fa-user-plus"></i>
            Criar Conta
        </button>
    </form>

    <div class="auth-links auth-link-no-top">
        <div class="auth-divider">
            <span>ou</span>
        </div>

        <a href="{{ route('login') }}" class="auth-link">
            <i class="fas fa-sign-in-alt"></i>
            Já possui uma conta? Entrar
        </a>
    </div>
@endsection



@push('styles')
    <style>
        .auth-link-no-top {
            border-top: none;
            margin-top: 0;
            padding-top: 0;
        }
    </style>
@endpush
