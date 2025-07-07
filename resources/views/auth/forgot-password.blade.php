@extends('layouts.guest')

@section('title', 'Esqueci a Senha')

@section('auth-nav')
    <a href="{{ route('login') }}" class="auth-nav-link">
        <i class="fas fa-sign-in-alt"></i>
        Entrar
    </a>
    <a href="{{ route('register') }}" class="auth-nav-link">
        <i class="fas fa-user-plus"></i>
        Cadastrar
    </a>
@endsection

@section('auth-icon')
    <i class="fas fa-key"></i>
@endsection

@section('auth-title')
    Esqueci minha Senha
@endsection

@section('auth-subtitle')
    Digite seu e-mail e enviaremos um link para redefinir sua senha
@endsection

@section('content')
    <div class="auth-info">
        <i class="fas fa-info-circle"></i>
        <p>Não há problema! Basta nos informar seu endereço de e-mail e nós lhe enviaremos um link de redefinição de senha
            que permitirá escolher uma nova.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div class="auth-form-group">
            <label for="email">
                <i class="fas fa-envelope"></i>
                E-mail
            </label>
            <input id="email" type="email" name="email" class="auth-form-control @error('email') error @enderror"
                value="{{ old('email') }}" placeholder="Digite seu e-mail" required autofocus autocomplete="email">
        </div>

        <button type="submit" class="auth-submit-btn">
            <i class="fas fa-paper-plane"></i>
            Enviar Link de Redefinição
        </button>
    </form>

    <div class="auth-links">
        <a href="{{ route('login') }}" class="auth-link">
            <i class="fas fa-arrow-left"></i>
            Voltar para o login
        </a>
    </div>
@endsection

@push('styles')
    <style>
        .auth-info {
            background: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .auth-info i {
            color: var(--info-color);
            font-size: 1.1rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .auth-info p {
            margin: 0;
            color: var(--gray-700);
            font-size: 0.9rem;
            line-height: 1.5;
        }
    </style>
@endpush
