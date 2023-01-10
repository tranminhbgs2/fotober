<?php

namespace App\Repositories\kpi;

use App\Models\Kpi;
use Illuminate\Support\Facades\Auth;

class KpiRepo
{
    public function __construct()
    {
    }

    /**
     * Hàm lấy ds Order, có tìm kiếm và phân trang
     *
     * @param $params
     * @param false $is_counting
     *
     * @return mixed
     */
    public function getListing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;

        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : -1;
        $service_id = isset($params['service_id']) ? $params['service_id'] : -1;
        $order_id = isset($params['order_id']) ? $params['order_id'] : -1;

        $start_date = isset($params['start_date']) ? $params['start_date'] : null;
        $end_date = isset($params['end_date']) ? $params['ordeend_dater_id'] : null;

        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        //
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : 0;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : -1;
        //
        $query = Kpi::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('name_order', 'LIKE', "%" . $keyword . "%");
            });
        });

        // Lọc theo customer nào
        if ($customer_id >= 0) {
            $query->where('customer_id', $customer_id);
        }

        // Lọc theo dịch vụ nào
        if ($service_id >= 0) {
            $query->where('service_id', $service_id);
        }

        // Lọc theo sale nào
        if ($sale_id > 0) {
            $query->where('sale_id', $sale_id);
        }

        // Lọc theo ngày bắt đầu
        if ($start_date) {
            $query->where('created_at', '>=', $start_date);
        }

        // Lọc theo editor nào
        if ($end_date) {
            $query->where('created_at','<=', $end_date);
        }

        if($is_admin){
            if($sale_id > 0){
                $query->where('sale_id', $sale_id);
            }
        } else{
            $sale_id = Auth::id();
            $query->where('sale_id', $sale_id);
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
            'customer' => function($sql){
                $sql->select(['id', 'fullname', 'email', 'email_paypal']);
            },
            'service' => function($sql){
                $sql->select(['id', 'name', 'code']);
            },
            'sale' => function($sql){
                $sql->select(['id', 'fullname', 'email', 'email_paypal']);
            },
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
}
