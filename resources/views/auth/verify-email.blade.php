@extends('layouts.guest')

@section('title', 'Verificar Email')

@section('auth-nav')
    <a href="{{ route('login') }}" class="auth-nav-link">
        <i class="fas fa-sign-in-alt"></i>
        Entrar
    </a>
@endsection

@section('auth-icon')
    <i class="fas fa-envelope"></i>
@endsection

@section('auth-title')
    Verificar Email
@endsection

@section('auth-subtitle')
    Antes de continuar, verifique seu email clicando no link enviado
@endsection

@section('content')
    <div class="auth-info">
        <i class="fas fa-info-circle"></i>
        <p>Enviamos um link de verificação para o seu email. Clique no link para verificar sua conta.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-alert auth-alert-success">
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Email enviado!</strong>
                <p>Um novo link de verificação foi enviado para o email que você forneceu durante o registro.</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="auth-form">
        @csrf

        <button type="submit" class="auth-submit-btn">
            <i class="fas fa-paper-plane"></i>
            Reenviar Email de Verificação
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="auth-form">
        @csrf

        <button type="submit" class="auth-submit-btn auth-submit-btn-secondary">
            <i class="fas fa-sign-out-alt"></i>
            Sair
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

        .auth-submit-btn-secondary {
            background: var(--gray-600);
            margin-top: 0.5rem;
        }

        .auth-submit-btn-secondary:hover {
            background: var(--gray-700);
        }
    </style>
@endpush
