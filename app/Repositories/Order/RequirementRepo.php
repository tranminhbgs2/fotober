<?php

namespace App\Repositories\Order;

use App\Models\Requirement;
use App\Repositories\BaseRepo;
use Illuminate\Support\Facades\Auth;

class RequirementRepo extends BaseRepo
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
        $status = isset($params['status']) ? $params['status'] : -1;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        //
        $query = Requirement::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('description', 'LIKE', "%" . $keyword . "%");
            });
        });

        // Lọc theo order
        if ($order_id > 0) {
            $query->where('order_id', $order_id);
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

        $query = Requirement::where('id', $id)->with([
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

        $query = Requirement::where('order_id', $order_id)->with([
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
     * @return Requirement|false
     */
    public function store($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        $name = isset($params['name']) ? $params['name'] : null;
        $description = isset($params['description']) ? $params['description'] : null;
        $status = isset($params['status']) ? $params['status'] : 0;
        $created_by = isset($params['created_by']) ? $params['created_by'] : Auth::id();
        //
        if ($order_id > 0 && $customer_id > 0 && $name && $status >= 0) {
            $requirement = new Requirement();
            $requirement->order_id = $order_id;
            $requirement->customer_id = $customer_id;
            $requirement->name = $name;
            $requirement->description = $description;
            $requirement->status = $status;
            $requirement->created_by = $created_by;
            //
            $requirement->save();
            return $requirement;
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
        $query = Requirement::where('id', $id)->first();

        if ($query) {
            $query->delete();
            return true;
        }

        return false;
    }

    /**
     * Hàm cập nhật thông tin yêu cầu
     *
     * @param $params
     * @return false
     */
    public function update($params)
    {
        $param_update = [];
        //
        $id = (isset($params['id']) && $params['id'] > 0) ? $params['id'] : null;
        (isset($params['name']) && $params['name']) ? $param_update['name'] = $params['name'] : null;
        (isset($params['order_id']) && $params['order_id'] > 0) ? $param_update['order_id'] = $params['order_id'] : null;
        (isset($params['customer_id']) && $params['customer_id'] > 0) ? $param_update['customer_id'] = $params['customer_id'] : null;
        (isset($params['description']) && $params['description'] > 0) ? $param_update['description'] = $params['description'] : null;
        (isset($params['status']) && $params['status'] > 0) ? $param_update['status'] = $params['status'] : null;
        //
        $order = Requirement::where('id', $id)->update($param_update);
        if ($order) {
            //$order->update($param_update);
            //
            return $order;
        }

        return false;
    }

    public function getAll($params)
    {

        $status = isset($params['status']) ? $params['status'] : -1;
        $query = Requirement::select('*');// Nếu truyền lên status thì so sánh theo status, trái lại lấy theo quyền
        if ($status >= 0) {
            $query->where('status', '!=', $status);
        }
        $query->orderBy('id', 'ASC');
        return $query->get();
    }
}
