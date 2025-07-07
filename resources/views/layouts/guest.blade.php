<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AutoGestor - @yield('title', 'Autenticação')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @stack('styles')
</head>

<body class="auth-body">
    <div class="auth-container">
        <!-- Header -->
        <header class="auth-header">
            <div class="auth-brand">
                <x-application-logo />
            </div>
            <div class="auth-nav">
                @yield('auth-nav')
            </div>
        </header>

        <!-- Main Content -->
        <main class="auth-main">
            <div class="auth-card">
                <div class="auth-card-header">
                    <h1 class="auth-title">
                        @yield('auth-icon')
                        @yield('auth-title')
                    </h1>
                    <p class="auth-subtitle">
                        @yield('auth-subtitle')
                    </p>
                </div>

                <div class="auth-card-body">
                    <x-auth-alerts />
                    @yield('content')
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="auth-footer">
            <p>&copy; {{ date('Y') }} AutoGestor. Todos os direitos reservados.</p>
        </footer>
    </div>

    <script>
        // Garantir que o scroll funcione corretamente
        document.addEventListener('DOMContentLoaded', function() {
            const authBody = document.querySelector('.auth-body');
            const authContainer = document.querySelector('.auth-container');

            // Forçar scroll se necessário
            function ensureScroll() {
                const bodyHeight = authBody.scrollHeight;
                const viewportHeight = window.innerHeight;

                if (bodyHeight > viewportHeight) {
                    authBody.style.overflowY = 'auto';
                    authBody.style.alignItems = 'flex-start';
                }
            }

            // Verificar na carga e no redimensionamento
            ensureScroll();
            window.addEventListener('resize', ensureScroll);

            // Garantir que o container tenha altura mínima
            authContainer.style.minHeight = '100vh';
        });
    </script>

    @stack('scripts')
</body>

</html>
