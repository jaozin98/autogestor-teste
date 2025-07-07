<section class="space-y-6">
    <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
        <i class="fas fa-trash"></i>
        {{ __('Excluir Conta') }}
    </button>

    <!-- Modal de Confirmação -->
    <div id="deleteModal" class="modal-overlay" style="display: none;">
        <div class="modal-backdrop" onclick="closeDeleteModal()"></div>
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <div class="text-center mb-6">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                        {{ __('Confirmar Exclusão da Conta') }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        {{ __('Esta ação não pode ser desfeita. Todos os seus dados serão permanentemente excluídos.') }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        {{ __('Digite sua senha para confirmar') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                        placeholder="{{ __('Sua senha atual') }}" required>
                    @error('password', 'userDeletion')
                        <div class="invalid-feedback">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        {{ __('Excluir Conta') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Fechar modal com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
