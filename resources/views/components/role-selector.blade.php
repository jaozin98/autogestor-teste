@props([
    'roles' => [],
    'selectedRoles' => [],
    'name' => 'roles[]',
    'label' => 'Roles',
    'helpText' => 'Selecione pelo menos uma role para o usuário',
    'required' => true,
])

<div class="form-group">
    <label class="form-label">
        <i class="fas fa-user-tag"></i>
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <div class="role-options">
        @foreach ($roles as $role)
            <div class="form-check role-option">
                <input type="checkbox" id="role_{{ $role->name }}" name="{{ $name }}" value="{{ $role->name }}"
                    class="form-check-input @error('roles') is-invalid @enderror"
                    {{ in_array($role->name, old('roles', $selectedRoles)) ? 'checked' : '' }} {{ $attributes }}>
                <label class="form-check-label" for="role_{{ $role->name }}">
                    <span class="role-name">{{ ucfirst($role->name) }}</span>
                    @if ($role->name === 'admin')
                        <span class="role-description text-danger">
                            <i class="fas fa-shield-alt"></i> Acesso total ao sistema
                        </span>
                    @elseif($role->name === 'user')
                        <span class="role-description text-muted">
                            <i class="fas fa-user"></i> Acesso básico
                        </span>
                    @else
                        <span class="role-description text-muted">
                            <i class="fas fa-user-cog"></i> Acesso personalizado
                        </span>
                    @endif
                </label>
            </div>
        @endforeach
    </div>

    @error('roles')
        <div class="invalid-feedback">
            <i class="fas fa-exclamation-triangle"></i>
            {{ $message }}
        </div>
    @enderror

    @if ($helpText)
        <small class="form-text text-muted">
            <i class="fas fa-info-circle"></i>
            {{ $helpText }}
        </small>
    @endif
</div>

<style>
    .role-options {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .role-option {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        transition: all 0.2s ease-in-out;
    }

    .role-option:hover {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }

    .role-option .form-check-input:checked+.form-check-label {
        color: #1f2937;
        font-weight: 500;
    }

    .role-option .form-check-input:checked~.role-option {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .role-name {
        display: block;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .role-description {
        display: block;
        font-size: 0.875rem;
        font-weight: normal;
    }

    .role-description i {
        margin-right: 0.25rem;
    }

    @media (max-width: 768px) {
        .role-options {
            gap: 0.5rem;
        }

        .role-option {
            padding: 0.5rem;
        }
    }
</style>
