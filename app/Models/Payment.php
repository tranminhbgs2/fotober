<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = Constants::TABLE_PAYMENTS;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'customer_id',
        'amount',
        'date_request',
        'date_success',
        'method',
        'email_paypal',
        'paypal_id',
        'link_payment',
        'status',
        'note_sale',
        'created_by',
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
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function details()
    {
        return $this->hasMany('App\Models\PaymentDetail', 'payment_id', 'id');
    }
}
