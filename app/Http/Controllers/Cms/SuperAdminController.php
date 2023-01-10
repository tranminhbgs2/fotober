<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Requests\User\StoreStaffRequest;
use App\Http\Requests\User\UpdateStaffRequest;
use App\Repositories\Customer\CustomerRepo;
use App\Repositories\Message\MessageRepo;
use App\Repositories\Order\OrderRepo;
use App\Repositories\Order\OutputRepo;
use App\Repositories\Order\RequirementRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\SuperAdmin\AuthLogRepo;
use App\Repositories\SuperAdmin\GroupRepo;
use App\Repositories\User\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    protected $authLogRepo;
    protected $customerRepo;
    protected $groupRepo;
    protected $messageRepo;
    protected $orderRepo;
    protected $outputRepo;
    protected $requirementRepo;
    protected $serviceRepo;
    protected $userRepo;

    public function __construct(AuthLogRepo $authLogRepo, CustomerRepo $customerRepo, GroupRepo $groupRepo, MessageRepo $messageRepo,
                                OutputRepo $outputRepo, OrderRepo $orderRepo, RequirementRepo $requirementRepo,
                                ServiceRepo $serviceRepo, UserRepo $userRepo)
    {
        $this->middleware('auth');
        //
        $this->authLogRepo = $authLogRepo;
        $this->customerRepo = $customerRepo;
        $this->groupRepo = $groupRepo;
        $this->messageRepo = $messageRepo;
        $this->orderRepo = $orderRepo;
        $this->outputRepo = $outputRepo;
        $this->requirementRepo = $requirementRepo;
        $this->serviceRepo = $serviceRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Danh sách khách hàng --------------------------------------------------------------------------------------------
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingCustomer(Request $request)
    {
        $data = [];
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        $data['status'] = getCustomerStatus();
        return view(getBladeFromPage('/superadmin/customer/customer-index'), $data);
    }

    /**
     * Lấy ds KH qua ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingAjaxCustomer(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['manager_by'] = request('manager_by', -1);
        $params['status'] = request('status', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['account_type'] = Constants::ACCOUNT_TYPE_SUPER_ADMIN;

        $data['data'] = $this->customerRepo->getListing($params);
        $total = $this->customerRepo->getListing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        $data['status'] = getCustomerStatus();

        return view(getBladeFromPage('/superadmin/customer/ajax-customer-index'), $data);
    }

    /**
     * Xem thông tin KH qua ajax và popup
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showInfoAjaxCustomer()
    {
        $id = request('customer_id', null);
        $data['customer'] = $this->customerRepo->getDetail(['id' => $id]);
        return view(getBladeFromPage('/customer/sale/ajax-customer-info'), $data);
    }

    /**
     * Thay đổi status của KH qua ajax
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function changeStatusAjaxCustomer(Request $request)
    {
        $params['status'] = request('status', null);
        $params['customer_id'] = request('customer_id', null);
        $params['response_type'] = request('response_type', 'JSON');

        $params['account_type'] = Constants::ACCOUNT_TYPE_CUSTOMER;

        $result = $this->customerRepo->changeStatus($params);

        if ($result) {
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
     * Danh sách order -------------------------------------------------------------------------------------------------
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingOrder(Request $request)
    {
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        $data['status'] = getOrderStatus(null, Constants::ACCOUNT_TYPE_SALE);
        return view(getBladeFromPage('/superadmin/order/order-index'), $data);
    }

    /**
     * Lấy ds order qua ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingAjaxOrder(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['assigned_sale_id'] = request('assigned_sale_id', -1);
        $params['status'] = request('status', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['account_type'] = Constants::ACCOUNT_TYPE_SUPER_ADMIN;
        //
        $data['data'] = $this->orderRepo->getListing($params);
        $total = $this->orderRepo->getListing($params, true);
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        return view(getBladeFromPage('/superadmin/order/ajax-order-index'), $data);
    }

    /**
     * Xem chi tiết đơn hàng
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        $id = request('id', null);
        //$noti_id = request('noti_id', null);

        // Update đã đọc
        //$this->notificationRepo->updateRead($noti_id);

        $data['services'] = $this->serviceRepo->listing([
            'status' => 1,
            'page_index' => 1,
            'page_size' => 20,
            'order_by' => [
                ['field' => 'name', 'direction' => 'ASC']
            ],
        ]);
        //
        $data['user_id'] = Auth::id();
        $data['order'] = $this->orderRepo->findById(['id' => $id]);

        // Check xem sale đang login có được chat không: 1-có, 0-không
        $data['is_chat'] = (Auth::id() == $data['order']->assigned_sale_id) ? true : false;

        $data['messages'] = $this->messageRepo->getListAll(['order_id' => $id]);
        $data['file_messages'] = $this->messageRepo->getListAll(['order_id' => $id, 'order_by' => 'DESC']);

        return view(getBladeFromPage('/superadmin/order/order-detail'), $data);

    }

    /**
     * Form cập nhật order
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $id = request('id', null);

        $data['services'] = $this->serviceRepo->listing([
            'status' => 1,
            'page_index' => 1,
            'page_size' => 20,
            'order_by' => [
                ['field' => 'name', 'direction' => 'ASC']
            ],
        ]);

        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        //
        $data['order'] = $this->orderRepo->findById(['id' => $id]);
        $data['account_type'] = Constants::ACCOUNT_TYPE_SALE;
        $data['is_admin'] = Auth::user()->is_admin;
        $data['turn_arround_times'] = getTurnArroundTime();

        return view(getBladeFromPage('/superadmin/order/order-edit'), $data);
    }

    /**
     * Hàm cập nhật thông tin đơn hàng
     *
     * @param UpdateOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateOrderRequest $request)
    {
        $params['id'] = request('id', null);
        $params['name'] = request('name', null);
        $params['service_id'] = request('service_id', null);
        $params['turn_arround_time'] = request('turn_arround_time', null);
        $params['notes'] = request('notes', null);

        if(Auth::user()->is_admin){
            $params['assigned_sale_id'] = request('sale_id', null);
        }
        //
        $result = $this->orderRepo->update($params);
        if ($result) {
            $message = trans('fotober.order.mess_update_success');
        } else {
            $message = trans('fotober.order.mess_update_failed');
        }
        //
        return redirect()->route('superadmin_listing_order')->with('message', $message);
    }

    /**
     * Xem thông tin order
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showInfoAjaxOrder(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $data['order'] = $this->orderRepo->findById(['id' => $id]);
        $data['outputs'] = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => $data['order']->customer_id]);
        return view(getBladeFromPage('/superadmin/order/ajax-order-info'), $data);
    }

    /**
     * Xem ds yêu cầu
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingRequirementAjaxOrder(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['customer_id'] = request('customer_id', null);
        //
        $data['requirements'] = $this->requirementRepo->getListing($params);
        $data['order_id'] = $params['order_id'];
        $data['customer_id'] = $params['customer_id'];
        $data['account_type'] = Auth::user()->account_type;
        $data['status'] = getRequirementStatus();
        //
        return view(Constants::VIEW_PAGE_PATH . '/superadmin/order/ajax-order-requirement-index', $data);
    }


    /**
     * Hàm lấy ds nhóm người dùng --------------------------------------------------------------------------------------
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingGroup(Request $request)
    {
        $data = [];
        return view(getBladeFromPage('/superadmin/group/group-index'), $data);
    }

    /**
     * Hàm lấy ds nhóm người dùng theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingAjaxGroup(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);

        $data['data'] = $this->groupRepo->listing($params);
        $data['status'] = getGroupStatus();
        unset($data['status'][0]);

        return view(getBladeFromPage('/superadmin/group/ajax-group-index'), $data);
    }

    /**
     * Hiển thị ds người dùng: Customer, Sale, Admin, Editor, Qaqc, ----------------------------------------------------
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingUser(Request $request)
    {
        $data = [];
        $data['status'] = getCustomerStatus();
        $data['account_type'] = $this->groupRepo->listing([]);
        return view(getBladeFromPage('/superadmin/user/user-index'), $data);
    }

    public function listingAjaxUser(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['account_type'] = request('account_type', null);
        $params['status'] = request('status', null);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['scope'] = Constants::ACCOUNT_TYPE_STAFF;
        //
        $data['data'] = $this->userRepo->listing($params);
        $total = $this->userRepo->listing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        $data['status'] = getGroupStatus();

        return view(getBladeFromPage('/superadmin/user/ajax-user-index'), $data);
    }

    public function createUser(Request $request)
    {
        $data['group'] = $this->groupRepo->listing(['account_type' => Constants::ACCOUNT_TYPE_STAFF]);
        $data['status'] = getCustomerStatus();

        return view(getBladeFromPage('/superadmin/user/user-create'), $data);
    }

    public function storeUser(StoreStaffRequest $request)
    {
        $params['group_id'] = request('group_id', null);
        $params['is_admin'] = request('is_admin', null);
        $params['fullname'] = request('fullname', null);
        $params['birthday'] = request('birthday', null);
        $params['gender'] = request('gender', 3);
        $params['phone'] = request('phone', null);
        $params['email'] = request('email', null);
        $params['password'] = request('password', null);
        $params['address'] = request('address', null);
        $params['status'] = request('status', 0);
        //
        $result = $this->userRepo->storeStaff($params);

        if ($result) {
            $message = 'Thêm mới thành công';
            return redirect()->route('superadmin_listing_user')->with('success', $message);
        } else {
            $message = 'Thêm mới không thành công';
            return redirect()->route('superadmin_listing_user')->with('danger', $message);
        }
    }

    public function editUser(Request $request)
    {
        $params['id'] = request('id', null);
        $data['user'] = $this->userRepo->findById($params);
        $data['group'] = $this->groupRepo->listing([]);

        return view(getBladeFromPage('/superadmin/user/user-edit'), $data);
    }

    /**
     * Hàm cập nhật thông tin nhân viên
     *
     * @param Request $request
     */
    public function updateUser(UpdateStaffRequest $request)
    {
        $params['id'] = request('id', null);
        //$params['group_id'] = request('group_id', null);
        //$params['is_admin'] = request('is_admin', null);
        $params['fullname'] = request('fullname', null);
        $params['birthday'] = request('birthday', null);
        $params['gender'] = request('gender', 3);
        $params['phone'] = request('phone', null);
        $params['address'] = request('address', null);
        //
        $result = $this->userRepo->updateStaff($params);

        if ($result) {
            $message = 'Cập nhật thành công';
            return redirect()->route('superadmin_listing_user')->with('success', $message);
        } else {
            $message = 'Cập nhật không thành công';
            return redirect()->route('superadmin_listing_user')->with('danger', $message);
        }
    }

    /**
     * Hàm thay đổi trạng thái nhân viên
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function changeStatusAjaxUser(Request $request)
    {
        $params['status'] = request('status', null);
        $params['user_id'] = request('user_id', null);
        $params['response_type'] = request('response_type', 'JSON');

        $result = $this->userRepo->changeStatusStaff($params);

        if ($result) {
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
     * Danh sách chương trình, sự kiện ---------------------------------------------------------------------------------
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingEvent(Request $request)
    {
        $data = [];
        $data['status'] = getServiceStatus();
        return view(getBladeFromPage('/superadmin/event/event-index'), $data);
    }

    public function listingAjaxEvent(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['status'] = request('status', null);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        //
        $params['order_by'] = [
            ['field' => 'name', 'direction' => 'ASC'],
            ['field' => 'status', 'direction' => 'ASC'],
        ];
        //
        $data['data'] = $this->serviceRepo->listing($params);
        $total = $this->serviceRepo->listing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        $data['status'] = getServiceStatus();

        return view(getBladeFromPage('/superadmin/event/ajax-event-index'), $data);
    }

    /**
     * Danh sách dịch vụ -----------------------------------------------------------------------------------------------
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingService(Request $request)
    {
        $data = [];
        $data['status'] = getServiceStatus();
        return view(getBladeFromPage('/superadmin/service/service-index'), $data);
    }

    public function listingAjaxService(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['status'] = request('status', null);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        //
        $params['order_by'] = [
            ['field' => 'sort', 'direction' => 'ASC'],
        ];
        //
        $data['data'] = $this->serviceRepo->listing($params);
        $total = $this->serviceRepo->listing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        $data['status'] = getServiceStatus();

        return view(getBladeFromPage('/superadmin/service/ajax-service-index'), $data);
    }

    public function changeStatusAjaxService(Request $request)
    {
        $params['status'] = request('status', null);
        $params['id'] = request('id', null);
        $params['response_type'] = request('response_type', 'JSON');

        $result = $this->serviceRepo->changeStatus($params);

        if ($result) {
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

    public function createService(Request $request)
    {
        $data['group_code'] = getGroupService();
        $data['status'] = getServiceStatus();
        $data['types'] = getServiceType();

        return view(getBladeFromPage('/superadmin/service/service-create'), $data);
    }

    public function storeService(StoreServiceRequest $request)
    {
        $params['name'] = request('name', null);
        $params['code'] = request('code', null);
        $params['from_price'] = request('from_price', null);
        $params['group_code'] = request('group_code', null);
        $params['type'] = request('type', null);
        $params['before_photo'] = request('before_photo', null);
        $params['after_photo'] = request('after_photo', null);
        $params['video_link'] = request('video_link', null);
        $params['read_more'] = request('read_more', null);
        $params['image'] = request('image', null);
        $params['description'] = request('description', null);
        $params['sort'] = request('sort', 1);
        $params['status'] = request('status', 0);
        // Tính toán nguồn video từ url của nó
        $params['video_src'] = getServiceVideoSrc($params['video_link']);
        //
        $result = $this->serviceRepo->store($params);

        if ($result) {
            $message = 'Thêm mới thành công';
            return redirect()->route('superadmin_listing_service')->with('success', $message);
        } else {
            $message = 'Thêm mới không thành công';
            return redirect()->route('superadmin_listing_service')->with('danger', $message);
        }
    }

    public function editService(Request $request)
    {
        $params['id'] = request('id', null);
        $data['group_code'] = getGroupService();
        $data['service'] = $this->serviceRepo->findById($params);
        $data['types'] = getServiceType();

        return view(getBladeFromPage('/superadmin/service/service-edit'), $data);
    }

    public function updateService(UpdateServiceRequest $request)
    {
        $params['id'] = request('id', null);
        $params['name'] = request('name', null);
        $params['code'] = request('code', null);
        $params['from_price'] = request('from_price', null);
        $params['group_code'] = request('group_code', null);
        $params['type'] = request('type', null);
        $params['before_photo'] = request('before_photo', null);
        $params['after_photo'] = request('after_photo', null);
        $params['video_link'] = request('video_link', null);
        $params['read_more'] = request('read_more', null);
        $params['image'] = request('image', null);
        $params['description'] = request('description', null);
        $params['sort'] = request('sort', null);
        // Bỏ đi phần http:port
        $params['before_photo'] = str_replace([$_SERVER['HTTP_ORIGIN'].'/'], [''], $params['before_photo']);
        $params['after_photo'] = str_replace([$_SERVER['HTTP_ORIGIN'].'/'], [''], $params['after_photo']);
        $params['image'] = str_replace([$_SERVER['HTTP_ORIGIN'].'/'], [''], $params['image']);
        // Tính toán nguồn video từ url của nó
        $params['video_src'] = getServiceVideoSrc($params['video_link']);
        //
        $result = $this->serviceRepo->update($params);

        if ($result) {
            $message = 'Cập nhật thành công';
            return redirect()->route('superadmin_listing_service')->with('success', $message);
        } else {
            $message = 'Cập nhật không thành công';
            return redirect()->route('superadmin_listing_service')->with('danger', $message);
        }
    }

    /**
     * Hàm quản lý tài nguyên hệ thống, chọn file, chọn ảnh bằng CKFinder
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingResoure(Request $request)
    {
        return view(getBladeFromPage('/superadmin/resoure/resoure-index'), []);
    }

    /**
     * Hàm hiển thị ds log login và logout -----------------------------------------------------------------------------
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingInOutLog(Request $request)
    {
        $data = [];
        return view(getBladeFromPage('/superadmin/log/log-inout-index'), $data);
    }

    public function listingAjaxInOutLog(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);

        $data['data'] = $this->authLogRepo->listing($params);
        $total = $this->authLogRepo->listing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];

        return view(getBladeFromPage('/superadmin/log/ajax-log-inout-index'), $data);
    }



}
