<?php

namespace App\Repositories\Service;

use App\Helpers\Constants;
use App\Models\Service;
use App\Repositories\BaseRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ServiceRepo extends BaseRepo
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
    public function listing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $status = isset($params['status']) ? $params['status'] : -1;
        $group_code = isset($params['group_code']) ? $params['group_code'] : 0;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 50;
        //
        $query = Service::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('code', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('description', 'LIKE', "%" . $keyword . "%");
            });
        });

        if ($group_code > 0) {
            $query->where('group_code', $group_code);
        }

        if ($status >= 0) {
            $query->where('status', $status);
        } else {
            // Nếu là super admin thì lấy tất
            if (Auth::user()->account_type != Constants::ACCOUNT_TYPE_SUPER_ADMIN) {
                $query->where('status', 1);
            }
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
            $query->orderBy('id', 'ASC');
        }

        return $query->get();
    }

    public function findById($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $user = Service::where('id', $id)->first();
        return $user;
    }

    public function changeStatus($params)
    {
        $status = (isset($params['status']) && in_array($params['status'], [0,1,2,3])) ? $params['status'] : 0;
        $id = (isset($params['id']) && $params['id'] > 0) ? $params['id'] : null;

        if ($status >= 0 && $id > 0) {
            Service::where('id', $id)
                ->update(['status' => $status]);

            return true;
        }

        return false;
    }

    public function store($params)
    {
        $name = isset($params['name']) ? $params['name'] : null;
        $code = isset($params['code']) ? $params['code'] : null;
        $from_price = isset($params['from_price']) ? $params['from_price'] : null;
        $group_code = isset($params['group_code']) ? $params['group_code'] : null;
        $type = isset($params['type']) ? $params['type'] : 'BEFORE_AFTER';
        $before_photo = isset($params['before_photo']) ? $params['before_photo'] : null;
        $after_photo = isset($params['after_photo']) ? $params['after_photo'] : null;
        $video_link = isset($params['video_link']) ? $params['video_link'] : null;
        $video_src = isset($params['video_src']) ? $params['video_src'] : null;
        $read_more = isset($params['read_more']) ? $params['read_more'] : null;
        $image = isset($params['image']) ? $params['image'] : null;
        $description = isset($params['description']) ? $params['description'] : null;
        $sort = isset($params['sort']) ? $params['sort'] : 1;
        $status = isset($params['status']) ? $params['status'] : 0;

        if ($name && $code && in_array($status, [0,1,2])) {
            $group_name = getGroupService($group_code);

            $max_number = Service::selectRaw('MAX(number) as max_number')->get();
            if (isset($max_number[0]->max_number) && intval($max_number[0]->max_number) > 0) {
                $number = intval($max_number[0]->max_number) + 1;
            } else {
                $number = 1;
            }
            if ($number < 10) {
                $number = '0' . $number;
            }

            $service = Service::create([
                'name' => $name,
                'code' => $code,
                'number' => $number,
                'from_price' => $from_price,
                'group_code' => $group_code,
                'type' => $type,
                'group_name' => $group_name,
                'before_photo' => $before_photo,
                'after_photo' => $after_photo,
                'video_link' => $video_link,
                'video_src' => $video_src,
                'read_more' => $read_more,
                'image' => $image,
                'description' => $description,
                'sort' => $sort,
                'status' => $status,
            ]);

            return $service;
        }

        return null;
    }

    /**
     * Câp nhật thông tin nhân viên
     * @param $params
     * @return bool
     */
    public function update($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $name = isset($params['name']) ? $params['name'] : null;
        $code = isset($params['code']) ? $params['code'] : null;
        $from_price = isset($params['from_price']) ? $params['from_price'] : null;
        $group_code = isset($params['group_code']) ? $params['group_code'] : null;
        $type = isset($params['type']) ? $params['type'] : 'BEFORE_AFTER';
        $before_photo = isset($params['before_photo']) ? $params['before_photo'] : null;
        $after_photo = isset($params['after_photo']) ? $params['after_photo'] : null;
        $video_link = isset($params['video_link']) ? $params['video_link'] : null;
        $video_src = isset($params['video_src']) ? $params['video_src'] : null;
        $read_more = isset($params['read_more']) ? $params['read_more'] : null;
        $image = isset($params['image']) ? $params['image'] : null;
        $description = isset($params['description']) ? $params['description'] : null;
        $sort = isset($params['sort']) ? $params['sort'] : 1;

        if ($id > 0 && $name && $code) {
            $group_name = getGroupService($group_code);
            $service = Service::where('id', $id)->update([
                'name' => $name,
                'code' => $code,
                'from_price' => $from_price,
                'group_code' => $group_code,
                'type' => $type,
                'group_name' => $group_name,
                'before_photo' => $before_photo,
                'after_photo' => $after_photo,
                'video_link' => $video_link,
                'video_src' => $video_src,
                'read_more' => $read_more,
                'image' => $image,
                'description' => $description,
                'sort' => $sort,
            ]);

            if ($service) {
                return true;
            }
        }

        return false;
    }

    /**
     * Hàm lấy ds dịch vụ, nếu super admin thì lấy tất, ngược lại chỉ lấy dv đang kích hoạt
     * Tạm thời lấy tất cả vì có thể có DV mới khóa nên các order cũ không tìm được
     *
     * @param $params
     * @return mixed
     */
    public function getAll($params)
    {
        $query = Service::select('*');
        /*if (Auth::user()->account_type != Constants::ACCOUNT_TYPE_SUPER_ADMIN) {
            $query->where('status', 1);
        }*/
        $query->orderBy('name', 'ASC');

        return $query->get();
    }
}
