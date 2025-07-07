@extends('layouts.guest')

@section('title', 'Esqueci Minha Senha')

@section('auth-icon')
    <i class="fas fa-envelope"></i>
@endsection

@section('auth-title', 'Esqueci Minha Senha')

@section('auth-subtitle', 'Digite seu e-mail para receber um link de redefinição')

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
    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <!-- Email -->
        <div class="auth-form-group">
            <label for="email" class="auth-form-label">
                <i class="fas fa-envelope"></i>
                Endereço de E-mail
            </label>
            <input id="email" type="email" class="auth-form-control @error('email') error @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Digite seu e-mail">
            @error('email')
                <span class="auth-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Botão Submit -->
        <div class="auth-form-group">
            <button type="submit" class="auth-submit-btn">
                <i class="fas fa-paper-plane"></i>
                Enviar Link de Redefinição
            </button>
        </div>

        <!-- Links -->
        <div class="auth-links">
            <a href="{{ route('login') }}" class="auth-link">
                <i class="fas fa-arrow-left"></i>
                Voltar para o Login
            </a>
            <a href="{{ route('register') }}" class="auth-link">
                <i class="fas fa-user-plus"></i>
                Criar Nova Conta
            </a>
        </div>
    </form>
@endsection
