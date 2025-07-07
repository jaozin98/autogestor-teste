@extends('layouts.guest')

@section('title', 'Confirmar Senha')

@section('auth-nav')
    <a href="{{ route('welcome') }}" class="auth-nav-link">
        <i class="fas fa-home"></i>
        Início
    </a>
@endsection

@section('auth-icon')
    <i class="fas fa-shield-alt"></i>
@endsection

@section('auth-title')
    Confirmar Senha
@endsection

@section('auth-subtitle')
    Esta é uma área segura da aplicação. Confirme sua senha antes de continuar
@endsection

@section('content')
    <div class="auth-info">
        <i class="fas fa-info-circle"></i>
        <p>Esta é uma área segura da aplicação. Por favor, confirme sua senha antes de continuar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
        @csrf

        <div class="auth-form-group">
            <label for="password">
                <i class="fas fa-lock"></i>
                Senha
            </label>
            <input id="password" type="password" name="password"
                class="auth-form-control @error('password') error @enderror" placeholder="Digite sua senha" required
                autofocus autocomplete="current-password">
        </div>

        <button type="submit" class="auth-submit-btn">
            <i class="fas fa-check"></i>
            Confirmar
        </button>
    </form>

    <div class="auth-links">
        <a href="{{ route('welcome') }}" class="auth-link">
            <i class="fas fa-arrow-left"></i>
            Voltar para o início
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
