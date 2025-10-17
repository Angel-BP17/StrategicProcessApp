<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'array',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function hasAnyRole(array $roles): bool
    {
        $own = collect($this->role ?? [])->map(fn($r) => strtolower((string) $r));
        return $own->intersect(collect($roles)->map(fn($r) => strtolower($r)))->isNotEmpty();
    }
}
