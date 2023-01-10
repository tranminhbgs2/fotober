<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Repositories\Notification\NotificationRepo;
use App\Repositories\Order\OrderRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationRepo;

    public function __construct(NotificationRepo $notificationRepo)
    {
        $this->middleware('auth');
        $this->notificationRepo = $notificationRepo;
    }

    /**
     * Trang danh sách thông báo
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view(getBladeFromPage('/notification/notification-index'), []);
    }

    /**
     * Lấy ds thông báo bằng ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View'
     */
    public function listingAjax(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['receiver_id'] = Auth::id();

        $data['data'] = $this->notificationRepo->listing($params);
        $total = $this->notificationRepo->listing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];

        return view(getBladeFromPage('/notification/ajax-notification-index'), $data);
    }

    /**
     * Lấy thông tin thông báo hiển thị ở menu
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function listingByUserAjax(Request $request)
    {
        $params['receiver_id'] = request('receiver_id', Auth::id());
        $params['is_counting'] = request('is_counting', false);

        if ($params['is_counting']) {
            $params['is_read'] = 0;
            return response()->json([
                'code' => 200,
                'message' => 'Success',
                'data' => [
                    'total' => $this->notificationRepo->listing($params, true)
                ]
            ]);
        } else {
            $params['is_read'] = 0;
            $data['notifications'] = $this->notificationRepo->listing($params);
            $data['account_type'] = Auth::user()->account_type; // Đẩy sang để tạo route tương ứng
            return view(Constants::VIEW_PAGE_PATH . '/notification/notification-top-menu', $data);
        }
    }

}
