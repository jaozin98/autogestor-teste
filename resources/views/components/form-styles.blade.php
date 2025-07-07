@push('styles')
    <style>
        /* Layout responsivo */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header-content h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }

        .page-header-content p {
            margin: 0.5rem 0 0 0;
            color: var(--text-muted);
        }

        .page-header-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Cards */
        .card {
            border: 1px solid var(--border-color, #e5e7eb);
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            background: var(--white);
        }

        .card-header {
            background-color: var(--card-header-bg, #f9fafb);
            border-bottom: 1px solid var(--border-color, #e5e7eb);
            padding: 1rem 1.5rem;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .card-title {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-color, #374151);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Formulários */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--text-color, #374151);
        }

        .form-label i {
            margin-right: 0.5rem;
            color: var(--primary-color, #3b82f6);
        }

        .form-control {
            width: 100%;
            border: 1px solid var(--border-color, #d1d5db);
            border-radius: 0.375rem;
            padding: 0.75rem;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            background-color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color, #3b82f6);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color, #dc2626);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-control.bg-light {
            background-color: var(--gray-100);
            color: var(--gray-600);
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: var(--danger-color, #dc2626);
        }

        .form-text {
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: var(--text-muted, #6b7280);
        }

        /* Checkbox */
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 1rem;
            height: 1rem;
            border: 1px solid var(--border-color, #d1d5db);
            border-radius: 0.25rem;
            margin: 0;
        }

        .form-check-input:checked {
            background-color: var(--primary-color, #3b82f6);
            border-color: var(--primary-color, #3b82f6);
        }

        .form-check-label {
            margin: 0;
            font-weight: 500;
            cursor: pointer;
        }

        /* Botões */
        .form-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color, #e5e7eb);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: var(--primary-color, #3b82f6);
            color: white;
            border-color: var(--primary-color, #3b82f6);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover, #2563eb);
            border-color: var(--primary-hover, #2563eb);
        }

        .btn-secondary {
            background-color: var(--secondary-color, #6b7280);
            color: white;
            border-color: var(--secondary-color, #6b7280);
        }

        .btn-secondary:hover {
            background-color: var(--secondary-hover, #4b5563);
            border-color: var(--secondary-hover, #4b5563);
        }

        .btn-danger {
            background-color: var(--danger-color, #dc2626);
            color: white;
            border-color: var(--danger-color, #dc2626);
        }

        .btn-danger:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }

        .btn-info {
            background-color: var(--info-color, #06b6d4);
            color: white;
            border-color: var(--info-color, #06b6d4);
        }

        .btn-info:hover {
            background-color: var(--info-hover, #0891b2);
            border-color: var(--info-hover, #0891b2);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        /* Modal Simples */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            z-index: 1001;
        }

        /* Modal Alpine.js */
        .modal-overlay[data-alpine] {
            background-color: transparent;
        }

        .modal-overlay[data-alpine] .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-enter {
            transition: all 0.3s ease-out;
        }

        .modal-enter-start {
            opacity: 0;
            transform: scale(0.95);
        }

        .modal-enter-end {
            opacity: 1;
            transform: scale(1);
        }

        .modal-leave {
            transition: all 0.2s ease-in;
        }

        .modal-leave-start {
            opacity: 1;
            transform: scale(1);
        }

        .modal-leave-end {
            opacity: 0;
            transform: scale(0.95);
        }

        /* Sidebar */
        .sidebar {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            height: fit-content;
        }

        .sidebar-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .card-body {
                padding: 1rem;
            }

            .card-header {
                padding: 1rem;
            }

            .modal-content {
                width: 95%;
                margin: 1rem;
            }
        }

        /* Estilos específicos para a página de perfil */
        .profile-form,
        .password-form {
            width: 100%;
        }

        .profile-form .form-group,
        .password-form .form-group {
            position: relative;
        }

        .profile-form .form-control,
        .password-form .form-control {
            padding-right: 1rem;
        }

        /* Melhorias para o modal de exclusão */
        .modal-content .form-actions {
            margin-top: 1.5rem;
            padding-top: 1rem;
        }

        /* Melhorias para os badges de status */
        .inline-flex {
            display: inline-flex;
        }

        .items-center {
            align-items: center;
        }

        .px-2\.5 {
            padding-left: 0.625rem;
            padding-right: 0.625rem;
        }

        .py-0\.5 {
            padding-top: 0.125rem;
            padding-bottom: 0.125rem;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .font-medium {
            font-weight: 500;
        }

        /* Melhorias para o grid responsivo */
        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .gap-6 {
            gap: 1.5rem;
        }

        .gap-8 {
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        /* Melhorias para espaçamento */
        .space-y-6>*+* {
            margin-top: 1.5rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-1 {
            margin-bottom: 0.25rem;
        }

        .mt-8 {
            margin-top: 2rem;
        }

        .mt-6 {
            margin-top: 1.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        /* Melhorias para flexbox */
        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-center {
            justify-content: center;
        }

        .flex-1 {
            flex: 1 1 0%;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        /* Melhorias para texto */
        .text-center {
            text-align: center;
        }

        .text-2xl {
            font-size: 1.5rem;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-medium {
            font-weight: 500;
        }

        /* Melhorias para cores */
        .text-gray-900 {
            color: #111827;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .text-red-600 {
            color: #dc2626;
        }

        .text-red-800 {
            color: #991b1b;
        }

        .text-red-400 {
            color: #f87171;
        }

        .bg-blue-100 {
            background-color: #dbeafe;
        }

        .bg-green-100 {
            background-color: #dcfce7;
        }

        .bg-red-100 {
            background-color: #fee2e2;
        }

        .bg-red-50 {
            background-color: #fef2f2;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        /* Melhorias para bordas */
        .border-red-200 {
            border-color: #fecaca;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        /* Melhorias para tamanhos */
        .w-16 {
            width: 4rem;
        }

        .h-16 {
            height: 4rem;
        }

        .w-3 {
            width: 0.75rem;
        }

        .h-3 {
            height: 0.75rem;
        }

        .w-5 {
            width: 1.25rem;
        }

        .h-5 {
            height: 1.25rem;
        }

        /* Melhorias para gap */
        .gap-4 {
            gap: 1rem;
        }

        .gap-3 {
            gap: 0.75rem;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        /* Melhorias para margens */
        .ml-3 {
            margin-left: 0.75rem;
        }

        .mr-1 {
            margin-right: 0.25rem;
        }

        /* Melhorias para padding */
        .p-6 {
            padding: 1.5rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .py-8 {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        /* Melhorias para container */
        .container {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        @media (min-width: 640px) {
            .container {
                max-width: 640px;
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 768px;
            }
        }

        @media (min-width: 1024px) {
            .container {
                max-width: 1024px;
            }
        }

        @media (min-width: 1280px) {
            .container {
                max-width: 1280px;
            }
        }
    </style>
@endpush
