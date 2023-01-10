<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepo $orderRepo)
    {
        $this->middleware('auth');
        $this->orderRepo = $orderRepo;
    }

    /**
     * Trang dashboard
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function home(Request $request)
    {
        $data = [];

        /*switch (Auth::user()->account_type)
        {
            case Constants::ACCOUNT_TYPE_CUSTOMER: break;
            case Constants::ACCOUNT_TYPE_SALE: break;
            case Constants::ACCOUNT_TYPE_ADMIN: break;
            case Constants::ACCOUNT_TYPE_EDITOR: break;
            case Constants::ACCOUNT_TYPE_QAQC: break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: break;
            default:
        }

        return view(
            Constants::VIEW_PAGE_PATH . '.dashboard.db-' . strtolower(Auth::user()->account_type),
            ['data' => $data]
        );*/

        // Điều hướng về trang orders theo quyền
        $link = '';
        switch (Auth::user()->account_type) {
            case Constants::ACCOUNT_TYPE_CUSTOMER: $link = redirect(route('customer_order')); break;
            case Constants::ACCOUNT_TYPE_SALE: $link = redirect(route('sale_order')); break;
            case Constants::ACCOUNT_TYPE_ADMIN: $link = redirect(route('admin_order')); break;
            case Constants::ACCOUNT_TYPE_EDITOR: $link = redirect(route('editor_order')); break;
            case Constants::ACCOUNT_TYPE_QAQC: $link = redirect(route('qaqc_order')); break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: $link = redirect(route('superadmin_listing_order')); break;
        }

        return $link;

    }

    /**
     * Hàm lấy con số tổng hợp ở dashboard
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function summaryOrderAjax(Request $request)
    {
        $params['group'] = request('group', null);
        $params['customer_id'] = (Auth::user()->account_type == Constants::ACCOUNT_TYPE_CUSTOMER) ? Auth::id() : null;
        $params['from_date'] = date('Y-m-d');
        $params['to_date'] = date('Y-m-d');

        $data = $this->orderRepo->summaryOrder($params);

        return response()->json($data);

    }

    /**
     * Lấy ds order nháp của KH
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function draftOrderAjax(Request $request)
    {
        $params['status'] = request('status', Constants::ORDER_STATUS_DRAFT);
        $params['group'] = request('group', null);
        $params['account_type'] = Auth::user()->account_type;
        $params['customer_id'] = Auth::id();

        $data['data'] = $this->orderRepo->draftOrder($params);
        return view('themes/cms/ace/pages/dashboard/ajax/customer-ajax-order', $data);
    }

    /**
     * Lấy ds order mới tạo của KH, vừa gửi cho Sale
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function newOrderAjax(Request $request)
    {
        $params['status'] = request('status', Constants::ORDER_STATUS_NEW);
        $params['group'] = request('group', null);
        $params['account_type'] = Auth::user()->account_type;
        $params['is_admin'] = Auth::user()->is_admin;

        $data['data'] = $this->orderRepo->newOrder($params);

        switch ($params['group']) {
            case Constants::ACCOUNT_TYPE_CUSTOMER: break;
            case Constants::ACCOUNT_TYPE_SALE: $view_path = '/dashboard/ajax/sale-ajax-order-new'; break;
            case Constants::ACCOUNT_TYPE_ADMIN: $view_path = '/dashboard/ajax/admin-ajax-order-new'; break;
            case Constants::ACCOUNT_TYPE_EDITOR: $view_path = '/dashboard/ajax/editor-ajax-order-new'; break;
            case Constants::ACCOUNT_TYPE_QAQC: $view_path = '/dashboard/ajax/qaqc-ajax-order-new'; break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: $view_path = '/dashboard/ajax/super_admin-ajax-order-new'; break;
            default: $view_path = '';
        }

        return view(Constants::VIEW_PAGE_PATH . $view_path, $data);
    }

    /**
     * Hàm lấy ds order gần đến ngày deadline nhất
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function deadlineOrderAjax(Request $request)
    {
        $params['page_index'] = 1;
        $params['page_size'] = 20;
        $params['order_by'] = [
            ['field' => 'deadline', 'direction' => 'ASC']
        ];
        $params['group'] = request('group', null);
        $params['account_type'] = Auth::user()->account_type;
        $params['is_admin'] = Auth::user()->is_admin;

        $data['data'] = $this->orderRepo->deadlineOrder($params);

        switch ($params['group']) {
            case Constants::ACCOUNT_TYPE_CUSTOMER: break;
            case Constants::ACCOUNT_TYPE_SALE: $view_path = '/dashboard/ajax/sale-ajax-order-deadline'; break;
            case Constants::ACCOUNT_TYPE_ADMIN: $view_path = '/dashboard/ajax/admin-ajax-order-deadline'; break;
            case Constants::ACCOUNT_TYPE_EDITOR: $view_path = '/dashboard/ajax/editor-ajax-order-deadline'; break;
            case Constants::ACCOUNT_TYPE_QAQC: $view_path = '/dashboard/ajax/qaqc-ajax-order-deadline'; break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: $view_path = '/dashboard/ajax/super_admin-ajax-order-deadline'; break;
            default: $view_path = '';
        }

        return view(Constants::VIEW_PAGE_PATH . $view_path, $data);
    }

    /**
     * Hàm lấy ds order gần đây nhất của KH
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function recentOrderAjax(Request $request)
    {
        $params['page_index'] = 1;
        $params['page_size'] = 20;
        $params['order_by'] = [
            ['field' => 'id', 'direction' => 'DESC']
        ];
        $params['group'] = request('group', null);
        $params['account_type'] = Auth::user()->account_type;

        $data['data'] = $this->orderRepo->recentOrder($params);
        return view('themes/cms/ace/pages/dashboard/ajax/customer-ajax-order', $data);
    }

    /**
     * Hàm lấy order đã chỉnh sửa
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function editedOrderAjax(Request $request)
    {
        $params['status'] = request('status', Constants::ORDER_STATUS_EDITED);
        $params['group'] = request('group', null);
        $params['account_type'] = Auth::user()->account_type;
        $params['is_admin'] = Auth::user()->is_admin;

        $data['data'] = $this->orderRepo->getListing($params);

        switch ($params['group']) {
            case Constants::ACCOUNT_TYPE_CUSTOMER: break;
            case Constants::ACCOUNT_TYPE_SALE: $view_path = '/dashboard/ajax/sale-ajax-order-edited'; break;
            case Constants::ACCOUNT_TYPE_ADMIN: $view_path = '/dashboard/ajax/admin-ajax-order-edited'; break;
            case Constants::ACCOUNT_TYPE_EDITOR: $view_path = '/dashboard/ajax/editor-ajax-order-edited'; break;
            case Constants::ACCOUNT_TYPE_QAQC: $view_path = '/dashboard/ajax/qaqc-ajax-order-edited'; break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: break;
            default: $view_path = '';
        }

        return view(Constants::VIEW_PAGE_PATH . $view_path, $data);
    }

    /**
     * Hàm lấy order phải làm lại
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function redoOrderAjax(Request $request)
    {
        $params['status'] = request('status', Constants::ORDER_STATUS_REDO);
        $params['group'] = request('group', null);
        $params['account_type'] = Auth::user()->account_type;
        $params['is_admin'] = Auth::user()->is_admin;

        $data['data'] = $this->orderRepo->getListing($params);

        switch ($params['group']) {
            case Constants::ACCOUNT_TYPE_CUSTOMER: break;
            case Constants::ACCOUNT_TYPE_SALE: $view_path = '/dashboard/ajax/sale-ajax-order-redo'; break;
            case Constants::ACCOUNT_TYPE_ADMIN: $view_path = '/dashboard/ajax/admin-ajax-order-redo'; break;
            case Constants::ACCOUNT_TYPE_EDITOR: $view_path = '/dashboard/ajax/editor-ajax-order-redo'; break;
            case Constants::ACCOUNT_TYPE_QAQC: $view_path = '/dashboard/ajax/qaqc-ajax-order-redo'; break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: break;
            default: $view_path = '';
        }

        return view(Constants::VIEW_PAGE_PATH . $view_path, $data);
    }
}
