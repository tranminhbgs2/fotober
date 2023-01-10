<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = Constants::TABLE_BANKS;

    public $timestamps = true;
}
