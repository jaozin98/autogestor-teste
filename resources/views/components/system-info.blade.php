@props([
    'user' => null,
    'title' => 'Informações do Sistema',
    'icon' => 'fas fa-info-circle',
])

@if ($user)
    <div class="system-info-card">
        <div class="card-header">
            <h4 class="card-title">
                <i class="{{ $icon }}"></i>
                {{ $title }}
            </h4>
        </div>
        <div class="card-body">
            <div class="system-info-grid">
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-hashtag"></i>
                        ID:
                    </span>
                    <span class="info-value">{{ $user->id }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-calendar-plus"></i>
                        Criado em:
                    </span>
                    <span class="info-value">{{ $user->created_at->format('d/m/Y H:i:s') }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-calendar-edit"></i>
                        Última atualização:
                    </span>
                    <span class="info-value">{{ $user->updated_at->format('d/m/Y H:i:s') }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-envelope-check"></i>
                        Email verificado:
                    </span>
                    <span class="info-value">
                        @if ($user->email_verified_at)
                            <span class="text-success">
                                <i class="fas fa-check-circle"></i>
                                {{ $user->email_verified_at->format('d/m/Y H:i:s') }}
                            </span>
                        @else
                            <span class="text-danger">
                                <i class="fas fa-times-circle"></i>
                                Não verificado
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <style>
        .system-info-card {
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .system-info-card .card-header {
            background-color: #f1f5f9;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .system-info-card .card-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }

        .system-info-card .card-title i {
            margin-right: 0.5rem;
            color: #6b7280;
        }

        .system-info-card .card-body {
            padding: 1rem;
        }

        .system-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0.75rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-label i {
            margin-right: 0.25rem;
        }

        .info-value {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }

        .info-value i {
            margin-right: 0.25rem;
        }

        @media (max-width: 768px) {
            .system-info-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .system-info-card .card-body {
                padding: 0.75rem;
            }
        }
    </style>
@endif
