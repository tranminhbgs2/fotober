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
use App\Repositories\Payment\PaymentDetailRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\User\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $orderRepo;
    protected $serviceRepo;
    protected $userRepo;
    protected $requirementRepo;
    protected $paymentDetailRepo;
    protected $outputRepo;
    protected $inputRepo;
    protected $downloadRepo;
    protected $notificationRepo;

    public function __construct(
        OrderRepo $orderRepo,
        ServiceRepo $serviceRepo,
        UserRepo $userRepo,
        RequirementRepo $requirementRepo,
        PaymentDetailRepo $paymentDetailRepo,
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
        $this->userRepo = $userRepo;
        $this->requirementRepo = $requirementRepo;
        $this->paymentDetailRepo = $paymentDetailRepo;
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

        $data['editors'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_EDITOR
        ]);
        $data['status'] = getOrderStatus(null, Constants::ACCOUNT_TYPE_ADMIN);
        return view(getBladeFromPage('/order/admin/order-index'), $data);
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
        $params['assigned_editor_id'] = request('assigned_editor_id', -1);
        $params['status'] = request('status', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['account_type'] = Constants::ACCOUNT_TYPE_ADMIN;
        //
        $data['data'] = $this->orderRepo->getListing($params);
        $total = $this->orderRepo->getListing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];

        $data['editors'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_EDITOR
        ]);
        // Đẩy id lấy từ url khi người dùng nhấn từ thông báo
        $data['url_id'] = request('url_id', -1);

        return view(getBladeFromPage('/order/admin/ajax-order-index'), $data);
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
        $data['outputs'] = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => $data['order']->customer_id]);
        $data['inputs'] = $this->inputRepo->getListing(['order_id' => $id, 'customer_id' => $user_id]);
        return view(getBladeFromPage('/order/admin/ajax-order-info'), $data);
    }

    /**
     * Cập nhật trạng thái gán cho sale, editor
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function orderAssignEditorAjax(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['editor_id'] = request('editor_id', null);
        $params['response_type'] = request('response_type', 'JSON');

        $result = $this->orderRepo->updateAssignSale($params);
        if ($result) {
            // Bắn noti cho admin phụ trách
            event(new NotificationEvent([
                'message_vi' => 'Đơn hàng cần xử lý: ' . $result->name,
                'order' => $result,
                'order_id' => $result->id,
                'total_no_seen_cus' => 0,
                'total_no_seen_sale' => 0,
                'account_type' => Constants::ACCOUNT_TYPE_EDITOR,
                'sender_id' => Auth::id(),
                'receiver_id' => $result->assigned_editor_id
            ]));

            // Trường hợp cập nhật thành công
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => null
                ]);
            }
            return true;
        }

        // Trường hợp cập nhật lỗi
        if ($params['response_type'] == 'JSON') {
            return response()->json([
                'code' => 400,
                'message' => 'Not OK',
                'data' => null
            ]);
        }
        return false;
    }

    /**
     * Hàm xóa item requirement
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function deleteRequirement()
    {
        $params['order_id'] = request('order_id', null);
        $params['customer_id'] = request('customer_id', null);
        $params['id'] = request('id', null);
        $params['response_type'] = request('response_type', 'JSON');
        //
        $result = $this->requirementRepo->delete($params);
        if ($result) {
            // Trường hợp cập nhật thành công
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $params
                ]);
            }
            return true;
        }

        // Trường hợp cập nhật lỗi
        if ($params['response_type'] == 'JSON') {
            return response()->json([
                'code' => 400,
                'message' => 'Not OK',
                'data' => null
            ]);
        }
        return false;
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
        $data['order_id'] = $params['order_id'];
        $data['customer_id'] = $params['customer_id'];
        $data['order'] = $this->orderRepo->findById(['id' => $data['order_id']]);
        $data['account_type'] = Auth::user()->account_type;
        $data['status'] = getRequirementStatus();
        //
        return view(Constants::VIEW_PAGE_PATH . '/order/admin/ajax-order-requirement-index', $data);
    }

    /**
     * Hàm thêm mới một yêu cầu của đơn hàng
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function storeRequirement(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['customer_id'] = request('customer_id', null);
        $params['name'] = request('name', null);
        $params['description'] = request('dsc', null);
        $params['status'] = request('status', null);
        $params['response_type'] = request('response_type', 'JSON');
        //
        $result = $this->requirementRepo->store($params);
        if ($result) {
            // Trường hợp cập nhật thành công
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $params
                ]);
            }
            return true;
        }

        // Trường hợp cập nhật lỗi
        if ($params['response_type'] == 'JSON') {
            return response()->json([
                'code' => 400,
                'message' => 'Not OK',
                'data' => null
            ]);
        }
        return false;
    }

    /**
     * Thay đổi trạng thái của đơn hàng
     *
     */
    public function changeStatus(Request $request)
    {
        $id = request('order_id', null);
        $status = request('status', null);
        $result = $this->orderRepo->updateStatus(['id' => $id, 'status' => $status]);
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
