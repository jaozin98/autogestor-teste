@props(['user'])

<div class="password-form">
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 gap-6">
            <!-- Senha Atual -->
            <div class="form-group">
                <label for="update_password_current_password" class="form-label">
                    <i class="fas fa-lock"></i>
                    Senha Atual
                    <span class="text-danger">*</span>
                </label>
                <input type="password" id="update_password_current_password" name="current_password"
                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                    autocomplete="current-password" placeholder="Digite sua senha atual">
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Nova Senha -->
            <div class="form-group">
                <label for="update_password_password" class="form-label">
                    <i class="fas fa-key"></i>
                    Nova Senha
                    <span class="text-danger">*</span>
                </label>
                <input type="password" id="update_password_password" name="password"
                    class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                    autocomplete="new-password" placeholder="Digite sua nova senha">
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
                <x-password-strength />
                <small class="form-text text-muted">
                    <i class="fas fa-info-circle"></i>
                    A senha deve ter pelo menos 8 caracteres.
                </small>
            </div>

            <!-- Confirmar Nova Senha -->
            <div class="form-group">
                <label for="update_password_password_confirmation" class="form-label">
                    <i class="fas fa-check-circle"></i>
                    Confirmar Nova Senha
                    <span class="text-danger">*</span>
                </label>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation"
                    class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                    autocomplete="new-password" placeholder="Confirme sua nova senha">
                @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Atualizar Senha
            </button>
        </div>

        <!-- Mensagem de Sucesso -->
        @if (session('status') === 'password-updated')
            <div class="alert alert-success" x-data="{ show: true }" x-show="show" x-transition
                x-init="setTimeout(() => show = false, 3000)">
                <i class="fas fa-check-circle"></i>
                Senha atualizada com sucesso!
            </div>
        @endif
    </form>
</div>
