<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    protected $table = Constants::TABLE_PAYMENT_DETAIL;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'payment_id',
        'order_name',
        'quantity',
        'price',
        'amount',
        'description'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment', 'payment_id', 'id');
    }
}
