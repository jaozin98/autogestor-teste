@if (session('status'))
    <div class="auth-alert auth-alert-success">
        <i class="fas fa-check-circle"></i>
        <div>
            <strong>Sucesso!</strong>
            <p>{{ session('status') }}</p>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="auth-alert auth-alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Erro!</strong>
            <p>{{ session('error') }}</p>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="auth-alert auth-alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Erro de validação:</strong>
            <ul class="auth-error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<style>
    .auth-error-list {
        margin: 0.5rem 0 0 1.5rem;
        padding: 0;
        list-style: none;
    }

    .auth-error-list li {
        position: relative;
        padding-left: 1rem;
        margin-bottom: 0.25rem;
    }

    .auth-error-list li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: currentColor;
        font-weight: bold;
    }
</style>
