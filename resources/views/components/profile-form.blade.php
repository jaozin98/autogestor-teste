@props(['user'])

<div class="profile-form">
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Campo Nome -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-user"></i>
                    Nome
                    <span class="text-danger">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}"
                    required autofocus autocomplete="name" placeholder="Digite seu nome completo">
                @error('name')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Campo Email (Somente Leitura) -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i>
                    E-mail
                    <span class="text-danger">*</span>
                </label>
                <input type="email" id="email" name="email" class="form-control bg-light"
                    value="{{ $user->email }}" readonly title="O e-mail não pode ser alterado">
                <small class="form-text text-muted">
                    <i class="fas fa-info-circle"></i>
                    O e-mail não pode ser alterado por questões de segurança.
                </small>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Salvar Alterações
            </button>
        </div>

        <!-- Mensagem de Sucesso -->
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success" x-data="{ show: true }" x-show="show" x-transition
                x-init="setTimeout(() => show = false, 3000)">
                <i class="fas fa-check-circle"></i>
                Perfil atualizado com sucesso!
            </div>
        @endif
    </form>
</div>
