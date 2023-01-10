<?php

namespace App\Repositories\SuperAdmin;

use App\Helpers\Constants;
use App\Models\Group;
use App\Repositories\BaseRepo;

class GroupRepo extends BaseRepo
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hàm lấy ds nhóm người dùng
     *
     * @param $params
     * @param false $is_counting
     *
     * @return mixed
     */
    public function listing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $status = isset($params['status']) ? $params['status'] : -1;
        $order_by = isset($params['order_by']) ? $params['order_by'] : null;
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        // Mặc định là tạo với quyền nhân viên, super admin ko tạo nhân viên, sale tạo nhân viên hoặc đăng ký
        $account_type = isset($params['account_type']) ? $params['account_type'] : Constants::ACCOUNT_TYPE_STAFF;
        //
        $query = Group::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('code', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('description', 'LIKE', "%" . $keyword . "%");
            });
        });

        // Nếu tạo tk nhân viên thì ko lấy account type là khách hàng
        if ($account_type == Constants::ACCOUNT_TYPE_STAFF) {
            $query->where('code', '!=', Constants::ACCOUNT_TYPE_CUSTOMER);
        }

        // Không lấy loại tk supper admin, tk được tạo khi setup hệ thống
        $query->where('code', '!=', Constants::ACCOUNT_TYPE_SUPER_ADMIN);

        // Lọc theo trạng thái
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

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $order) {
                $query->orderBy($order['field'], $order['direction']);
            }
        } else {
            $query->orderBy('name', 'ASC');
        }

        return $query->get();
    }

}
