<?php

namespace App\Http\Controllers\Cms;

use App\Events\CountNoSeenEvent;
use App\Events\MessageEvent;
use App\Events\NotificationEvent;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreSaleOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Jobs\SendEmailJob;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Requirement;
use App\Repositories\Country\CountryRepo;
use App\Repositories\Customer\CustomerRepo;
use App\Repositories\Download\DownloadRepo;
use App\Repositories\kpi\KpiRepo;
use App\Repositories\Message\MessageRepo;
use App\Repositories\Notification\NotificationRepo;
use App\Repositories\Order\InputRepo;
use App\Repositories\Order\OrderRepo;
use App\Repositories\Order\OutputRepo;
use App\Repositories\Order\RequirementRepo;
use App\Repositories\Payment\PaymentDetailRepo;
use App\Repositories\Payment\PaymentRepo;
use App\Repositories\Paypal\PaypalRepo;
use App\Repositories\Report\ReportRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\Upload\UploadRepo;
use App\Repositories\User\UserRepo;
use App\Rules\PasswordRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SaleController extends Controller
{
    protected $customerRepo;
    protected $countryRepo;
    protected $messageRepo;
    protected $orderRepo;
    protected $paymentDetailRepo;
    protected $paypalRepo;
    protected $paymentRepo;
    protected $requirementRepo;
    protected $reportRepo;
    protected $serviceRepo;
    protected $userRepo;
    protected $outputRepo;
    protected $uploadRepo;
    protected $kpiRepo;
    protected $inputRepo;
    protected $downloadRepo;
    protected $notificationRepo;

    public function __construct(
        CustomerRepo $customerRepo,
        CountryRepo $countryRepo,
        MessageRepo $messageRepo,
        OrderRepo $orderRepo,
        PaymentDetailRepo $paymentDetailRepo,
        PaymentRepo $paymentRepo,
        PaypalRepo $paypalRepo,
        RequirementRepo $requirementRepo,
        ReportRepo $reportRepo,
        ServiceRepo $serviceRepo,
        UserRepo $userRepo,
        OutputRepo $outputRepo,
        UploadRepo $uploadRepo,
        KpiRepo $kpiRepo,
        InputRepo $inputRepo,
        DownloadRepo $downloadRepo,
        NotificationRepo $notificationRepo
    )
    {
        $this->middleware('auth');

        $this->customerRepo = $customerRepo;
        $this->countryRepo = $countryRepo;
        $this->messageRepo = $messageRepo;
        $this->paymentDetailRepo = $paymentDetailRepo;
        $this->paymentRepo = $paymentRepo;
        $this->paypalRepo = $paypalRepo;
        $this->orderRepo = $orderRepo;
        $this->requirementRepo = $requirementRepo;
        $this->reportRepo = $reportRepo;
        $this->serviceRepo = $serviceRepo;
        $this->userRepo = $userRepo;
        $this->outputRepo = $outputRepo;
        $this->uploadRepo = $uploadRepo;
        $this->kpiRepo = $kpiRepo;
        $this->inputRepo = $inputRepo;
        $this->downloadRepo = $downloadRepo;
        $this->notificationRepo = $notificationRepo;
    }

    /**
     * H??m show ds ????n h??ng
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function order(Request $request)
    {
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        $data['status'] = getOrderStatus(null, Constants::ACCOUNT_TYPE_SALE);
        return view(getBladeFromPage('/order/sale/order-index'), $data);
    }

    /**
     * H??m show form chi ti???t ????n h??ng
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        $id = request('id', null);
        $noti_id = request('noti_id', null);

        // Update ???? ?????c
        $this->notificationRepo->updateRead($noti_id);

        $data['services'] = $this->serviceRepo->listing([
            'status' => 1,
            'page_index' => 1,
            'page_size' => 20,
            'order_by' => [
                ['field' => 'name', 'direction' => 'ASC']
            ],
        ]);

        $data['user_id'] = Auth::id();
        $data['order'] = $this->orderRepo->findById(['id' => $id]);

        // L???y th??ng tin customer hi???n th??? chat
        if($data['order']->customer_id){
            $customer = $this->customerRepo->getDetail(['id' => $data['order']->customer_id]);
            $data['name_cus'] = $customer->fullname;
            $data['cus_avatar'] = $customer->avatar;
        } else {
            $data['name_cus'] = 'Customer';
            $data['cus_avatar'] = asset(Constants::DEFAULT_AVATAR);
        }

        // L???y th??ng tin sale hi???n th??? chat
        $data['name_sale'] = (Auth::user()->fullname) ? Auth::user()->fullname : 'Fotober';
        $data['sale_avatar'] = Auth::user()->avatar;

        // Check xem sale ??ang login c?? ???????c chat kh??ng: 1-c??, 0-kh??ng
        $data['is_chat'] = (Auth::id() == $data['order']->assigned_sale_id) ? true : false;

        //
        $data['messages'] = $this->messageRepo->getListAll(['order_id' => $id]);
        $data['file_messages'] = $this->messageRepo->getListAll(['order_id' => $id, 'order_by' => 'DESC']);

        return view(getBladeFromPage('/order/sale/order-detail'), $data);

    }

    /**
     * H??m show form t???o m???i ????n h??ng
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $data['services'] = $this->serviceRepo->listing([
            'status' => 1,
            'page_index' => 1,
            'page_size' => 20,
            'order_by' => [
                ['field' => 'name', 'direction' => 'ASC']
            ],
        ]);

        $data['customers'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER
        ]);
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        $data['account_type'] = Constants::ACCOUNT_TYPE_SALE;
        $data['is_admin'] = Auth::user()->is_admin;
        $data['turn_arround_times'] = getTurnArroundTime();

        return view(getBladeFromPage('/order/sale/order-create'), $data);
    }

    /**
     * H??m x??? l?? t???o ????n h??ng
     *
     * @param StoreSaleOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSaleOrderRequest $request)
    {
        $params['name'] = request('name', null);
        $params['service_id'] = request('service_id', null);
        $params['options'] = request('options', null);
        $params['email_receiver'] = request('email_receiver', null);
        $params['turn_arround_time'] = request('turn_arround_time', null);
        $params['notes'] = request('notes', null);
        $params['customer_id'] = request('customer_id', null);
        $params['assigned_sale_id'] = request('sale_id', null);
        //
        $params['created_type'] = Constants::ACCOUNT_TYPE_SALE;
        if(!Auth::user()->is_admin && Auth::user()->account_type == Constants::ACCOUNT_TYPE_SALE){
            $params['assigned_sale_id'] = Auth::id();
        }
        //
        $result = $this->orderRepo->store($params);
        $params['link'] = request('link', null);
        $params['upload_file'] = ($request->hasFile('upload_file')) ? $request->file('upload_file') : null;
        if ($result) {
            if(count($params['link']) > 0){
                foreach($params['link'] as $item){
                    if(!empty($item)){
                        $data = [
                            'order_id' => $result->id,
                            'customer_id' => $params['customer_id'],
                            'type' => $params['options'],
                            'link' => $item
                        ];
                        $res = $this->inputRepo->store($data);
                    }
                }
            }
            if($params['upload_file'] && count($params['upload_file']) > 0){
                $param_file['type'] = 'ORDER';
                $param_file['name_file'] = true;
                foreach($params['upload_file'] as $key =>$file){
                    $param_file['id'] = $key. '_'.$result->id;
                    $param_file['file'] = $file;
                    $path = $this->uploadRepo->updateFile($param_file);

                    $data = [
                        'order_id' => $result->id,
                        'customer_id' => Auth::id(),
                        'type' => $params['options'],
                        'file' => $path['path'],
                        'name' => $path['name_file']
                    ];
                    $res = $this->inputRepo->store($data);
                }
            }
            //?????y ghi ch?? v??o h???i tho???i chat
            if(!empty($result->notes)){
                $params_mess['message'] = $result->notes;
                $params_mess['order_id'] = $result->id;
                $params_mess['customer_id'] = $result->customer_id;
                $params_mess['type'] = 'TEXT';

                $result_mess = $this->messageRepo->store($params_mess);
            }
            $message = trans('fotober.order.mess_create_success');
        } else {
            $message = trans('fotober.order.mess_create_failed');
        }
        //
        return redirect()->route('sale_order')->with('message', $message);
    }

    /**
     * H??m show form c???p nh???t ????n h??ng
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

        return view(getBladeFromPage('/order/sale/order-edit'), $data);
    }

    /**
     * H??m c???p nh???t th??ng tin ????n h??ng
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
        return redirect()->route('sale_order')->with('message', $message);
    }

    /**
     * H??m th???c hi???n x??a ????n h??ng, k???t h???p check quy???n tr?????c khi x??a
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $id = request('id', null);
        $detail = $this->orderRepo->findById(['id' => $id]);
        if(Auth::user()->is_admin || $detail->assigned_sale_id == Auth::id() || $detail->created_by== Auth::id()){
            $result = $this->orderRepo->delete(['id' => $id]);
            if ($result) {
                $message = trans('fotober.order.mess_delete_success');
            } else {
                $message = trans('fotober.order.mess_delete_failed');
            }
        } else{
            $message = trans('fotober.order.mess_delete_failed');
        }

        return redirect()->route('sale_order')->with('message', $message);
    }

    /**
     * H??m l???y ds ????n h??ng theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderListingAjax(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['assigned_sale_id'] = request('assigned_sale_id', -1);
        $params['status'] = request('status', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['customer_id'] = request('customer_id', -1);
        $params['account_type'] = Constants::ACCOUNT_TYPE_SALE;
        $params['is_admin'] = Auth::user()->is_admin;
        $params['sale_id'] = Auth::id();
        //
        $data['data'] = $this->orderRepo->getListing($params);
        $total = $this->orderRepo->getListing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        //
        return view(getBladeFromPage('/order/sale/ajax-order-index'), $data);
    }

    /**
     * H??m l???y th??ng tin ????n h??ng theo ajax
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
        return view(getBladeFromPage('/order/sale/ajax-order-info'), $data);
    }

    /**
     *
     * @param Request $request
     * @return bool
     */
    public function updateAssignSale(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['sale_id'] = request('sale_id', null);
        $params['is_member'] = request('is_member', false);
        $params['response_type'] = request('response_type', 'JSON');

        $order = $this->orderRepo->findById(['id' => $params['order_id']]);
        if($order){
            if(!Auth::user()->is_admin){
                $params['sale_id'] = Auth::id();
            }
            if(empty($order->customer->manager_by) || empty($order->assigned_sale_id)){
                $orders = $this->orderRepo->OrderByCustomer(['customer_id' => $order->customer_id]);
                $arr_id = [];
                if(count($orders) > 0){
                    foreach($orders as $item){
                        $arr_id[] = $item->id;
                    }
                    $params['order_id'] = $arr_id;
                }
                $result = $this->orderRepo->updateAssignSale($params);
            } else{
                $result = $this->orderRepo->updateAssignSale($params);

                // Tr?????ng h???p c???p nh???t l???i
                // if ($params['response_type'] == 'JSON') {
                //     return response()->json([
                //         'code' => 400,
                //         'message' => 'Order n??y ???? ???????c Sale kh??c nh???n.',
                //         'data' => null
                //     ]);
                // }
                // return false;
            }
            if ($result) {
                // B???n noti cho admin
                event(new NotificationEvent([
                    'message_vi' => 'Sale admin v???a g???i ????n h??ng: ' . $order->name,
                    'order' => $order,
                    'order_id' => $order->id,
                    'total_no_seen_cus' => 0,
                    'total_no_seen_sale' => 0,
                    'account_type' => Constants::ACCOUNT_TYPE_SALE,
                    'sender_id' => Auth::id(),
                    'receiver_id' => $result->assigned_sale_id
                ]));

                // G???i mail cho Sale
                SendEmailJob::dispatch(Constants::EMAIL_ORDER_ASSIGN_SALE, [
                    'customer_id' => $order->assigned_sale_id,
                    'order' => $order
                ])->onQueue('email_sale');
                if(is_array($params['order_id']) && count($params['order_id']) > 0){
                    foreach($params['order_id'] as $order_id){
                        $order = $this->orderRepo->findById(['id' => $order_id]);
                        $mess = $this->messageRepo->getOrderById(['order_id' => $order_id]);
                        if($order->assigned_sale_id > 0 && !empty($order->notes) && count($mess) == 0){
                            
                            //?????y ghi ch?? v??o h???i tho???i chat
                            $params_mess['message'] = $order->notes;
                            $params_mess['order_id'] = $order_id;
                            $params_mess['customer_id'] = $order->customer_id;
                            $params_mess['type'] = 'TEXT';
                            $result_mess = $this->messageRepo->store($params_mess);
                        }

                        if($order->customer->manager_by == null){
                            $params_update['customer_id'] = $order->customer->id;
                            $params_update['sale_id'] = $order->assigned_sale_id;
                            $res = $this->customerRepo->updateAssignSale($params_update);
                        }
                    }
                } else {
                    $order = $this->orderRepo->findById(['id' => $params['order_id']]);
                    if($order->assigned_sale_id > 0 && !empty($order->notes)){
                        //?????y ghi ch?? v??o h???i tho???i chat
                        // $params_mess['message'] = $order->notes;
                        // $params_mess['order_id'] = $params['order_id'];
                        // $params_mess['customer_id'] = $order->customer_id;
                        // $params_mess['type'] = 'TEXT';
                        // $result_mess = $this->messageRepo->store($params_mess);

                    }

                    if($order->customer->manager_by == null){
                        $params_update['customer_id'] = $order->customer->id;
                        $params_update['sale_id'] = $params['sale_id'];
                        $res = $this->customerRepo->updateAssignSale($params_update);
                    }
                }

                // Tr?????ng h???p c???p nh???t th??nh c??ng
                if ($params['response_type'] == 'JSON') {
                    return response()->json([
                        'code' => 200,
                        'message' => 'OK',
                        'data' => $params['order_id']
                    ]);
                }
                return true;
            }
        }

        // Tr?????ng h???p c???p nh???t l???i
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
     * G???i tin nh???n
     *
     */
    public function sendMessage(Request $request)
    {
        $params['message'] = request('message', null);
        $params['order_id'] = request('order_id', null);

        $order = $this->orderRepo->findById(['id' => $params['order_id']]);

        // L???y tin cu???i c??ng ????? check c??ch h??n 1h th?? b???n noti
        $last_message = Message::where('order_id', $order->id)->orderBy('id', 'DESC')->first();

        $params['sale_id'] = $order->assigned_sale_id;
        $params['type'] = request('type', Constants::MESSAGE_TYPE_TEXT);
        $params['seen'] = 0;

        if(Auth::id() == $order->assigned_sale_id){
            $result = $this->messageRepo->updateSeen(['order_id' => $order->id, 'customer_id' => $order->customer_id, 'seen' => 1]);
            $result = $this->messageRepo->store($params);
        } else{
            $res = ['success' => false];
        }
        $total_no_seen_cus = $this->messageRepo->totalNoSeen(['order_id' => $order->id, 'customer_id' => $order->customer_id]); //S??? tin nh???n Sale ch??a xem
        $total_no_seen_sale = $this->messageRepo->totalNoSeen(['order_id' => $order->id, 'sale_id' => $order->assigned_sale_id]); //s??? tin nh???n KH ch??a xem
        $param_mess = [
            'mess_sale' => $params['message'],
            'mess_cus' => '',
            'type' => 'sale',
            'total_no_seen_cus' => count($total_no_seen_cus),
            'total_no_seen_sale' => count($total_no_seen_sale),
            'order_id' => $params['order_id'],
            'type_mess' => $params['type'],
            'file_name' => '',
            'created_at' => date('h:i a'),
            'sender_name' => (Auth::user()->fullname) ? Auth::user()->fullname : 'Fotober',
            'sender_avatar' => Auth::user()->avatar
        ];

        event(new MessageEvent($param_mess));

        if ($result) {
            $res = ['success' => true];
            // B???n noti cho sale ph??? tr??ch: check xem tin nh???n cu???i c??ch h??n 1h th?? b???n noti
            // if ($last_message && (strtotime($last_message->created_at) < time() - 3600)) {
            event(new NotificationEvent([
                'scope' => 'CHAT',
                'message_vi' => 'You had a message for your Order ID: ' . $order->code,
                'order' => $order,
                'total_no_seen_cus' => count($total_no_seen_cus),
                'total_no_seen_sale' => count($total_no_seen_sale),
                'order_id' => $order->id,
                'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                'sender_id' => Auth::id(),
                'receiver_id' => $order->customer_id
            ]));
            // }
        } else {
            $res = ['success' => false];
        }

        return $res;
    }

    /**
     * G???i tin nh???n
     *
     */
    public function sendMessageImage(Request $request)
    {
        $params['type'] = request('type', null);
        $params['message'] = null;
        $params['order_id'] = request('order_id', null);

        $order = $this->orderRepo->findById(['id' => $params['order_id']]);

        $params['sale_id'] = $order->assigned_sale_id;

        if(Auth::id() == $order->assigned_sale_id){
            $param_file['order_id'] = $params['order_id'];
            if($params['type'] == Constants::MESSAGE_TYPE_IMAGE){
                $param_file['type'] = 'media';
            } elseif($params['type'] == Constants::MESSAGE_TYPE_FILE) {
                $param_file['type'] = 'file';
            }
            $param_file['file'] = ($request->hasFile('file')) ? $request->file('file') : null;
            $path = $this->messageRepo->updateFile($param_file);

            if($path){
                $params['message'] = $path;
                $params['file_name'] = $param_file['file']->getClientOriginalName();
                $result = $this->messageRepo->store($params);
            }
        }
        $param_mess = [
            'mess_sale' => asset('storage/'.$path),
            'mess_cus' => '',
            'type' => 'sale',
            'order_id' => $params['order_id'],
            'type_mess' => $params['type'],
            'file_name' => $params['file_name'],
            'created_at' => date('h:i a'),
            'sender_name' => (Auth::user()->fullname) ? Auth::user()->fullname : 'Fotober',
            'sender_avatar' => Auth::user()->avatar
        ];
        event(new MessageEvent($param_mess));
        if ($result) {
            $res = ['success' => true];
        } else {
            $res = ['success' => false];
        }

        return $res;
    }

    public function orderInitPayment(Request $request)
    {
        $id = request('id', null);
        if ($id) {
            $order = $this->orderRepo->findById(['id' => $id]);
            if ($order) {
                $params['order_id'] = $id;
                $params['customer_id'] = $order->customer_id;
                $params['amount'] = 0;
                $params['email_paypal'] = $order->customer->email_paypal;
                $params['method'] = 'PAYPAL';
                $params['status'] = Constants::PAYMENT_STATUS_NEW;
                $params['created_by'] = Auth::id();
                $params['paypal_id'] = null;
                $params['link_payment'] = null;
                //
                $result = $this->paymentRepo->updateOrCreate($params);
                if ($result) {
                    $message = 'Kh???i t???o thanh to??n th??nh c??ng';
                    return redirect()->route('sale_order')->with('success', $message);
                }
            }
        }

        $message = 'Kh???i t???o thanh to??n kh??ng th??nh c??ng';
        return redirect()->route('sale_order')->with('danger', $message);
    }

    /**
     * H??m show ds thanh to??n ------------------------------------------------------------------------------------------
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function payment(Request $request)
    {
        $data['status'] = getPaymentStatus();
        return view(getBladeFromPage('/payment/sale/payment-index'), $data);
    }

    /**
     * H??m l???y ds thanh to??n theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function paymentListingAjax(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['status'] = request('status', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['account_type'] = Constants::ACCOUNT_TYPE_CUSTOMER;
        //
        $data['data'] = $this->paymentRepo->getListing($params);
        $total = $this->paymentRepo->getListing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        return view(getBladeFromPage('/payment/sale/ajax-payment-index'), $data);
    }

    /**
     * H??m l???y th??ng tin thanh to??n theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function paymentInfoAjax(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $data['payment'] = $this->paymentRepo->findById(['id' => $id]);
        return view(getBladeFromPage('/payment/sale/ajax-payment-info'), $data);
    }

    /**
     * H??m ?????y th??ng tin, y??u c???u KH thanh to??n sang Paypal
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAndSendInvoiceToPaypal(Request $request)
    {
        $id = request('id', null);
        $data['payment'] = $this->paymentRepo->findById(['id' => $id]);
        $params['order_id'] = $data['payment']->order_id;
        $params['customer_id'] = $data['payment']->customer_id;
        $params['amount'] = $data['payment']->amount;
        if(isset($data['payment']['details']) && count($data['payment']['details']) > 0){

            $api = $this->paypalRepo->createdInvoice($data['payment']);
            if($api['created_invoice'] == true){
                $params['status'] = Constants::PAYMENT_STATUS_PENDING;
                $params['paypal_id'] = $api['id_invoice'];
            }

            if($api['send_invoice'] == true){
                $item = json_decode($api['link_paypal']);
                $params['link_payment'] = $item->href;
            }

            $result = $this->paymentRepo->updateOrCreate($params);
            if ($result) {
                $res = $this->orderRepo->updateStatus(['id' => $params['order_id'], 'status' => Constants::ORDER_STATUS_AWAITING_PAYMENT]);
                $message = trans('fotober.payment.invoice_success');
                $key = 'success';
                // B???n noti cho KH th??ng b??o ???? t???o ????n h??ng tr??n Paypal
                event(new NotificationEvent([
                    'message_vi' => 'The system has sent a payment request. Please, check your Paypal account',
                    'order' => $result,
                    'order_id' => $result->id,
                    'total_no_seen_cus' => 0,
                    'total_no_seen_sale' => 0,
                    'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                    'sender_id' => Auth::id(),
                    'receiver_id' => $result->customer_id
                ]));

                // G???i mail cho KH
                SendEmailJob::dispatch(Constants::EMAIL_ORDER_AWAIT_PAYMENT, [
                    'customer_id' => $result->customer_id,
                    'order' => $res
                ])->onQueue('email_customer');
            }
        } else{
            $message = trans('fotober.payment.invoice_failed');
            $key = 'danger';
        }
        return redirect()->route('sale_order')->with($key, $message);
    }

    /**
     * H??m hi???n th??? chi ti???t thanh to??n g???m c??c m???c n??o
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showInvoiceDetail(Request $request)
    {
        $order_id = request('order_id', null);
        $data['payment'] = $this->paymentRepo->findByOrderId(['order_id' => $order_id]);
        //
        return view(Constants::VIEW_PAGE_PATH . '/order/sale/ajax-order-invoice-detail', $data);
    }

    /**
     * H??m th??m c??c m???c thanh to??n chi ti???t
     *
     * @param Request $request
     * @return bool
     */
    public function updatePaymentDetail(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['payment_id'] = request('payment_id', null);
        $params['description'] = request('description', null);
        $params['quantity'] = request('quantity', null);
        $params['price'] = request('price', null);
        $params['amount'] = request('amount', null);
        $params['response_type'] = request('response_type', 'JSON');
        //
        $result = $this->paymentDetailRepo->store($params);
        if ($result) {
            // Tr?????ng h???p c???p nh???t th??nh c??ng
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $params
                ]);
            }
            return true;
        }

        // Tr?????ng h???p c???p nh???t l???i
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
     * H??m x??a item thanh to??n
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function deletePaymentDetail()
    {
        $params['order_id'] = request('order_id', null);
        $params['id'] = request('payment_detail_id', null);
        $params['response_type'] = request('response_type', 'JSON');
        //
        $result = $this->paymentDetailRepo->delete($params);
        if ($result) {
            // Tr?????ng h???p c???p nh???t th??nh c??ng
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $params
                ]);
            }
            return true;
        }

        // Tr?????ng h???p c???p nh???t l???i
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
     * H??m l???y ds y??u c???u
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
        return view(Constants::VIEW_PAGE_PATH . '/order/sale/ajax-order-requirement-index', $data);
    }

    /**
     * H??m th??m m???i m???t y??u c???u c???a ????n h??ng
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
            // Tr?????ng h???p c???p nh???t th??nh c??ng
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $params
                ]);
            }
            return true;
        }

        // Tr?????ng h???p c???p nh???t l???i
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
     * H??m x??a item requirement
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
            // Tr?????ng h???p c???p nh???t th??nh c??ng
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => $params
                ]);
            }
            return true;
        }

        // Tr?????ng h???p c???p nh???t l???i
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
     * Danh s??ch KH quy???n Sale
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingCustomer()
    {
        $data = [];
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        $data['status'] = getOrderStatus(null, Constants::ACCOUNT_TYPE_SALE);
        return view(Constants::VIEW_PAGE_PATH . '/customer/sale/customer-index', $data);
    }

    /**
     * L???y ds Sale ????? ph??n c??ng CSKH n??o
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function saleListCustomerAjax()
    {
        $params['keyword'] = request('keyword', null);
        $params['manager_by'] = request('manager_by', -1);
        $params['status'] = request('status', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['account_type'] = Constants::ACCOUNT_TYPE_SALE;
        $params['is_admin'] = Auth::user()->is_admin;
        $params['manager_by'] = request('manager_by', null);

        //
        $data['data'] = $this->customerRepo->getListing($params);
        $total = $this->customerRepo->getListing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);

        //
        return view(getBladeFromPage('/customer/sale/ajax-customer-index'), $data);
    }

    /**
     * Hi???n th??? th??ng tin KH
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function saleInfoCustomerAjax(Request $request)
    {
        $id = request('customer_id', null);
        $data['customer'] = $this->customerRepo->getDetail(['id' => $id]);
        return view(getBladeFromPage('/customer/sale/ajax-customer-info'), $data);
    }

    /**
     * M??n h??nh th??m m???i KH
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function createCustomer()
    {
        $data['countries'] = $this->countryRepo->getListing([]);
        return view(getBladeFromPage('/customer/sale/customer-create'), $data);
    }

    /**
     * X??? l?? th??m m???i KH
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCustomer(Request $request)
    {
        $this->validate(
            $request,
            [
                'fullname' => 'required|string|max:50',
                'country_code' => 'required|string|max:5',
                'website' => 'nullable|url|max:255',
                'phone' => 'nullable|min:9|max:15',
                'email' => 'required|string|email|max:191|unique:users',
                'email_paypal' => 'nullable|string|email|max:191',
                'password' => [
                    'nullable',
                    'min:8',
                    'max:32',
                    new PasswordRule(),
                    'confirmed'
                ],
                'password_confirmation' => 'nullable|min:8',
            ],
            [],
            [
                'fullname' => trans('fotober.customer.fullname'),
                'email' => trans('fotober.register.email'),
                'email_paypal' => trans('fotober.register.email'),
                'password' => trans('fotober.register.password'),
                'password_confirmation' => trans('fotober.register.password_confirmation'),
            ]
        );
        // T???o th??ng tin user
        $result = $this->customerRepo->store($request->all());

        if ($result) {
            $message = trans('fotober.customer.created_account_success');
            return redirect()->route('sale_customer_listing')->with('success', $message);
        } else {
            $message = trans('fotober.customer.created_account_faild');
            return redirect()->route('sale_customer_create')->with('danger', $message);
        }
    }

    /**
     * M??n h??nh c???p nh???t th??ng tin KH
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function editCustomer(Request$request)
    {
        $id = request('id', null);
        $data['customer'] = $this->customerRepo->getDetail(['id' => $id]);
        $data['countries'] = $this->countryRepo->getListing([]);
        return view(getBladeFromPage('/customer/sale/customer-edit'), $data);

    }

    /**
     * X??? l?? c???p nh???t th??ng tin KH
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCustomer()
    {
        $params['id'] = request('id', null);
        $params['fullname'] = request('fullname', null);
        $params['country_code'] = request('country_code', 'FTB');
        $params['phone'] = request('phone', null);
        $params['email'] = request('email', null);
        $params['email_paypal'] = request('email_paypal', null);
        $params['password'] = request('password', null);
        $params['website'] = request('website', null);
        $params['address'] = request('address', null);
        $params['notes'] = request('notes', null);
        $params['birthday'] = request('birthday', null);
        $is_admin = Auth::user()->is_admin;

        $customer = $this->customerRepo->getDetail(['id' => $params['id']]);
        if(!$is_admin && $customer->manager_by != Auth::id()){
            $message = trans('fotober.customer.access_deny');
            return redirect()->route('sale_customer_listing')->with('message', $message);
        }
        //
        if ($params['birthday']) {
            $params['birthday'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $params['birthday'])));
        }
        //
        if ($params['password']) {
            $params['password'] = Hash::make($params['password']);
        }

        $result = $this->customerRepo->updateInfo($params);
        if ($result) {
            $message = trans('fotober.customer.mess_update_success');
        } else {
            $message = trans('fotober.customer.mess_update_failed');
        }
        //
        return redirect()->route('sale_customer_listing')->with('message', $message);
    }

    /**
     * X??? l?? x??a th??ng tin KH
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCustomer()
    {
        $params['id'] = request('id', null);
        $is_admin = Auth::user()->is_admin;

        $customer = $this->customerRepo->getDetail(['id' => $params['id']]);
        if(!$is_admin && $customer->manager_by != Auth::id()){
            $message = trans('fotober.customer.access_deny');
            return redirect()->route('sale_customer_listing')->with('message', $message);
        }
        $params['response_type'] = request('response_type', 'JSON');
        //
        $result = $this->customerRepo->delete($params);
        if ($result) {
            $message = trans('fotober.customer.mess_delete_success');
        } else {
            $message = trans('fotober.customer.mess_delete_failed');
        }

        return redirect()->route('sale_customer_listing')->with('message', $message);
    }

    /**
     * H??m g??n quy???n qu???n l?? customer
     *
     */
    public function updateAssignSaleCustomer(Request $request)
    {
        $params['customer_id'] = request('customer_id', null);
        $params['sale_id'] = request('sale_id', null);
        $params['response_type'] = request('response_type', 'JSON');

        $result = $this->customerRepo->updateAssignSale($params);
        if ($result) {
            $order = $this->orderRepo->findByCustomerId(['customer_id' => $params['customer_id'], 'order_by' =>  'DESC']);
            event(new NotificationEvent([
                'message_vi' => trans('fotober.order.customer_sends_order_request').' '  . $order->name,
                'order' => $order,
                'order_id' => $order->id,
                'total_no_seen_cus' => 0,
                'total_no_seen_sale' => 0,
                'account_type' => Constants::ACCOUNT_TYPE_SALE,
                'sender_id' => $params['customer_id'],
                'receiver_id' => $params['sale_id']
            ]));
            // Tr?????ng h???p c???p nh???t th??nh c??ng
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    'data' => null
                ]);
            }
            return true;
        }

        // Tr?????ng h???p c???p nh???t l???i
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
     * H??m x??a item output
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function deleteOutput()
    {
        $params['id'] = request('id', null);
        $params['order_id'] = request('order_id', null);
        $params['customer_id'] = request('customer_id', null);
        $params['response_type'] = request('response_type', 'JSON');
        // $getOutput = $this->outputRepo->findById($params['id']);
        // if(isset($getOutput) && $getOutput->type == 'UPLOAD'){
        //     Storage::delete($getOutput->file);
        // }
        //
        $result = $this->outputRepo->delete($params);
        if ($result) {
            // Tr?????ng h???p c???p nh???t th??nh c??ng
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'X??a Output th??nh c??ng.',
                    'data' => $params
                ]);
            }
            return true;
        }else{
            return response()->json([
                'code' => 400,
                'message' => 'X??a Output th???t b???i. Vui l??ng th??? l???i',
                'data' => null
            ]);
        }
        return false;
    }

    /**
     * H??m l???y ds output
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingOutput(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['customer_id'] = request('customer_id', null);
        //
        $data['outputs'] = $this->outputRepo->getListing($params);
        $data['order_id'] = $params['order_id'];
        $data['customer_id'] = $params['customer_id'];
        //
        return view(Constants::VIEW_PAGE_PATH . '/order/sale/ajax-order-output-index', $data);
    }

    /**
     * H??m th??m m???i m???t output
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function storeOutput(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['customer_id'] = request('customer_id', null);
        $params['link'] = request('link', null);
        $params['file'] = request('file', null);
        $params['type'] = request('type', null);
        $params['response_type'] = request('response_type', 'JSON');
        //
        $param_file['type'] = 'OUTPUT';
        if($params['type'] == 'IMAGE'){
            $param_file['file'] = ($request->hasFile('avatar')) ? $request->file('avatar') : null;
            
            if($param_file['file'] && count($param_file['file']) > 0){
                foreach($param_file['file'] as $key => $file){
                    $param_file['id'] = $params['order_id'].'_'.$key;
                    $param_file['file'] = $file;
                    $path = $this->uploadRepo->updateFile($param_file);
                    if($path){
                        $params['file'] = $path;
                        $params['link'] = null;
                        $result = $this->outputRepo->store($params);
                    }
                }
            }
        } else {
            $params['file'] = null;
            $result = $this->outputRepo->store($params);
        }
        $params_order['id'] = $params['order_id'];
        $params_order['status'] = Constants::ORDER_STATUS_COMPLETED;
        $result = $this->orderRepo->update($params_order);

        $order = $this->orderRepo->findById(['id' => $params['order_id']]);
        
        // G???i mail cho KH
        SendEmailJob::dispatch(Constants::EMAIL_ORDER_COMPLETED, [
            'customer_id' => $order->customer_id,
            'order' => $order
        ])->onQueue('email_customer');

        // event(new NotificationEvent([
        //     'message_vi' => 'Your order ('.$order->code.') has been completed. Please kindly check the output.',
        //     'order' => $order,
        //     'order_id' => $order->id,
        //     'total_no_seen_cus' => 0,
        //     'total_no_seen_sale' => 0,
        //     'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
        //     'sender_id' => Auth::id(),
        //     'receiver_id' => $order->customer_id
        // ]));
        // Tr?????ng h???p c???p nh???t th??nh c??ng
        if ($params['response_type'] == 'JSON') {
            return response()->json([
                'code' => 200,
                'message' => 'Th??m m???i output th??nh c??ng',
                'data' => $params,
                'order' => $order
            ]);
        }
        return true;

        // Tr?????ng h???p c???p nh???t l???i
        // if ($params['response_type'] == 'JSON') {
        //     return response()->json([
        //         'code' => 400,
        //         'message' => 'Not OK',
        //         'data' => null
        //     ]);
        // }
        // return false;
    }

    /**
     * H??m th??m m???i m???t output
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function updateOutput(Request $request)
    {
        $params['file'] = request('file', NULL);
        $params['id'] = request('output_id', NULL);
        $params['link'] = request('link', NULL);
        $params['type'] = request('type', NULL);
        $params['type_update'] = request('type_update', 'update');
        $params['response_type'] = request('response_type', 'JSON');
        if($params['id']){
            //
            $output = $this->outputRepo->findById(['id' => $params['id']]);
            $params['order_id'] = $output->order_id;
            $params['customer_id'] = $output->customer_id;
            $params['request_revision'] = $output->request_revision;
            //X??a file c??
            $param_file['id'] = $params['order_id'];
            $param_file['type'] = 'OUTPUT';
            if(!empty($params['link']) && $params['link'] != NULL){ //Ch???nh s???a link c???a video
                $va = 'video'.$params['link'];
                $params['fix_request'] = 2; //???? ch???nh s???a theo y??u c???u
                $result = $this->outputRepo->update($params);
            } else {
                $params['link'] = $output->link;
                $va = 'image';
                $param_file['file'] = ($request->hasFile('file_ouput')) ? $request->file('file_ouput') : null;
                if($param_file['file']){
                    $path = $this->uploadRepo->updateFile($param_file);
                    if($path){
                        $params['file'] = $path;
                        $params['fix_request'] = 2; //???? ch???nh s???a theo y??u c???u
                        $result = $this->outputRepo->update($params);
                    }
                }
            }
            $params_order['id'] = $params['order_id'];
            $params_order['status'] = Constants::ORDER_STATUS_COMPLETED;
            $result = $this->orderRepo->update($params_order);
            $order = $this->orderRepo->findById(['id' => $output->order_id]);
            // G???i mail cho KH
            SendEmailJob::dispatch(Constants::EMAIL_ORDER_COMPLETED, [
                'customer_id' => $order->customer_id,
                'order' => $order
            ])->onQueue('email_customer');
            event(new NotificationEvent([
                'message_vi' => 'Your order ('.$order->code.') has been updated. Please kindly check the output.',
                'order' => $order,
                'order_id' => $order->id,
                'total_no_seen_cus' => 0,
                'total_no_seen_sale' => 0,
                'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                'sender_id' => Auth::id(),
                'receiver_id' => $order->customer_id
            ]));

            if( $params['type_update'] != 'update'){
                // G???i mail cho KH
                SendEmailJob::dispatch(Constants::ORDER_UPDATE_REVISION, [
                    'customer_id' => $order->customer_id,
                    'order' => $order
                ])->onQueue('email_customer');
            }
            // Tr?????ng h???p c???p nh???t th??nh c??ng
            if ($params['response_type'] == 'JSON') {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK'. $va,
                    'data' => $params
                ]);
            }
        } else{

            return response()->json([
                'code' => 400,
                'message' => 'Kh??ng t??m th???y output v???a ch???n.',
                'data' => null
            ]);
        }
        return false;

        // Tr?????ng h???p c???p nh???t l???i
        // if ($params['response_type'] == 'JSON') {
        //     return response()->json([
        //         'code' => 400,
        //         'message' => 'Not OK',
        //         'data' => null
        //     ]);
        // }
        // return false;
    }

    /**
     * H??m x??? l?? chuy???n ti???p t??? Sale sang Admin
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forwardSaleToAdmin(Request $request)
    {
        $params['id'] = request('id', null);
        $admin = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_ADMIN,
            'get_object' => true,
        ]);

        $params['assigned_admin_id'] = ($admin && isset($admin->id) && $admin->id > 0) ? $admin->id : null;
        $result = $this->orderRepo->forwardSaleToAdmin($params);

        if ($result) {
            // B???n noti cho admin ph??? tr??ch
            event(new NotificationEvent([
                'message_vi' => 'C?? y??u c???u ????n h??ng c???n x??? l??: ' . $result->name,
                'order' => $result,
                'order_id' => $result->id,
                'total_no_seen_cus' => 0,
                'total_no_seen_sale' => 0,
                'account_type' => Constants::ACCOUNT_TYPE_ADMIN,
                'sender_id' => Auth::id(),
                'receiver_id' => $result->assigned_admin_id
            ]));
            //
            $message = 'Chuy???n ti???p th??nh c??ng';
            return redirect()->route('sale_order')->with('success', $message);
        }

        $message = 'Chuy???n ti???p kh??ng th??nh c??ng';
        return redirect()->route('sale_order')->with('error', $message);
    }

    /**
     * Show danh s??ch KPI
     *
     */
    public function kpi(Request $request)
    {
        if(Auth::user()->is_admin){
            $data['sales'] = $this->userRepo->getListingByAccountType([
                'account_type' => Constants::ACCOUNT_TYPE_SALE
            ]);
            $data['customers'] = $this->userRepo->getListingByAccountType([
                'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER
            ]);
        } else{
            $data['customers'] = $this->userRepo->getListingByAccountType([
                'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                'sale_id' => Auth::id()
            ]);
        }
        $data['services'] = $this->serviceRepo->getAll([]);
        $data['status'] = getOrderStatus(null, Constants::ACCOUNT_TYPE_SALE);
        return view(getBladeFromPage('/kpi/kpi-index'), $data);
    }

    /**
     * Show danh s??ch KPI g???i ajax
     *
     */
    public function saleListKpiAjax(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['sale_id'] = request('sale_id', -1);
        $params['customer_id'] = request('customer_id', -1);
        $params['service_id'] = request('service_id', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['is_admin'] = Auth::user()->is_admin;
        if($params['is_admin']){
            $params['sale_id'] = request('sale_id', -1);
        } else{
            $params['sale_id'] = Auth::id();
        }
        //
        $data['data'] = $this->kpiRepo->getListing($params);
        $total = $this->kpiRepo->getListing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        return view(getBladeFromPage('/kpi/ajax-kpi-index'), $data);
    }

    /**
     * Thay ?????i tr???ng th??i c???a ????n h??ng
     *
     */
    public function changeStatus(Request $request)
    {
        $id = request('order_id', null);
        $status = request('status', null);
        $result = $this->orderRepo->updateStatus(['id' => $id, 'status' => $status]);
        if($result){
            switch ($status) {
                case Constants::ORDER_STATUS_DELIVERING:
                    // B???n noti cho customer
                    event(new NotificationEvent([
                        'message_vi' => 'Your order has been processed (delivering): ' . $result->name . '. Please, check the result',
                        'order' => $result,
                        'order_id' => $result->id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $result->customer_id
                    ]));

                    // G???i mail cho KH
                    SendEmailJob::dispatch(Constants::EMAIL_ORDER_DELIVERY, [
                        'customer_id' => $result->customer_id,
                        'order' => $result
                    ])->onQueue('email_customer');

                    // T???o tin nh???n g???i cho KH v??o ph???n chat
                    // Message c??: We sent order. Please, check and feedback (if any) in chat message
                    $this->messageRepo->store([
                        'message' => 'We would like to inform you that your order has been Completed. Please kindly check the project for the outputs. If you have any additional requirements or feedback, just feel free to let us know.',
                        'order_id' => $id,
                        'sale_id' => Auth::id(),
                        'type' => 'TEXT'
                    ]);

                    break;
                case Constants::ORDER_STATUS_COMPLETED:
                    // B???n noti cho qaqc ph??? tr??ch
                    event(new NotificationEvent([
                        'message_vi' => 'Your order has been processed (completed): ' . $result->name,
                        'order' => $result,
                        'order_id' => $result->id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $result->customer_id
                    ]));

                    // G???i mail cho KH
                    SendEmailJob::dispatch(Constants::EMAIL_ORDER_COMPLETED, [
                        'customer_id' => $result->customer_id,
                        'order' => $result
                    ])->onQueue('email_customer');

                    break;
                case Constants::ORDER_STATUS_REDO:
                    // B???n noti cho customer
                    event(new NotificationEvent([
                        'message_vi' => 'Your order is being reprocessed as requested (re-do): ' . $result->name,
                        'order' => $result,
                        'order_id' => $result->id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $result->customer_id
                    ]));

                    // B???n noti cho QA/QC
                    event(new NotificationEvent([
                        'message_vi' => 'V???a c?? ????n h??ng y??u c???u x??? l?? l???i (re-do): ' . $result->name,
                        'order' => $result,
                        'order_id' => $result->id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_QAQC,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $result->assigned_qaqc_id
                    ]));

                    // L??u message nh???n cho KH
                    $result_mess = $this->messageRepo->store([
                        'message' => 'Your order has been requested to be revised (re-do)',
                        'order_id' => $id,
                        'sale_id' => Auth::id(),
                        'seen' => 0,
                        'type' => 'TEXT'
                    ]);

                    break;
                case Constants::ORDER_STATUS_PAID:
                    event(new NotificationEvent([
                        'message_vi' => 'Your order has completed payment (paid): ' . $result->name,
                        'order' => $result,
                        'order_id' => $result->id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $result->customer_id
                    ]));
                    break;
            }

            return response()->json([
                'code' => 200,
                'message' => 'Th??nh c??ng',
                'data' => null
            ]);

        } else {
            return response()->json([
            'code' => 400,
            'message' => 'Th???t b???i',
            'data' => null
        ]);}
    }

    /**
     * H??m l???y th??ng tin input theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderInputAjax(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $data['inputs'] = $this->inputRepo->getListing(['order_id' => $id, 'customer_id' => $user_id]);
        return view(getBladeFromPage('/order/sale/ajax-order-input-index'), $data);
    }

    /**
     * H??m l???y th??ng tin output theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderOutputAjax(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $data['order'] = $this->orderRepo->findById(['id' => $id]);
        $data['outputs'] = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => $data['order']->customer_id]);
        return view(getBladeFromPage('/order/sale/ajax-order-output-list'), $data);
    }

    /**
     * Danh s??ch b??o c??o th???ng k?? --------------------------------------------------------------------------------------
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingReport(Request $request)
    {
        $data = [];
        $data['sales'] = $this->userRepo->getListingByAccountType([
            'account_type' => Constants::ACCOUNT_TYPE_SALE
        ]);
        return view(Constants::VIEW_PAGE_PATH . '/report/sale/report-index', $data);
    }

    public function listingReportAjax(Request $request)
    {
        $params['user_id'] = request('user_id', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        //
        $params['is_admin'] = Auth::user()->is_admin;
        //
        $data['data'] = $this->reportRepo->listingOrder($params);
        $total = $this->reportRepo->listingOrder($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        return view(getBladeFromPage('/report/sale/ajax-report-index'), $data);
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

    /**
     * H??m l???y th??ng tin chat theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderChatAjax(Request $request)
    {
        $id = request('order_id', null);
        $noti_id = request('noti_id', null);

        // Update ???? ?????c
        $this->notificationRepo->updateRead($noti_id);

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

        //L???y th??ng tin nh??n vi??n
        if($data['order']->assigned_sale_id){
            $sale = $this->customerRepo->getDetail(['id' => $data['order']->assigned_sale_id]);
            $data['name_sale'] = $sale->fullname;
            $data['sale_avatar'] = $sale->avatar;
        } else {
            $data['name_sale'] = 'Fotober';
            $data['sale_avatar'] = asset(Constants::DEFAULT_AVATAR);
        }

        //L???y th??ng tin customer
        $data['name_cus'] = (Auth::user()->fullname) ? Auth::user()->fullname : 'Me';
        $data['cus_avatar'] = Auth::user()->avatar;

        $result = $this->messageRepo->updateSeen(['order_id' =>  $data['order']->id, 'customer_id' =>  $data['order']->customer_id, 'seen' => 1]);
        $data['messages'] = $this->messageRepo->getListAll(['order_id' => $id]);
        $data['file_messages'] = $this->messageRepo->getListAll(['order_id' => $id, 'order_by' => 'DESC']);

        return view(getBladeFromPage('/order/sale/ajax-chat-sale'), $data);
    }


    /**
     * H??m show Form nh???n ????nh gi??
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function readySummary(Request $request)
    {
        $id = request('id', null);
        $noti_id = request('noti_id', null);

        // Update ???? ?????c
        $this->notificationRepo->updateRead($noti_id);

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
        $data['payment_detail'] = $this->paymentDetailRepo->findByOrderId(['order_id' => $id]);
        if(count($data['payment_detail']) > 0 && !empty($data['order']->payment->paypal_id)){
            $pay_status = $this->paypalRepo->checkStatus($data['order']->payment->paypal_id);
            if($pay_status){
                if($data['order']->status != Constants::ORDER_STATUS_PAID && $pay_status == 'PAID'){
                    $result = $this->orderRepo->updateStatus(['id' => $id, 'status' => Constants::ORDER_STATUS_PAID]);
                    $result = $this->paymentRepo->updateStatus(['id' => $data['order']->payment->id, 'status' => Constants::PAYMENT_STATUS_SUCCESS]);
                }
                if($data['order']->status != Constants::ORDER_STATUS_PAID && $pay_status == 'CANCELLED'){
                    // print_r($pay_status);
                    // die();
                    $result = $this->paymentRepo->updateStatus(['id' => $data['order']->payment->id, 'status' => Constants::PAYMENT_STATUS_FALIED]);
                }
                $data['order'] = $this->orderRepo->findById(['id' => $id]);
            }
        }

        $data['outputs'] = $outputs = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => $data['order']->customer_id]);
        $data['output_image'] = 0;
        $data['output_video'] = 0;
        foreach($outputs as $item){
            if($item->type == 'VIDEO'){
                $data['output_video'] = $data['output_video'] + 1;
            }
            if($item->type == 'IMAGE'){
                $data['output_image'] = $data['output_image'] + 1;
            }
        }
        $data['customer'] = $this->customerRepo->getDetail(['id' => $data['order']->customer_id]);

        return view(getBladeFromPage('/order/sale/order-sumary'), $data);
    }

    /**
     * H??m l???y ds output
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function listingOutputSumary(Request $request)
    {
        $params['order_id'] = request('order_id', null);
        $params['customer_id'] = request('customer_id', null);
        // $params['page_size'] = 100;
        
        //
        $data['outputs'] = $this->outputRepo->getAll($params);
        $data['order_id'] = $params['order_id'];
        $data['customer_id'] = $params['customer_id'];
        $data['output_image'] = 0;
        $data['output_video'] = 0;
        foreach($data['outputs'] as $item){
            if($item->type == 'VIDEO'){
                $data['output_video'] = $data['output_video'] + 1;
            }
            if($item->type == 'IMAGE'){
                $data['output_image'] = $data['output_image'] + 1;
            }
        }
        //
        return view(Constants::VIEW_PAGE_PATH . '/order/sale/ajax-order-output-sumary', $data);
    }


    /**
     * H?? x??? l?? t???o th??ng tin thanh to??n, chi ti???t thanh to??n, t???o h??a ????n paypal
     *
     */
    public function CreateInvoicePaypal(Request $request)
    {
        $order_id = request('order_id', null);
        $dvi = request('dvi', null);
        $discount = request('discount', 0);
        $details = request('details', null);
        $note_sale = request('note_sale', null);
        $details = (array)$details;
        if ($order_id) {
            $order = $this->orderRepo->findById(['id' => $order_id]);
            $email_paypal = request('email_paypal', $order->customer->email_paypal);
            $check_pay = $this->paymentRepo->findByOrderId(['order_id' => $order_id]);
            if ($order && !$check_pay) {
                //C???p nh???t discount order
                $quantity = 0;
                $cost = 0;
                $total_payment = 0;
                if(count($details) > 0){
                    foreach($details as $item){
                        $quantity = $quantity + $item['quantity'];
                        $cost = $cost + $item['quantity']*$item['price'];
                    }
                    if($dvi == 'pre'){
                        if(floatval($discount) > 100){
                            $discount = 0;
                        }
                        $total_payment = $cost - $cost*floatval($discount)/100;
                    } else{
                        if(floatval($discount) > $cost){
                            $discount = 0;
                        }
                        $total_payment = $cost - floatval($discount);
                    }
                } else{
                    $discount = 0;
                }
                $order->quantity = $quantity;
                $order->cost = $cost;
                $order->total_payment = $total_payment;
                $order->discount = ($dvi == 'pre') ? floatval($discount) : 0;
                $order->discount_money = ($dvi == 'money') ? floatval($discount) : 0;
                $this->orderRepo->update($order);

                //T???o Payment
                $params['order_id'] = $order_id;
                $params['customer_id'] = $order->customer_id;
                $params['amount'] = 0;
                $params['note_sale'] = $note_sale;
                $params['email_paypal'] = $email_paypal;
                $params['method'] = 'PAYPAL';
                $params['status'] = Constants::PAYMENT_STATUS_NEW;
                $params['paypal_id'] = null;
                $params['link_payment'] = null;
                $params['created_by'] = Auth::id();
                //
                $result = $this->paymentRepo->updateOrCreate($params);
                if ($result) {
                    //T???o payment detail
                    $result = $this->paymentRepo->findByOrderId(['order_id' => $order_id]);
                    $payment_id = $result->id;
                    // die();
                    if(count($details) > 0){
                        foreach($details as $item){
                            $params['order_id'] = $order_id;
                            $params['payment_id'] = $payment_id ;
                            $params['description'] = $item["description"];
                            $params['quantity'] = intval($item["quantity"]);
                            $params['order_name'] = $item["item_name"];
                            $params['price'] = floatval($item["price"]);
                            $params['amount'] = intval($item["quantity"])*floatval($item["price"]);
                            //
                            $result_detail = $this->paymentDetailRepo->store($params);
                        }
                    }

                    //T???o invoice
                    $data['payment'] = $this->paymentRepo->findById(['id' => $payment_id]);
                    $params['order_id'] = $data['payment']->order_id;
                    $params['customer_id'] = $data['payment']->customer_id;
                    $params['amount'] = $data['payment']->amount;
                    if(isset($data['payment']['details']) && count($data['payment']['details']) > 0){

                        $api = $this->paypalRepo->createdInvoice($data['payment']);
                        if($api['created_invoice'] == true){
                            $params['status'] = Constants::PAYMENT_STATUS_PENDING;
                            $params['paypal_id'] = $api['id_invoice'];
                        }

                        if($api['send_invoice'] == true){
                            // $item = json_decode($api['link_paypal']);
                            $params['link_payment'] = $api['link_paypal'];
                        }

                        $result = $this->paymentRepo->updateOrCreate($params);
                        if ($result) {

                            $res = $this->orderRepo->updateStatus(['id' => $params['order_id'], 'status' => Constants::ORDER_STATUS_AWAITING_PAYMENT]);
                            $message = trans('fotober.payment.invoice_success');
                            $key = 'success';
                            // B???n noti cho KH th??ng b??o ???? t???o ????n h??ng tr??n Paypal
                            event(new NotificationEvent([
                                'message_vi' => 'The system has sent a payment request. Please, check your Paypal account',
                                'order' => $result,
                                'order_id' => $result->id,
                                'total_no_seen_cus' => 0,
                                'total_no_seen_sale' => 0,
                                'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                                'sender_id' => Auth::id(),
                                'receiver_id' => $result->customer_id
                            ]));

                            // G???i mail cho KH
                            SendEmailJob::dispatch(Constants::EMAIL_ORDER_AWAIT_PAYMENT, [
                                'customer_id' => $result->customer_id,
                                'order' => $res
                            ])->onQueue('email_customer');

                            return response()->json([
                                'code' => 200,
                                'message' => 'T???o h??a ????n th??nh c??ng',
                                'data' => null
                            ]);
                        }
                    } else{
                        return response()->json([
                            'code' => 400,
                            'message' => trans('fotober.payment.invoice_failed'),
                            'data' => null
                        ]);
                    }
                } else {
                    return response()->json([
                        'code' => 400,
                        'message' => 'T???o thanh to??n th???t b???i',
                        'data' => null
                    ]);
                }
            } else{
                return response()->json([
                    'code' => 400,
                    'message' => '???? t???n t???i th??ng tin thanh to??n',
                    'data' => $check_pay
                ]);
            }
        }
    }


    /**
     * H??m summary update
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function readySummaryUpdate(Request $request)
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
        //
        $data['user_id'] = Auth::id();
        $data['order'] = $this->orderRepo->findById(['id' => $id]);
        $data['payment_detail'] = $this->paymentDetailRepo->findByOrderId(['order_id' => $id]);
        //update tr???ng th??i Invoice
        // $pay_status = $this->paypalRepo->checkStatus($data['order']->payment->paypal_id);
        // if($pay_status){
        //     if($data['order']->status != Constants::ORDER_STATUS_PAID && $pay_status == 'PAID'){
        //         $result = $this->orderRepo->updateStatus(['id' => $id, 'status' => Constants::ORDER_STATUS_PAID]);
        //         $data['order'] = $this->orderRepo->findById(['id' => $id]);
        //     }
        // }

        $data['outputs'] = $outputs = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => $data['order']->customer_id]);
        $data['output_image'] = 0;
        $data['output_video'] = 0;
        foreach($outputs as $item){
            if($item->type == 'VIDEO'){
                $data['output_video'] = $data['output_video'] + 1;
            }
            if($item->type == 'IMAGE'){
                $data['output_image'] = $data['output_image'] + 1;
            }
        }
        $data['customer'] = $this->customerRepo->getDetail(['id' => $data['order']->customer_id]);

        return view(getBladeFromPage('/order/sale/order-sumary-edit'), $data);
    }


    /**
     * H?? x??? l?? t???o th??ng tin thanh to??n, chi ti???t thanh to??n, t???o h??a ????n paypal
     *
     */
    public function EditInvoicePaypal(Request $request)
    {
        $order_id = request('order_id', null);
        $dvi = request('dvi', null);
        $discount = request('discount', 0);
        $details = request('details', null);
        $note_sale = request('note_sale', null);
        $details = (array)$details;
        if ($order_id) {
            $order = $this->orderRepo->findById(['id' => $order_id]);
            $email_paypal = request('email_paypal', $order->customer->email_paypal);
            $check_pay = $this->paymentRepo->findByOrderId(['order_id' => $order_id]);
            if ($order && $order->status != Constants::ORDER_STATUS_PAID && $check_pay) {
                //C???p nh???t discount order
                $quantity = 0;
                $cost = 0;
                $total_payment = 0;
                if(count($details) > 0){
                    foreach($details as $item){
                        $quantity = $quantity + $item['quantity'];
                        $cost = $cost + $item['quantity']*$item['price'];
                    }
                    if($dvi == 'pre'){
                        if(floatval($discount) > 100){
                            $discount = 0;
                        }
                        $total_payment = $cost - $cost*floatval($discount)/100;
                    } else{
                        if(floatval($discount) > $cost){
                            $discount = 0;
                        }
                        $total_payment = $cost - floatval($discount);
                    }
                } else{
                    $discount = 0;
                }
                $order->quantity = $quantity;
                $order->cost = $cost;
                $order->total_payment = $total_payment;
                $order->discount = ($dvi == 'pre') ? floatval($discount) : 0;
                $order->discount_money = ($dvi == 'money') ? floatval($discount) : 0;
                $this->orderRepo->update($order);

                //T???o payment detail
                $param_pay['order_id'] = $order_id;
                $param_pay['customer_id'] = $order->customer_id;
                $param_pay['amount'] = 0;
                $param_pay['note_sale'] = $note_sale;
                $param_pay['email_paypal'] = $email_paypal;
                $param_pay['method'] = 'PAYPAL';
                $param_pay['paypal_id'] = $check_pay->paypal_id;
                $param_pay['link_payment'] = $check_pay->link_payment;
                $param_pay['status'] = Constants::PAYMENT_STATUS_NEW;
                $param_pay['created_by'] = Auth::id();
                //
                $result = $this->paymentRepo->updateOrCreate($param_pay);
                $payment_id = $check_pay->id;

                //X??a th??ng tin chi ti???t thanh to??n
                $payment_detail = $this->paymentDetailRepo->findByOrderId(['order_id' => $order_id]);
                if(count($payment_detail) > 0){
                    foreach($payment_detail as $item){
                        $this->paymentDetailRepo->delete(['id' => $item['id']]);
                    }
                }
                if(count($details) > 0){
                    foreach($details as $item){
                        $params['order_id'] = $order_id;
                        $params['payment_id'] = $payment_id ;
                        $params['description'] = $item["description"];
                        $params['quantity'] = intval($item["quantity"]);
                        $params['order_name'] = $item["item_name"];
                        $params['price'] = floatval($item["price"]);
                        $params['amount'] = intval($item["quantity"])*floatval($item["price"]);
                        //
                        $result_detail = $this->paymentDetailRepo->store($params);
                    }
                }

                //Update invoice
                $data['payment'] = $this->paymentRepo->findById(['id' => $payment_id]);
                $params['order_id'] = $data['payment']->order_id;
                $params['customer_id'] = $data['payment']->customer_id;
                $params['amount'] = $data['payment']->amount;
                if(isset($data['payment']['details']) && count($data['payment']['details']) > 0){

                    $api = $this->paypalRepo->updateInvoice($data['payment']);
                    if($api['updated_invoice'] == true){
                        // $res = $this->orderRepo->updateStatus(['id' => $params['order_id'], 'status' => Constants::ORDER_STATUS_AWAITING_PAYMENT]);

                        $res = $this->orderRepo->findById(['id' => $order_id]);
                        $message = trans('fotober.payment.invoice_success');
                        $key = 'success';
                        // B???n noti cho KH th??ng b??o ???? t???o ????n h??ng tr??n Paypal
                        event(new NotificationEvent([
                            'message_vi' => 'The system has sent a payment request. Please, check your Paypal account',
                            'order' => $res,
                            'order_id' => $res->id,
                            'total_no_seen_cus' => 0,
                            'total_no_seen_sale' => 0,
                            'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                            'sender_id' => Auth::id(),
                            'receiver_id' => $res->customer_id
                        ]));

                        // G???i mail cho KH
                        SendEmailJob::dispatch(Constants::EMAIL_ORDER_AWAIT_PAYMENT, [
                            'customer_id' => $res->customer_id,
                            'order' => $res
                        ])->onQueue('email_customer');

                        return response()->json([
                            'code' => 200,
                            'message' => 'C???p nh???t ????n th??nh c??ng',
                            'data' => null
                        ]);
                    }
                } else{
                    return response()->json([
                        'code' => 400,
                        'message' => trans('fotober.payment.invoice_failed'),
                        'data' => $data['payment']['details']
                    ]);
                }
            } else{
                return response()->json([
                    'code' => 400,
                    'message' => 'Kh??ng t??m th???y th??ng tin thanh to??n',
                    'data' => null
                ]);
            }
        }
    }

}
