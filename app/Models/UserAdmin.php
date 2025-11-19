<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class UserAdmin extends Authenticatable
{
    use Notifiable;

    protected $table = 'useradmin';
    protected $fillable = [
        'full_name',
        'email',
        'user_name',
        'mobile',
        'role',
        'password',
        'status'
    ];
    protected $hidden = ['password', 'remember_token'];

    // Optionally: check if user is active
    public function isActive()
    {
        return $this->status == 1;
    }


    public function roles()
{
    return $this->belongsToMany(Role::class, 'user_role');
}

public function hasRole($role)
{
    return $this->roles()->where('name', $role)->exists();
}

public function hasPermission($permission)
{
    return $this->roles()
        ->whereHas('permissions', function($q) use ($permission) {
            $q->where('name', $permission);
        })->exists();
}

public function rolePermission()
{
    return $this->hasOne(RolePermission::class, 'role', 'role_id');
}


}
