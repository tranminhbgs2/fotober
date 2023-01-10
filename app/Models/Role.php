<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = Constants::TABLE_ROLES;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'default',
        'is_active',
        'created_by',
    ];

    public function groups()
    {
        return $this->belongsToMany('App\Models\Group', Constants::TABLE_GROUP_ROLE, 'role_id', 'group_id');
    }

    public function permissions()
    {
        return $this->hasMany('App\Models\Permission', 'role_id', 'id');
    }
}
