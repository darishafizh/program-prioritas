<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'kabupaten',
    ];

    public function isSuperAdmin()
    {
        return $this->hasRole(['super_admin', 'super admin', 'Super Admin']);
    }

    public function isAdminRoren()
    {
        return $this->hasRole(['admin_roren', 'admin roren', 'Admin Roren', 'admin', 'Admin']);
    }

    public function isVerifikator()
    {
        return $this->hasRole(['verifikator', 'Verifikator']);
    }

    public function isUserDaerah()
    {
        return $this->hasRole(['user_daerah', 'user daerah', 'User Daerah']);
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

    protected $appends = ['role'];

    public function getRoleAttribute()
    {
        return $this->roles->first()->name ?? 'Tanpa Role';
    }

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
