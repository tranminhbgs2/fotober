<?php

namespace App\Repositories\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Repositories\BaseRepo;
use http\Env\Request;

class PaymentDetailRepo extends BaseRepo
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hàm thêm các mục thanh toán chi tiết
     *
     * @param $params
     * @return PaymentDetail|false
     */
    public function store($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $payment_id = isset($params['payment_id']) ? $params['payment_id'] : null;
        $description = isset($params['description']) ? $params['description'] : null;
        $order_name = isset($params['order_name']) ? $params['order_name'] : null;
        $quantity = isset($params['quantity']) ? $params['quantity'] : 0;
        $price = isset($params['price']) ? $params['price'] : 0;
        $amount = isset($params['amount']) ? $params['amount'] : $quantity*$price;
        //
        if ($order_id > 0 && $payment_id > 0 && $quantity && $price && $amount) {
            $detail = new PaymentDetail();
            $detail->order_id = $order_id;
            $detail->payment_id = $payment_id;
            $detail->description = $description;
            $detail->order_name = $order_name;
            $detail->quantity = $quantity;
            $detail->price = $price;
            $detail->amount = $amount;
            //
            $detail->save();
            // Cập nhật lại tổng số tiền vào orders và payments từ payment-detail
            $this->syncCostWhenChangeDetail(['payment_id' => $payment_id]);

            return $detail;
        }

        return false;
    }

    /**
     * Hàm xóa item
     *
     * @param Request $request
     * @return bool
     */
    public function delete($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = PaymentDetail::where('id', $id)->first();

        if ($query) {
            $query->delete();
            // Cập nhật lại tổng số tiền vào orders và payments từ payment-detail
            $this->syncCostWhenChangeDetail(['payment_id' => $query->payment_id]);
            //
            return true;
        }

        return false;
    }

    /**
     * Hàm cập nhật số tiền cần thanh toán khi thay đổi item chi tiết
     *
     * @param $params
     * @return bool
     */
    public function syncCostWhenChangeDetail($params)
    {
        $payment_id = isset($params['payment_id']) ? $params['payment_id'] : null;
        $payment = Payment::where('id', $payment_id)->first();
        if ($payment) {
            $query = PaymentDetail::selectRaw('SUM(`quantity`*`price`) AS total_quantity_price, SUM(`amount`) AS total_amount')
                ->where('payment_id', $payment_id)
                ->get();

            if ($query) {
                $order = Order::where('id', $payment->order_id)->first();
                $total = $query[0]->total_quantity_price;
                if($order->discount > 0){
                    $total_old = round(($total - $total*$order->discount/100), 2);
                } elseif($order->discount_money > 0){
                    $total_old = round(($total - $order->discount_money), 2);
                } else {
                    $total_old = $total;
                }
                Payment::where('id', $payment->id)->update(['amount' => $total_old]);
                Order::where('id', $payment->order_id)->update([
                    'cost' => $total,
                    'total_payment' => $total_old,
                ]);

                return true;
            }
        }

        return false;
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

        $query = PaymentDetail::where('order_id', $order_id);

        return $query->get();
    }


}
