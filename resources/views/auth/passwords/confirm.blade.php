@extends('layouts.guest')

@section('title', 'Confirmar Senha')

@section('auth-icon')
    <i class="fas fa-shield-alt"></i>
@endsection

@section('auth-title', 'Confirmar Senha')

@section('auth-subtitle', 'Por favor, confirme sua senha antes de continuar')

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
    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf

        <!-- Senha -->
        <div class="auth-form-group">
            <label for="password" class="auth-form-label">
                <i class="fas fa-lock"></i>
                Senha
            </label>
            <div class="password-input-group">
                <input id="password" type="password" class="auth-form-control @error('password') error @enderror"
                    name="password" required autocomplete="current-password" placeholder="Digite sua senha">
                <x-password-toggle target="password" />
            </div>
            @error('password')
                <span class="auth-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Botão Submit -->
        <div class="auth-form-group">
            <button type="submit" class="auth-submit-btn">
                <i class="fas fa-check"></i>
                Confirmar Senha
            </button>
        </div>

        <!-- Links -->
        <div class="auth-links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">
                    <i class="fas fa-question-circle"></i>
                    Esqueceu sua senha?
                </a>
            @endif
            <a href="{{ route('login') }}" class="auth-link">
                <i class="fas fa-arrow-left"></i>
                Voltar para o Login
            </a>
        </div>
    </form>
@endsection
