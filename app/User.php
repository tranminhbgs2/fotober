<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Hàm check quyền
     *
     * @param $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if (is_array($roles) && count($roles) > 0) {
            if (in_array(Auth::user()->account_type, $roles)) {
                return true;
            }
        }
        return false;
    }

    public function getAvatarAttribute($key)
    {
        if ($key) {
            if (substr($key, 0, 14) == 'uploads/avatar') {
                return asset('storage/' . $key);
            }
            return asset($key);
        }

        return asset('storage/uploads/avatar/user-avatar-male.jpg');
    }
}
