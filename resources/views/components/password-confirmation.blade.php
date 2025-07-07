@props(['password' => 'password', 'confirmation' => 'password_confirmation'])

<div class="password-confirmation" id="passwordConfirmation" style="display: none;">
    <div class="confirmation-label">
        <i class="fas fa-check-circle" id="confirmationIcon"></i>
        <span id="confirmationText">Confirme sua senha</span>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('{{ $password }}');
        const confirmationInput = document.getElementById('{{ $confirmation }}');
        const confirmationDiv = document.getElementById('passwordConfirmation');
        const confirmationIcon = document.getElementById('confirmationIcon');
        const confirmationText = document.getElementById('confirmationText');

        if (passwordInput && confirmationInput) {
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmation = confirmationInput.value;

                if (confirmation.length > 0) {
                    confirmationDiv.style.display = 'block';

                    if (password === confirmation) {
                        confirmationIcon.className = 'fas fa-check-circle';
                        confirmationText.textContent = 'Senhas coincidem';
                        confirmationDiv.className = 'password-confirmation match';
                    } else {
                        confirmationIcon.className = 'fas fa-times-circle';
                        confirmationText.textContent = 'Senhas não coincidem';
                        confirmationDiv.className = 'password-confirmation no-match';
                    }
                } else {
                    confirmationDiv.style.display = 'none';
                }
            }

            passwordInput.addEventListener('input', checkPasswordMatch);
            confirmationInput.addEventListener('input', checkPasswordMatch);
        }
    });
</script>

<style>
    .password-confirmation {
        margin-top: 0.5rem;
        padding: 0.75rem;
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-300);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .password-confirmation.match {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .password-confirmation.no-match {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .confirmation-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .confirmation-label i {
        font-size: 1rem;
    }
</style>
