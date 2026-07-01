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
        return in_array(strtolower($this->role), ['super admin', 'super_admin']);
    }

    public function isAdminRoren()
    {
        return in_array(strtolower($this->role), ['admin', 'admin roren', 'admin_roren']);
    }

    public function isVerifikator()
    {
        return in_array(strtolower($this->role), ['verifikator']);
    }

    public function isUserDaerah()
    {
        return in_array(strtolower($this->role), ['user daerah', 'user_daerah']);
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
