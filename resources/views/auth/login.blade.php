@extends('layouts.guest')

@section('title', 'Login')

@section('auth-nav')
    <a href="{{ route('register') }}" class="auth-nav-link">
        <i class="fas fa-user-plus"></i>
        Cadastrar
    </a>
@endsection

@section('auth-icon')
    <i class="fas fa-sign-in-alt"></i>
@endsection

@section('auth-title')
    Entrar no Sistema
@endsection

@section('auth-subtitle')
    Faça login para acessar sua conta e gerenciar seus produtos
@endsection

@section('content')
    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="auth-form-group">
            <label for="email">
                <i class="fas fa-envelope"></i>
                E-mail
            </label>
            <input id="email" type="email" name="email" class="auth-form-control @error('email') error @enderror"
                value="{{ old('email') }}" placeholder="Digite seu e-mail" required autofocus autocomplete="email">
        </div>

        <div class="auth-form-group">
            <label for="password">
                <i class="fas fa-lock"></i>
                Senha
            </label>
            <input id="password" type="password" name="password"
                class="auth-form-control @error('password') error @enderror" placeholder="Digite sua senha" required
                autocomplete="current-password">
            <x-password-toggle target="password" />
        </div>

        <div class="auth-checkbox-group">
            <input type="checkbox" name="remember" id="remember" class="auth-checkbox"
                {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" class="auth-checkbox-label">
                Lembrar de mim
            </label>
        </div>

        <button type="submit" class="auth-submit-btn">
            <i class="fas fa-sign-in-alt"></i>
            Entrar
        </button>
    </form>

    <div class="auth-links">
        <a href="{{ route('password.request') }}" class="auth-link">
            <i class="fas fa-key"></i>
            Esqueceu sua senha?
        </a>

        <div class="auth-divider">
            <span>ou</span>
        </div>

        <a href="{{ route('register') }}" class="auth-link">
            <i class="fas fa-user-plus"></i>
            Criar nova conta
        </a>
    </div>
@endsection
