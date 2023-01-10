<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = Constants::TABLE_NOTIFICATIONS;
    public $timestamps = true;

    protected $fillable = [
        'scope',
        'order_id',
        'title_vi',
        'title_en',
        'content',
        'sender_id',
        'receiver_id',
        'is_read',
        'read_at',
        'order_data',
    ];
}
