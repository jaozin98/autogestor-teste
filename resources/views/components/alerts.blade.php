@if (session('success') || session('sucesso'))
    <div class="alert alert-success" role="alert">
        <div class="alert-content">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') ?? session('sucesso') }}</span>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error" role="alert">
        <div class="alert-content">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-error" role="alert">
        <div class="alert-content">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Erro de validação:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif
