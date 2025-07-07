@props(['password' => ''])

<div class="password-strength" id="passwordStrength" style="display: none;">
    <div class="strength-label">
        <i class="fas fa-shield-alt"></i>
        Força da senha:
    </div>
    <div class="strength-bar">
        <div class="strength-fill" id="strengthFill"></div>
    </div>
    <div class="strength-text" id="strengthText">Digite uma senha</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.querySelector('input[name="password"]');
        const strengthDiv = document.getElementById('passwordStrength');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;

                if (password.length > 0) {
                    strengthDiv.style.display = 'block';
                    const strength = calculatePasswordStrength(password);
                    updateStrengthIndicator(strength);
                } else {
                    strengthDiv.style.display = 'none';
                }
            });
        }

        function calculatePasswordStrength(password) {
            let score = 0;

            // Comprimento
            if (password.length >= 8) score += 1;
            if (password.length >= 12) score += 1;

            // Caracteres minúsculos
            if (/[a-z]/.test(password)) score += 1;

            // Caracteres maiúsculos
            if (/[A-Z]/.test(password)) score += 1;

            // Números
            if (/[0-9]/.test(password)) score += 1;

            // Caracteres especiais
            if (/[^A-Za-z0-9]/.test(password)) score += 1;

            return Math.min(score, 5);
        }

        function updateStrengthIndicator(strength) {
            const percentages = [0, 20, 40, 60, 80, 100];
            const colors = ['#dc3545', '#ffc107', '#fd7e14', '#17a2b8', '#28a745', '#28a745'];
            const texts = [
                'Muito fraca',
                'Fraca',
                'Média',
                'Boa',
                'Forte',
                'Muito forte'
            ];

            strengthFill.style.width = percentages[strength] + '%';
            strengthFill.style.backgroundColor = colors[strength];
            strengthText.textContent = texts[strength];
            strengthText.className = 'strength-text strength-' + strength;
        }
    });
</script>

<style>
    .password-strength {
        margin-top: 0.5rem;
        padding: 0.75rem;
        background: var(--gray-100);
        border-radius: var(--border-radius);
        border: 1px solid var(--gray-300);
    }

    .strength-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }

    .strength-label i {
        color: var(--primary-color);
    }

    .strength-bar {
        width: 100%;
        height: 6px;
        background: var(--gray-300);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 3px;
    }

    .strength-text {
        font-size: 0.8rem;
        font-weight: 500;
        text-align: center;
    }

    .strength-0 {
        color: #dc3545;
    }

    .strength-1 {
        color: #ffc107;
    }

    .strength-2 {
        color: #fd7e14;
    }

    .strength-3 {
        color: #17a2b8;
    }

    .strength-4 {
        color: #28a745;
    }

    .strength-5 {
        color: #28a745;
    }
</style>
