<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role',
        'nip',
        'nama',
        'no_hp',
        'email',
        'jabatan',
        'foto_profil',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Helper role
     */
    public function isSuperAdmin()
    {
        return $this->role === 1;
    }

    public function isAdmin()
    {
        return $this->role === 2;
    }

    public function isPengaju()
    {
        return $this->role === 3;
    }

    /**
     * Label role (opsional)
     */
    public function getRoleNameAttribute()
    {
        return match ($this->role) {
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'Pengaju',
            default => 'Unknown',
        };
    }
}
