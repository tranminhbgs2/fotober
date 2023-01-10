<?php

namespace App\Repositories\Order;

use App\Models\Output;
use App\Repositories\BaseRepo;
use Carbon\Carbon;

class OutputRepo extends BaseRepo
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
        $query = Output::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('link', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('file', 'LIKE', "%" . $keyword . "%");
            });
        });

        // Lọc theo order
        if ($order_id > 0) {
            $query->where('order_id', $order_id);
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
                $sql->select(['id', 'name', 'code', 'status', 'delivered_at', 'cost']);
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

        $query = Output::where('id', $id)->with([
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

        $query = Output::where('order_id', $order_id)->with([
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
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        $link = isset($params['link']) ? $params['link'] : null;
        $file = isset($params['file']) ? $params['file'] : null;
        $type = isset($params['type']) ? $params['type'] : 'IMAGE';
        $fix_request = isset($params['fix_request']) ? $params['fix_request'] : 0;
        //
        if ($order_id > 0 && $customer_id > 0) {
            $output = new Output();
            $output->order_id = $order_id;
            $output->customer_id = $customer_id;
            $output->link = $link;
            $output->file = $file;
            $output->type = $type;
            $output->fix_request = $fix_request;
            //
            $output->save();
            return $output;
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
        $query = Output::where('id', $id)->first();

        if ($query) {
            $query->delete();
            return true;
        }

        return false;
    }

    
    /**
     * Hàm thực hiện cập nhật trạng thái xóa và thực hiện xóa mềm
     *
     * @param $params
     * @return bool
     */
    public function acceptOutput($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $output = Output::find($id);
        Output::where('id', $id)->update([
            'is_accepted' => 1,
            'accepted_at' => Carbon::now()
        ]);
        return $output;

        return false;
    }

    
    /**
     * Hàm thực hiện cập nhật trạng thái xóa và thực hiện xóa mềm
     *
     * @param $params
     * @return bool
     */
    public function requestRevision($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $output = Output::find($id);
        Output::where('id', $id)->update([
            'request_revision' => $params['request_revision'],
            'fix_request' => 1, //Tạo yêu cầu chỉnh sửa
            'accepted_at' => Carbon::now()
        ]);
        return $output;

        return false;
    }

    /**
     * Hàm thực hiện cập nhật output
     *
     * @param $params
     * @return bool
     */
    public function update($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $link = isset($params['link']) ? $params['link'] : null;
        $request_revision = isset($params['request_revision']) ? $params['request_revision'] : null;
        $file = isset($params['file']) ? $params['file'] : null;
        $fix_request = isset($params['fix_request']) ? $params['fix_request'] : 0;
        // print_r($params);
        // die();
        $output = Output::find($id);
        Output::where('id', $id)->update([
            'request_revision' => $request_revision,
            'link' => $link,
            'file' => $file,
            'fix_request' => $fix_request
        ]);
        return $output;

        return false;
    }

    public function getAll($params)
    {

        $order_id = isset($params['order_id']) ? $params['order_id'] : 0;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : 0;
        $query = Output::select('*');// Nếu truyền lên status thì so sánh theo status, trái lại lấy theo quyền
        if ($order_id >= 0) {
            $query->where('order_id', $order_id);
        }
        if ($customer_id >= 0) {
            $query->where('customer_id', '=', $customer_id);
        }
        $query->orderBy('id', 'ASC');
        return $query->get();
    }
}
