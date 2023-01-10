<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = Constants::TABLE_MESSAGES;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'customer_id',
        'sale_id',
        'type',
        'content',
        'file_name',
        'seen',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo('App\Models\User', 'sale_id', 'id');
    }
}
