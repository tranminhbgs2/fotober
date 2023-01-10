<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $table = Constants::TABLE_NOTIFICATION_LOGS;
    public $timestamps = true;

    protected $fillable = [
        'notification_id',
        'receiver_id',
        'is_read',
        'read_at',
    ];
}
