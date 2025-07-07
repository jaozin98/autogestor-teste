@extends('layouts.guest')

@section('title', 'Redefinir Senha')

@section('auth-nav')
    <a href="{{ route('login') }}" class="auth-nav-link">
        <i class="fas fa-sign-in-alt"></i>
        Entrar
    </a>
@endsection

@section('auth-icon')
    <i class="fas fa-lock"></i>
@endsection

@section('auth-title')
    Redefinir Senha
@endsection

@section('auth-subtitle')
    Digite sua nova senha para continuar
@endsection

@section('content')
    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-form-group">
            <label for="email">
                <i class="fas fa-envelope"></i>
                E-mail
            </label>
            <input id="email" type="email" name="email" class="auth-form-control @error('email') error @enderror"
                value="{{ old('email', $request->email) }}" placeholder="Digite seu e-mail" required autofocus
                autocomplete="username">
        </div>

        <div class="auth-form-group">
            <label for="password">
                <i class="fas fa-lock"></i>
                Nova Senha
            </label>
            <input id="password" type="password" name="password"
                class="auth-form-control @error('password') error @enderror" placeholder="Digite sua nova senha" required
                autocomplete="new-password">
            <x-password-strength />
        </div>

        <div class="auth-form-group">
            <label for="password_confirmation">
                <i class="fas fa-lock"></i>
                Confirmar Nova Senha
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="auth-form-control"
                placeholder="Confirme sua nova senha" required autocomplete="new-password">
            <x-password-toggle target="password_confirmation" />
            <x-password-confirmation />
        </div>

        <button type="submit" class="auth-submit-btn">
            <i class="fas fa-save"></i>
            Redefinir Senha
        </button>
    </form>

    <div class="auth-links">
        <a href="{{ route('login') }}" class="auth-link">
            <i class="fas fa-arrow-left"></i>
            Voltar para o login
        </a>
    </div>
@endsection
