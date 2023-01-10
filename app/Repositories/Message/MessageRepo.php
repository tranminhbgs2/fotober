<?php

namespace App\Repositories\Message;

use App\Helpers\Constants;
use App\Models\Message;
use App\Repositories\BaseRepo;

class MessageRepo extends BaseRepo
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hàm lấy ds tin nhắn, có tìm kiếm và phân trang
     *
     * @param $params
     * @param false $is_counting
     *
     * @return mixed
     */
    public function getListing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $status = isset($params['status']) ? $params['status'] : 1;
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        //
        $keyword = translateKeyWord($keyword);
        $offset = ($page_index - 1) * $page_size;
        //
        $query = Message::select(['id', 'customer_id', 'sale_id', 'order_id', 'type', 'content', 'file_name', 'status', 'created_at']);

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('username', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('fullname', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('display_name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('email', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('phone', 'LIKE', "%" . $keyword . "%");
            });
        });

        $query->where('account_type', Constants::ACCOUNT_TYPE_CUSTOMER);

        if ($status >= 0) {
            $query->where('status', $status);
        }

        if ($is_counting) {
            return $query->count();
        } else {
            if ($page_size > 0 && $offset >= 0) {
                $query->take($page_size)->skip($offset);
            }
        }

        $query->orderBy('id', 'ASC');

        return $query->get()->toArray();
    }

    /**
     * Hàm tạo tin nhắn
     * @param $params
     * @return bool
     */
    public function store($params)
    {
        $message = isset($params['message']) ? $params['message'] : null;
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        $type = isset($params['type']) ? $params['type'] : null;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : null;
        $file_name = isset($params['file_name']) ? $params['file_name'] : null;
        $seen = isset($params['seen']) ? $params['seen'] : 0;

        if ($message && $order_id) {
            $mess = new Message();

            $mess->fill([
                'order_id' => $order_id,
                'customer_id' => $customer_id,
                'sale_id' => $sale_id,
                'type' => $type,
                'content' => $message,
                'file_name' => $file_name,
                'seen' => $seen,
                'status' => Constants::MESSAGE_STATUS_SHOW
            ]);

            if ($mess->save()) {
                return true;
            }

        }

        return false;
    }

    /**
     * Hàm lấy chi tiết thông tin KH
     *
     * @param $params
     * @return Customer|\Illuminate\Database\Eloquent\Model|null
     */
    public function getDetail($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;

        if ($id) {
            return $this->customer_model->where('uid', $id)->first();
        } else {
            return null;
        }
    }

    /**
     * Hàm lấy chi tiết theo order id
     *
     * @param $params
     * @return Customer|\Illuminate\Database\Eloquent\Model|null
     */
    public function getOrderById($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        if($order_id){
            $query = Message::where('order_id', $order_id);
            return $query->get()->toArray();
        }
        return [];
    }

    /**
     * Hàm lấy chi tiết thông tin KH
     *
     * @param $params
     * @return Customer|\Illuminate\Database\Eloquent\Model|null
     */
    public function getListAll($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'ASC';

        if ($order_id) {
            $query = Message::select(['id', 'customer_id', 'sale_id', 'order_id', 'type', 'content','file_name', 'status', 'created_at']);

            $query->where('order_id', $order_id);
            $query->where('status', Constants::MESSAGE_STATUS_SHOW);

            if ($customer_id) {
                $query->where('customer_id', $customer_id);
            }
            if ($sale_id) {
                $query->where('sale_id', $sale_id);
            }
            $query->orderBy('id', $order_by);

            return $query->get()->toArray();
        } else {
            return null;
        }
    }

    /**
     * Hàm xử lý upload và cập nhật đường dẫn avatar
     *
     * @param $params
     * @param null $request
     * @param null $customer
     * @return false|mixed
     */
    public function updateFile($params)
    {
        $order_id = $params['order_id'];
        $file_avatar = $params['file'];
        $filename_avatar =  $params['type'] . '_' . time() . '_' . $order_id . '.' . $file_avatar->getClientOriginalExtension();
        $db_path_save = $this->_processUpload($file_avatar, $filename_avatar);
        if ($db_path_save) {
            return $db_path_save;
        }
        return false;
    }


    /**
     * Hàm xử lý upload ảnh vào storage --------------------------------------------------------------------------------
     *
     * @param $file
     * @param $filename
     * @return false|string|string[]
     */
    private function _processUpload($file, $filename)
    {
        if (is_file($file) && $filename) {

            $base_path = Constants::UPLOAD_IMAGE_CHAT;
            // Tạo thư mục lưu lại nếu chưa tồn tại
            //$dir_save_path = $base_path . '/' . date('Y/m');
            $dir_save_path = $base_path;
            if (!file_exists($dir_save_path)) {
                mkdir($dir_save_path, 0777, true);
                chown($dir_save_path, Constants::SSH_USER);
                chgrp($dir_save_path, Constants::SSH_GROUP);
            }

            try {
                $db_path = $file->storeAs($dir_save_path, $filename);
                return str_replace('public/', '', $db_path);
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    
    public function updateSeen($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : null;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        $seen = isset($params['seen']) ? $params['seen'] : 0;
        if($sale_id > 0){
            $query = Message::where('order_id', '=', $order_id)
            ->where('sale_id', $sale_id)
            ->where('seen', 0)
            ->update(['seen' => $seen]);
        }
        if($customer_id > 0){
            $query = Message::where('order_id', '=', $order_id)
            ->where('seen', 0)
            ->where('customer_id', $customer_id)
            ->update(['seen' => $seen]);
        }
        return $query;
    }
    
    public function totalNoSeen($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : null;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        if($sale_id > 0){
            $query = Message::where('order_id', '=', $order_id)
            ->where('sale_id', $sale_id)
            ->where('seen', 0);
        }
        if($customer_id > 0){
            $query = Message::where('order_id', '=', $order_id)
            ->where('seen', 0)
            ->where('customer_id', $customer_id);
        }
        return $query->get();
    }
}
