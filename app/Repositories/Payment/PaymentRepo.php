<?php

namespace App\Repositories\Payment;

use App\Helpers\Constants;
use App\Models\Order;
use App\Models\Payment;
use App\Repositories\BaseRepo;
use Illuminate\Support\Facades\Auth;

class PaymentRepo extends BaseRepo
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hàm lấy ds KH, có tìm kiếm và phân trang
     *
     * @param $params
     * @param false $is_counting
     *
     * @return mixed
     */
    public function getListing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $status = isset($params['status']) ? $params['status'] : -1;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        //
        $query = Payment::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                /*$sub_sql->where('name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('code', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('notes', 'LIKE', "%" . $keyword . "%");*/
            });
        });

        // Nếu là KH thì lấy theo thanh toán liên quan đến order của KH đó thôi
        if (Auth::user()->account_type == Constants::ACCOUNT_TYPE_CUSTOMER) {
            $query->where('customer_id', Auth::id());
        }

        // Nếu truyền lên status thì so sánh theo status, trái lại lấy theo quyền
        if ($status >= 0) {
            $query->where('status', $status);
        }

        if ($is_counting) {
            return $query->count();
        } else {
            $offset = ($page_index - 1) * $page_size;
            if ($page_size > 0 && $offset >= 0) {
                $query->take($page_size)->skip($offset);
            }
        }

        $query->with([
            'order' => function($sql){
                $sql->select(['id', 'name', 'code', 'delivered_at', 'cost']);
            }
        ]);

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $order) {
                $query->orderBy($order['field'], $order['direction']);
            }
        } else {
            $query->orderBy('id', 'DESC');
        }

        return $query->get();
    }

    /**
     * Hàm lấy thanh toán theo id
     * @param $id
     * @return mixed
     */
    public function findById($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;

        $query = Payment::where('id', $id)->with([
            'order' => function($sql){
                $sql->select(['id', 'name', 'code', 'cost', 'delivered_at', 'discount', 'discount_money', 'cost', 'total_payment', 'notes', 'status']);
            },
            'details' => function($sql){
                $sql->select(['id', 'order_id', 'order_name', 'payment_id', 'quantity', 'price', 'amount', 'description']);
            },
            'customer' => function($sql){
                $sql->select(['id', 'fullname', 'email', 'email_paypal']);
            },
        ]);

        return $query->first();
    }

    /**
     * Hàm lấy thông tin thanh toán qua id của order
     *
     * @param $params
     * @return mixed
     */
    public function findByOrderId($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;

        $query = Payment::where('order_id', $order_id)->with([
            'order' => function($sql){
                $sql->select(['id', 'name', 'code', 'cost', 'delivered_at', 'notes', 'status']);
            },
            'details' => function($sql){
                $sql->select(['id', 'order_id', 'payment_id', 'quantity', 'price', 'amount', 'description']);
            }
        ]);

        return $query->first();
    }

    /**
     * Hàm lưu thông tin đơn hàng
     *
     * @param $params
     * @return Order|false
     */
    public function updateOrCreate($params)
    {
        $param_fill = [];
        //
        (isset($params['order_id']) && $params['order_id']) ? $param_fill['order_id'] =  $params['order_id'] : null;
        (isset($params['customer_id']) && $params['customer_id']) ? $param_fill['customer_id'] = $params['customer_id'] : null;
        (isset($params['amount']) && $params['amount'] >= 0) ? $param_fill['amount'] = $params['amount'] : 0;
        (isset($params['method']) && $params['method']) ? $param_fill['method'] = $params['method'] : null;
        (isset($params['email_paypal']) && $params['email_paypal']) ? $param_fill['email_paypal'] = $params['email_paypal'] : null;
        (isset($params['status']) && $params['status']) ? $param_fill['status'] = $params['status'] : $param_fill['status'] = Constants::PAYMENT_STATUS_NEW;
        (isset($params['created_by']) && $params['created_by']) ? $param_fill['created_by'] = $params['created_by'] : null;
        (isset($params['link_payment']) && $params['link_payment']) ? $param_fill['link_payment'] = $params['link_payment'] : $param_fill['link_payment'] = null;
        (isset($params['paypal_id']) && $params['paypal_id']) ? $param_fill['paypal_id'] = $params['paypal_id'] : $param_fill['paypal_id'] = null;
        (isset($params['note_sale']) && $params['note_sale']) ? $param_fill['note_sale'] = $params['note_sale'] : $param_fill['note_sale'] = null;
        //
        if ($param_fill['order_id'] > 0 && $param_fill['customer_id'] > 0 && $param_fill['amount'] >= 0) {
            $payment = Payment::updateOrCreate(
                [
                    'order_id' => $param_fill['order_id'],
                    'customer_id' => $param_fill['customer_id'],
                ],
                [
                    'amount' => $param_fill['amount'],
                    'method' => $param_fill['method'],
                    'paypal_id' => $param_fill['paypal_id'],
                    'note_sale' => $param_fill['note_sale'],
                    'email_paypal' => $param_fill['email_paypal'],
                    'created_by' => $param_fill['created_by'],
                    'status' => $param_fill['status'],
                    'link_payment' => $param_fill['link_payment'],
                    'status' => $param_fill['status'],
                ]
            );

            return $payment;
        }

        return false;
    }

    public function updateStatus($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $status = isset($params['status']) ? $params['status'] : null;
        $query = Payment::where('id', $id)->first();
        // print_r($params);
        // die();
        $query->status = $status;
        $query->save();
        return $query;
    }

}
