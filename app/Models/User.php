<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'kabupaten',
    ];

    public function isSuperAdmin()
    {
        return $this->role === 'Super Admin' || $this->role === 'super_admin';
    }

    public function isAdminRoren()
    {
        return $this->role === 'Admin Roren' || $this->role === 'admin_roren';
    }

    public function isVerifikator()
    {
        return $this->role === 'Verifikator' || $this->role === 'verifikator';
    }

    public function isUserDaerah()
    {
        return $this->role === 'User Daerah' || $this->role === 'user_daerah';
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
