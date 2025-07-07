<div class="app-logo">
    <i class="fas fa-cogs"></i>
    <span>AutoGestor</span>
</div>

<style>
    .app-logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .app-logo i {
        font-size: 1.8rem;
        color: var(--warning-color);
        animation: spin 4s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .app-logo span {
        background: linear-gradient(45deg, var(--white), var(--gray-200));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
