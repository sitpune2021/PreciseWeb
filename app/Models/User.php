<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
     use SoftDeletes;

    /**

     *
     * @var list<string>
     */
    protected $fillable = [
        'admin_id',
        'name',
        'username',
        'email',
        'mobile',
        'user_type',
        'password',
        'org_pass',
        'profile_photo',
        'status',
        'last_login_at',
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

    public function client()
    {
        return $this->hasMany(Client::class, 'login_id', 'id'); // assume login_id matches user id
    }

    public function rolePermission()
    {
        return $this->hasOne(RolePermission::class, 'role_id', 'user_type');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'admin_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
