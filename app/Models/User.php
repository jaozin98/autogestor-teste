<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modelo de Usuário com sistema de permissões
 * @method bool can(string $permission)
 * @method bool hasRole(string $role)
 * @method bool hasPermissionTo(string $permission)
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * Os atributos que devem ser ocultos para serialização.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Obter os atributos que devem ser convertidos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's active status
     */
    public function getIsActiveAttribute(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Set the user's active status
     */
    public function setIsActiveAttribute(bool $value): void
    {
        $this->email_verified_at = $value ? now() : null;
    }
}
