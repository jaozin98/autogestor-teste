@extends('layouts.guest')

@section('title', 'Redefinir Senha')

@section('auth-icon')
    <i class="fas fa-key"></i>
@endsection

@section('auth-title', 'Redefinir Senha')

@section('auth-subtitle', 'Digite sua nova senha para redefinir sua conta')

@section('auth-nav')
    <a href="{{ route('login') }}" class="auth-nav-link">
        <i class="fas fa-sign-in-alt"></i>
        Login
    </a>
    <a href="{{ route('register') }}" class="auth-nav-link">
        <i class="fas fa-user-plus"></i>
        Registrar
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('password.update') }}" class="auth-form">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email -->
        <div class="auth-form-group">
            <label for="email" class="auth-form-label">
                <i class="fas fa-envelope"></i>
                Endereço de E-mail
            </label>
            <input id="email" type="email" class="auth-form-control @error('email') error @enderror" name="email"
                value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                placeholder="Digite seu e-mail">
            @error('email')
                <span class="auth-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nova Senha -->
        <div class="auth-form-group">
            <label for="password" class="auth-form-label">
                <i class="fas fa-lock"></i>
                Nova Senha
            </label>
            <div class="password-input-group">
                <input id="password" type="password" class="auth-form-control @error('password') error @enderror"
                    name="password" required autocomplete="new-password" placeholder="Digite sua nova senha">
                <x-password-toggle target="password" />
            </div>
            <x-password-strength target="password" />
            @error('password')
                <span class="auth-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Confirmar Senha -->
        <div class="auth-form-group">
            <label for="password-confirm" class="auth-form-label">
                <i class="fas fa-lock"></i>
                Confirmar Nova Senha
            </label>
            <div class="password-input-group">
                <input id="password-confirm" type="password" class="auth-form-control" name="password_confirmation" required
                    autocomplete="new-password" placeholder="Confirme sua nova senha">
                <x-password-toggle target="password-confirm" />
            </div>
            <x-password-confirmation password-id="password" confirm-id="password-confirm" />
        </div>

        <!-- Botão Submit -->
        <div class="auth-form-group">
            <button type="submit" class="auth-submit-btn">
                <i class="fas fa-key"></i>
                Redefinir Senha
            </button>
        </div>

        <!-- Links -->
        <div class="auth-links">
            <a href="{{ route('login') }}" class="auth-link">
                <i class="fas fa-arrow-left"></i>
                Voltar para o Login
            </a>
        </div>
    </form>
@endsection
