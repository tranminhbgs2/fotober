<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Repositories\BaseRepo;
use Carbon\Carbon;

class NotificationRepo extends BaseRepo
{

    public function __construct()
    {
        parent::__construct();
        //
    }

    /**
     * API tìm kiếm HS theo SSCID
     * URL: {{url}}/api/v1/students/search-by-sscid
     *
     * @param $params
     * @return array|null
     */
    public function listing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $receiver_id = isset($params['receiver_id']) ? $params['receiver_id'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        //
        $is_read = isset($params['is_read']) ? $params['is_read'] : -1;

        $query = Notification::select(['id', 'title_vi', 'title_en', 'order_id', 'content', 'receiver_id', 'is_read', 'read_at', 'created_at']);

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('title_vi', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('title_en', 'LIKE', "%" . $keyword . "%");
            });
        });

        if ($receiver_id > 0) {
            $query->where('receiver_id', $receiver_id);
        }

        if ($is_read >= 0) {
            $query->where('is_read', $is_read);
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
            $query->orderBy('id', 'DESC');
        }

        return $query->get();

    }

    /**
     * Hàm cập nhật đã đọc
     *
     * @param $id
     * @return bool
     */
    public function updateRead($id)
    {
        $noti = Notification::where('id', $id)->first();
        if ($noti && $noti->is_read == 0) {
            $noti->is_read = 1;
            $noti->read_at = Carbon::now();
            $noti->save();
            return true;
        }

        return false;
    }


}
