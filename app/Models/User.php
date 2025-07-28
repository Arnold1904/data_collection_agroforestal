<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PROFESOR = 'profesor';
    public const ROLE_ESTUDIANTE = 'estudiante';
    public const ROLE_VISITANTE = 'visitante';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isProfesor(): bool
    {
        return $this->role === self::ROLE_PROFESOR;
    }

    public function isEstudiante(): bool
    {
        return $this->role === self::ROLE_ESTUDIANTE;
    }

    public function isVisitante(): bool
    {
        return $this->role === self::ROLE_VISITANTE;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
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
}
