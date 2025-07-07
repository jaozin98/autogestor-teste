<style>
    /* Estilos específicos para formulários de usuário */
    .user-form {
        max-width: 100%;
    }

    .user-form .form-group {
        margin-bottom: 1.5rem;
    }

    .user-form .row {
        margin-left: -0.75rem;
        margin-right: -0.75rem;
    }

    .user-form .col-md-6 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    /* Responsividade para telas menores */
    @media (max-width: 768px) {
        .user-form .row {
            margin-left: 0;
            margin-right: 0;
        }

        .user-form .col-md-6 {
            padding-left: 0;
            padding-right: 0;
            margin-bottom: 1rem;
        }

        .user-form .col-md-6:last-child {
            margin-bottom: 0;
        }
    }

    /* Melhorias no componente de roles */
    .role-options {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        background-color: #f9fafb;
    }

    .role-option {
        margin-bottom: 0.5rem;
        padding: 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        background-color: white;
        transition: all 0.2s ease-in-out;
    }

    .role-option:hover {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }

    .role-option:last-child {
        margin-bottom: 0;
    }

    .role-option .form-check-input:checked+.form-check-label {
        color: #1f2937;
        font-weight: 500;
    }

    .role-option .form-check-input:checked~.role-option {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .role-name {
        display: block;
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: #374151;
    }

    .role-description {
        display: block;
        font-size: 0.875rem;
        font-weight: normal;
        line-height: 1.4;
    }

    .role-description i {
        margin-right: 0.25rem;
    }

    /* Melhorias no componente de informações do sistema */
    .system-info-card {
        margin-top: 2rem;
    }

    .system-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .info-item {
        padding: 0.75rem;
        background-color: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
        display: block;
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

    /* Responsividade para informações do sistema */
    @media (max-width: 768px) {
        .system-info-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .info-item {
            padding: 0.5rem;
        }
    }

    /* Melhorias nos formulários responsivos */
    @media (max-width: 576px) {
        .user-form .form-group {
            margin-bottom: 1rem;
        }

        .role-options {
            padding: 0.75rem;
        }

        .role-option {
            padding: 0.5rem;
        }
    }
</style>
