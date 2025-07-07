@props(['brand' => null, 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST" class="needs-validation" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-tag"></i>
                        Nome da Marca <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Ex: Apple, Samsung, Nike"
                        value="{{ old('name', $brand?->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Nome único da marca (máximo 255 caracteres)
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="founded_year" class="form-label">
                        <i class="fas fa-calendar"></i>
                        Ano de Fundação
                    </label>
                    <input type="number" id="founded_year" name="founded_year"
                        class="form-control @error('founded_year') is-invalid @enderror" placeholder="Ex: 1990"
                        min="1800" max="{{ date('Y') + 1 }}"
                        value="{{ old('founded_year', $brand?->founded_year) }}">
                    @error('founded_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="country_of_origin" class="form-label">
                        <i class="fas fa-flag"></i>
                        País de Origem
                    </label>
                    <input type="text" id="country_of_origin" name="country_of_origin"
                        class="form-control @error('country_of_origin') is-invalid @enderror"
                        placeholder="Ex: Brasil, Estados Unidos, Alemanha"
                        value="{{ old('country_of_origin', $brand?->country_of_origin) }}">
                    @error('country_of_origin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="website" class="form-label">
                        <i class="fas fa-globe"></i>
                        Website
                    </label>
                    <input type="url" id="website" name="website"
                        class="form-control @error('website') is-invalid @enderror"
                        placeholder="https://www.exemplo.com" value="{{ old('website', $brand?->website) }}">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">
                <i class="fas fa-align-left"></i>
                Descrição
            </label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                rows="4" placeholder="Descreva a marca, sua história, valores, etc..." maxlength="1000">{{ old('description', $brand?->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">
                Descrição opcional da marca (máximo 1000 caracteres)
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="d-flex justify-content-between">
            <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                {{ $brand ? 'Atualizar' : 'Criar' }} Marca
            </button>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        // Validação do lado do cliente
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Contador de caracteres para descrição
        document.getElementById('description').addEventListener('input', function() {
            const maxLength = 1000;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;

            let counter = this.parentNode.querySelector('.char-counter');
            if (!counter) {
                counter = document.createElement('small');
                counter.className = 'form-text char-counter';
                this.parentNode.appendChild(counter);
            }

            counter.textContent = `${currentLength}/${maxLength} caracteres`;
            counter.className = `form-text char-counter ${remaining < 50 ? 'text-warning' : 'text-muted'}`;
        });
    </script>
@endpush
