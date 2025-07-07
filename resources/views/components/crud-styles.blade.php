<style>
    :root {
        /* Cores */
        --primary-color: #3b82f6;
        --secondary-color: #64748b;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #06b6d4;

        /* Cores de texto */
        --text-color: #1f2937;
        --text-muted: #6b7280;
        --white: #ffffff;

        /* Cores de fundo */
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-600: #4b5563;

        /* Bordas */
        --border-color: #e5e7eb;
        --border-radius: 0.375rem;
        --border-radius-sm: 0.25rem;
    }

    /* Estilos globais para cards */
    .card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        background-color: var(--gray-50);
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Estilos para botões */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.5;
        border-radius: var(--border-radius);
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        gap: 0.5rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: var(--white);
    }

    .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .btn-secondary {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        color: var(--white);
    }

    .btn-secondary:hover {
        background-color: #475569;
        border-color: #475569;
    }

    /* Estilos para formulários */
    .form-control {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: var(--text-color);
        background-color: var(--white);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .form-select {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: var(--text-color);
        background-color: var(--white);
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        appearance: none;
    }

    /* Estilos para badges */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: var(--border-radius-sm);
        color: var(--white);
    }

    .bg-primary {
        background-color: var(--primary-color) !important;
    }

    .bg-secondary {
        background-color: var(--secondary-color) !important;
    }

    .bg-success {
        background-color: var(--success-color) !important;
    }

    .bg-warning {
        background-color: var(--warning-color) !important;
    }

    .bg-danger {
        background-color: var(--danger-color) !important;
    }

    .bg-info {
        background-color: var(--info-color) !important;
    }

    /* Estilos para texto */
    .text-muted {
        color: var(--text-muted) !important;
    }

    .text-decoration-none {
        text-decoration: none !important;
    }

    /* Utilitários */
    .d-inline {
        display: inline !important;
    }

    .d-flex {
        display: flex !important;
    }

    .justify-content-center {
        justify-content: center !important;
    }

    .align-items-center {
        align-items: center !important;
    }

    .gap-1 {
        gap: 0.25rem !important;
    }

    .gap-2 {
        gap: 0.5rem !important;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    .mb-1 {
        margin-bottom: 0.25rem !important;
    }

    .mb-2 {
        margin-bottom: 0.5rem !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .mt-1 {
        margin-top: 0.25rem !important;
    }

    .mt-2 {
        margin-top: 0.5rem !important;
    }

    .mt-3 {
        margin-top: 1rem !important;
    }

    .p-0 {
        padding: 0 !important;
    }

    .p-1 {
        padding: 0.25rem !important;
    }

    .p-2 {
        padding: 0.5rem !important;
    }

    .p-3 {
        padding: 1rem !important;
    }

    .w-100 {
        width: 100% !important;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .card-header {
            padding: 0.75rem 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }

        .form-control,
        .form-select {
            font-size: 16px;
            /* Previne zoom no iOS */
        }
    }

    /* Mobile */
    @media (max-width: 480px) {
        .card-header {
            padding: 0.5rem 0.75rem;
        }

        .card-body {
            padding: 0.75rem;
        }

        .btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            min-height: 2.5rem;
        }

        .form-control,
        .form-select {
            padding: 0.5rem 0.75rem;
            font-size: 16px;
        }

        .badge {
            font-size: 0.6875rem;
            padding: 0.1875rem 0.375rem;
        }
    }

    /* Extra small mobile */
    @media (max-width: 360px) {
        .card-header {
            padding: 0.375rem 0.5rem;
        }

        .card-body {
            padding: 0.5rem;
        }

        .btn {
            padding: 0.375rem 0.5rem;
            font-size: 0.8125rem;
            min-height: 2.25rem;
        }

        .form-control,
        .form-select {
            padding: 0.375rem 0.5rem;
            font-size: 16px;
        }

        .badge {
            font-size: 0.625rem;
            padding: 0.125rem 0.25rem;
        }
    }

    /* Melhorias para touch devices */
    @media (hover: none) and (pointer: coarse) {
        .btn {
            min-height: 2.75rem;
            padding: 0.5rem 1rem;
        }

        .form-control,
        .form-select {
            min-height: 2.75rem;
        }

        .table th,
        .table td {
            padding: 0.75rem 0.5rem;
        }
    }

    /* Melhorias para telas de alta densidade */
    @media (-webkit-min-device-pixel-ratio: 2),
    (min-resolution: 192dpi) {

        .btn,
        .form-control,
        .form-select {
            border-width: 0.5px;
        }
    }
</style>
