<?php

namespace App\Repositories\Report;

use App\Helpers\Constants;
use App\Models\User;
use App\Repositories\BaseRepo;
use Illuminate\Support\Facades\Auth;

class ReportRepo extends BaseRepo
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hàm thống kê số lượng order theo từng trạng thái của từng sale
     *
     * @param $params
     * @param false $is_counting
     * @return mixed
     */
    public function listingOrder($params, $is_counting = false)
    {
        $user_id = isset($params['user_id']) ? $params['user_id'] : -1;
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : 0;

        $query = User::select('*')->where('account_type', Constants::ACCOUNT_TYPE_SALE);

        $query->with([
            'orders' => function($sql){
                $sql->select(['assigned_sale_id', 'customer_id', 'service_id', 'status'])
                    ->selectRaw('COUNT(*) AS total_order')
                    ->groupBy('assigned_sale_id')->groupBy('status')
                    ->orderBy('status', 'ASC');
            }
        ]);

        // Nếu là sale admin thì lấy tất, sale member thì chỉ lấy theo data của member đó
        if ($is_admin == 1) {
            // Nếu lọc theo sale member nào thì lấy theo member đó
            if ($user_id > 0) {
                $query->where('id', $user_id);
            }
        } else {
            $query->where('id', Auth::id());
        }

        if ($is_counting) {
            return $query->count();
        } else {
            $offset = ($page_index - 1) * $page_size;
            if ($page_size > 0 && $offset >= 0) {
                $query->take($page_size)->skip($offset);
            }
        }

        return $query->get();
    }

}
