<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    protected $table = Constants::TABLE_USERS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'account_type',
        'username',
        'salt',
        'password',
        'fullname',
        'birthday',
        'address',
        'avatar',
        'email',
        'email_paypal',
        'phone',
        'gender',
        'country_code',
        'website',
        'email_verified_at',
        'notes',
        'remember_token',
        'is_admin',
        'total_order',
        'status',
        'manager_by',
        'last_login',
        'last_logout',
        'activation_key',
        'activated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at',
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo('App\Models\User', 'manager_by', 'id');
    }

    /**
     * Lấy ds order của sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'assigned_sale_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(
            'App\Models\Permission',
            Constants::TABLE_USER_PERMISSION,
            'user_id',
            'permission_id'
        );
    }

    public function getAvatarAttribute($key)
    {
        if ($key) {
            if (substr($key, 0, 14) == 'uploads/avatar') {
                return asset('storage/' . $key);
            }
            return asset($key);
        }

        return asset(Constants::DEFAULT_AVATAR);
    }


}
