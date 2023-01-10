<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //use SoftDeletes;

    protected $table = Constants::TABLE_ORDERS;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'name',
        'code',
        'service_id',
        'options',
        'link',
        'email_receiver',
        'upload_file',
        'turn_arround_time',
        'deadline',
        'notes',
        'quantity',
        'discount',
        'discount_money',
        'cost',
        'total_payment',
        'review',
        'rating',
        'reviewed_at',
        'status',
        'created_type',
        'created_by',
        'assigned_sale_id',
        'assigned_admin_id',
        'assigned_editor_id',
        'assigned_qaqc_id',
        'sent_sale_at',
        'sent_admin_at',
        'sent_editor_at',
        'sent_qaqc_at',
        'delivered_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo('App\Models\User', 'created_type', 'id');
    }

    public function requirements()
    {
        return $this->hasMany('App\Models\Requirement', 'order_id', 'id');
    }

    public function requirementDone()
    {
        return $this->hasMany('App\Models\Requirement', 'order_id', 'id');
    }

    /**
     * Lấy thông tin thanh toán
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'order_id', 'id');
    }

    /**
     * Lấy thông tin chi tiết thanh toán
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment_detail()
    {
        return $this->hasOne('App\Models\PaymentDetail', 'order_id', 'id');
    }
    /**
     * Gán cho sale nào
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedSale()
    {
        return $this->belongsTo('App\Models\User', 'assigned_sale_id', 'id');
    }

    /**
     * Gán cho admin nào
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedAdmin()
    {
        return $this->belongsTo('App\Models\User', 'assigned_admin_id', 'id');
    }

    /**
     * Gán cho editor nào
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedEditor()
    {
        return $this->belongsTo('App\Models\User', 'assigned_editor_id', 'id');
    }

    /**
     * Gán cho qaqc nào
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedQaqc()
    {
        return $this->belongsTo('App\Models\User', 'assigned_qaqc_id', 'id');
    }

    /**
     * Lấy thông tin thanh toán
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function output()
    {
        return $this->hasOne('App\Models\Output', 'order_id', 'id');
    }
    
    /**
     * Lấy thông tin thanh toán
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function total_no_seen()
    {
        return $this->hasMany('App\Models\Message', 'order_id', 'id');
    }
}
