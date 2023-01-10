<?php

namespace App\Repositories\Order;

use App\Models\Input;
use App\Models\Output;
use App\Repositories\BaseRepo;

class InputRepo extends BaseRepo
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
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        //
        $query = Input::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('link', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('file', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('name', 'LIKE', "%" . $keyword . "%");
            });
        });

        // Chỉ lấy file - bỏ đi để hiển thị cả file + link - 09/02/2022
        //$query->whereNotNull('file');

        // Lọc theo order
        if ($order_id > 0) {
            $query->where('order_id', $order_id);
        }

        if ($is_counting) {
            return $query->count();
        } else {
            // $offset = ($page_index - 1) * $page_size;
            // if ($page_size > 0 && $offset >= 0) {
            //     $query->take($page_size)->skip($offset);
            // }
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

        $query = Input::where('id', $id)->with([
            'order' => function($sql){
                $sql->select(['id', 'name', 'code', 'cost', 'delivered_at', 'notes', 'status']);
            }
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

        $query = Input::where('order_id', $order_id)->with([
            'order' => function($sql){
                $sql->select(['id', 'name', 'code', 'cost', 'delivered_at', 'notes', 'status']);
            }
        ]);

        return $query->first();
    }

    /**
     * Hàm thêm mới yêu cầu
     *
     * @param $params
     * @return Output|false
     */
    public function store($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : 0;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : 0;
        $link = isset($params['link']) ? $params['link'] : null;
        $file = isset($params['file']) ? $params['file'] : null;
        $type = isset($params['type']) ? $params['type'] : null;
        $name = isset($params['name']) ? $params['name'] : null;
        //
        if ($order_id > 0 && $customer_id > 0) {
            $input = new Input();
            $input->order_id = $order_id;
            $input->customer_id = $customer_id;
            $input->link = $link;
            $input->file = $file;
            $input->name = $name;
            $input->type = $type;
            //
            $input->save();
            return $input;
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
        $query = Input::where('id', $id)->first();

        if ($query) {
            $query->delete();
            return true;
        }

        return false;
    }
}
