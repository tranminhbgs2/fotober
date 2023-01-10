<?php

namespace App\Models\Log;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class LogFollow extends Model
{
    protected $table = Constants::TABLE_LOG_FOLLOWS;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'before_status',
        'after_status',
        'receiver_id',
        'summary',
        'content',
    ];
}
