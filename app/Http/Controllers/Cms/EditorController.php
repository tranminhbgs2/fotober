<?php

namespace App\Http\Controllers\Cms;

use App\Events\NotificationEvent;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Repositories\Download\DownloadRepo;
use App\Repositories\Notification\NotificationRepo;
use App\Repositories\Order\InputRepo;
use App\Repositories\Order\OrderRepo;
use App\Repositories\Order\OutputRepo;
use App\Repositories\Order\RequirementRepo;
use App\Repositories\Service\ServiceRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditorController extends Controller
{
    protected $orderRepo;
    protected $serviceRepo;
    protected $requirementRepo;
    protected $outputRepo;
    protected $inputRepo;
    protected $downloadRepo;
    protected $notificationRepo;

    public function __construct(
        OrderRepo $orderRepo,
        ServiceRepo $serviceRepo,
        RequirementRepo $requirementRepo,
        OutputRepo $outputRepo,
        InputRepo $inputRepo,
        DownloadRepo $downloadRepo,
        NotificationRepo $notificationRepo
    )
    {
        $this->middleware('auth');  // Y/c phải login
        //
        $this->orderRepo = $orderRepo;
        $this->serviceRepo = $serviceRepo;
        $this->requirementRepo = $requirementRepo;
        $this->outputRepo = $outputRepo;
        $this->inputRepo = $inputRepo;
        $this->downloadRepo = $downloadRepo;
        $this->notificationRepo = $notificationRepo;
    }

    /**
     * Hàm show ds đơn hàng
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function order(Request $request)
    {
        // Bắt id khi nhấn xem từ thông báo
        $data['url_id'] = request('id', null);
        $noti_id = request('noti_id', null);

        // Update đã đọc
        $this->notificationRepo->updateRead($noti_id);

        $data['status'] = getOrderStatus(null, Constants::ACCOUNT_TYPE_EDITOR);
        return view(getBladeFromPage('/order/editor/order-index'), $data);
    }

    /**
     * Hàm lấy ds đơn hàng theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderListingAjax(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['status'] = request('status', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['account_type'] = Constants::ACCOUNT_TYPE_EDITOR;
        $params['is_requirement'] = true;
        //
        $data['status'] = getOrderStatus(null, Constants::ACCOUNT_TYPE_EDITOR);
        //
        $data['data'] = $this->orderRepo->getListing($params);
        $total = $this->orderRepo->getListing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        $data['requirements'] = $this->requirementRepo->getAll(['status' => 2]);
        // Đẩy id lấy từ url khi người dùng nhấn từ thông báo
        $data['url_id'] = request('url_id', -1);
        //
        return view(getBladeFromPage('/order/editor/ajax-order-index'), $data);
    }

    /**
     * Hàm lấy thông tin đơn hàng theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderInfoAjax(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $data['order'] = $this->orderRepo->findById(['id' => $id]);
        $data['inputs'] = $this->inputRepo->getListing(['order_id' => $id, 'customer_id' => $user_id]);
        $data['outputs'] = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => $data['order']->customer_id]);
        return view(getBladeFromPage('/order/editor/ajax-order-info'), $data);
    }

    /**
     * Hàm lấy ds yêu cầu
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingRequirement(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['customer_id'] = request('customer_id', null);
        //
        $data['requirements'] = $this->requirementRepo->getListing($params);
        $data['order'] = $this->orderRepo->findById(['id' => $params['order_id']]);
        $data['order_id'] = $params['order_id'];
        $data['customer_id'] = $params['customer_id'];
        $data['account_type'] = Auth::user()->account_type;
        $data['status'] = getRequirementStatus();
        //
        return view(Constants::VIEW_PAGE_PATH . '/order/editor/ajax-order-requirement-index', $data);
    }

    /**
     * Thay đổi trạng thái của đơn hàng
     *
     */
    public function changeStatus(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $result = $this->orderRepo->updateStatus(['id' => $id, 'status' => Constants::ORDER_STATUS_EDITED]);
        if($result){
            // Bắn noti cho qaqc phụ trách
            event(new NotificationEvent([
                'message_vi' => 'Editor vừa xử lý xong yêu cầu đơn hàng: ' . $result->name,
                'order' => $result,
                'order_id' => $result->id,
                'total_no_seen_cus' => 0,
                'total_no_seen_sale' => 0,
                'account_type' => Constants::ACCOUNT_TYPE_QAQC,
                'sender_id' => Auth::id(),
                'receiver_id' => $result->assigned_qaqc_id
            ]));

            return response()->json([
                'code' => 200,
                'message' => 'Thành công',
                'data' => null
            ]);
        } else {
            return response()->json([
            'code' => 400,
            'message' => 'Thất bại',
            'data' => null
        ]);}
    }

    /**
     * Thay đổi trạng thái của requirement
     *
     */
    public function changeStatusRequirement(Request $request)
    {
        $id = request('id', null);
        $status_id = request('status_id', null);
        $result = $this->requirementRepo->update(['id' => $id, 'status' => $status_id]);
        if($result){
            return response()->json([
                'code' => 200,
                'message' => 'Thành công',
                'data' => null
            ]);
        } else {
            return response()->json([
            'code' => 400,
            'message' => 'Thất bại',
            'data' => null
        ]);}
    }


    public function downZip()
    {
        $order_id = request('order_id', null);
        $user_id = request('user_id', null);
        $down = $this->downloadRepo->downloadZip(['order_id' => $order_id, 'user_id' => $user_id]);
        if($down){
            return response()->download($down);
        } else{
            return redirect()->route('dashboard_home');
        }
    }
}
