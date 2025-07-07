@props(['target' => 'password'])

<button type="button" class="password-toggle" onclick="togglePassword('{{ $target }}')"
    title="Mostrar/Ocultar senha">
    <i class="fas fa-eye" id="eyeIcon-{{ $target }}"></i>
</button>

<script>
    function togglePassword(targetId) {
        const passwordInput = document.getElementById(targetId);
        const eyeIcon = document.getElementById('eyeIcon-' + targetId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.className = 'fas fa-eye-slash';
            eyeIcon.title = 'Ocultar senha';
        } else {
            passwordInput.type = 'password';
            eyeIcon.className = 'fas fa-eye';
            eyeIcon.title = 'Mostrar senha';
        }
    }
</script>

<style>
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray-500);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
        transition: var(--transition);
    }

    .password-toggle:hover {
        color: var(--primary-color);
        background: var(--gray-100);
    }

    .auth-form-group {
        position: relative;
    }

    .auth-form-group input[type="password"] {
        padding-right: 3rem;
    }
</style>
