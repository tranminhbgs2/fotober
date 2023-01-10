<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = Constants::TABLE_PERMISSIONS;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'name',
        'code',
        'description',
        'is_active',
        'default',
    ];

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(
            'App\Models\User',
            Constants::TABLE_USER_PERMISSION,
            'permission_id',
            'user_id'
        );
    }
}
