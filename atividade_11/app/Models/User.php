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
        'debit',
        'role',
    ];

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
public function books()
{
    return $this->belongsToMany(Book::class, 'borrowings')
                ->withPivot('id', 'borrowed_at', 'returned_at')
                ->withTimestamps();
}

/**
 * Verifica se o usuário é bibliotecário
 */
public function isBibliotecario()
{
    return $this->role === 'bibliotecario';
}

/**
 * Verifica se o usuário é administrador
 */
public function isAdmin()
{
    return $this->role === 'admin';
}

/**
 * Verifica se o usuário é bibliotecário ou administrador
 */
public function isStaff()
{
    return $this->isBibliotecario() || $this->isAdmin();
}

/**
 * Verifica se o usuário é cliente
 */
public function isCliente()
{
    return $this->role === 'cliente';
}
}
