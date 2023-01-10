<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = Constants::TABLE_GROUPS;
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
        'status',
        'created_by',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', Constants::TABLE_GROUP_ROLE, 'group_id', 'role_id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User', 'group_id', 'id');
    }
}
