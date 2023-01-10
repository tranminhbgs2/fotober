<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = Constants::TABLE_USERS;
    public $timestamps = true;

    protected $appends = ['status_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    protected $hidden = [
        'password', 'password_reset_tocken'
    ];

}
