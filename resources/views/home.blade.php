@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-tachometer-alt"></i>
            Bem-vindo ao AutoGestor!
        </h1>
        <p class="page-subtitle">Sistema de gerenciamento de produtos, categorias e marcas</p>
    </div>

    @unless (Auth::user()->hasAnyRole(['admin', 'super_admin']))
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="card-content">
                    <h3>Produtos</h3>
                    <p>Gerencie seu catálogo de produtos, preços e categorias</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        Gerenciar Produtos
                    </a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="card-content">
                    <h3>Categorias</h3>
                    <p>Organize seus produtos em categorias bem definidas</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-success">
                        <i class="fas fa-arrow-right"></i>
                        Gerenciar Categorias
                    </a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">
                    <i class="fas fa-copyright"></i>
                </div>
                <div class="card-content">
                    <h3>Marcas</h3>
                    <p>Controle as marcas e fabricantes dos seus produtos</p>
                    <a href="{{ route('brands.index') }}" class="btn btn-warning">
                        <i class="fas fa-arrow-right"></i>
                        Gerenciar Marcas
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="admin-notice">
            <div class="notice-card">
                <div class="notice-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="notice-content">
                    <h3>Acesso Administrativo</h3>
                    <p>Como administrador, você tem acesso limitado ao sistema. As funcionalidades de gerenciamento de produtos,
                        categorias e marcas estão disponíveis para outros usuários.</p>
                </div>
            </div>
        </div>
    @endunless

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle"></i>
                Informações do Sistema
            </h3>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <i class="fas fa-user"></i>
                    <div>
                        <strong>Usuário:</strong> {{ Auth::user()->name }}
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email:</strong> {{ Auth::user()->email }}
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Último acesso:</strong> {{ now()->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .dashboard-card {
            background: var(--white);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow);
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-color);
        }

        .card-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .card-content h3 {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-content p {
            color: var(--gray-600);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--gray-100);
            border-radius: var(--border-radius);
        }

        .info-item i {
            font-size: 1.5rem;
            color: var(--primary-color);
            width: 2rem;
            text-align: center;
        }

        .info-item div {
            color: var(--gray-700);
        }

        .admin-notice {
            margin-bottom: 2rem;
        }

        .notice-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow-xl);
        }

        .notice-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .notice-content h3 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .notice-content p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.95;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .notice-card {
                padding: 1.5rem;
            }

            .notice-content h3 {
                font-size: 1.5rem;
            }

            .notice-content p {
                font-size: 1rem;
            }
        }
    </style>
@endpush
