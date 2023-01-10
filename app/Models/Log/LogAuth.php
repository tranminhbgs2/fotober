<?php

namespace App\Models\Log;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class LogAuth extends Model
{
    protected $table = Constants::TABLE_LOG_AUTHS;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'user_id',
        'action_type',
        'logged_in_at',
        'account_input',
        'logged_out_at',
        'user_agent',
        'duration',
        'ip_address',
        'result',
    ];
}
