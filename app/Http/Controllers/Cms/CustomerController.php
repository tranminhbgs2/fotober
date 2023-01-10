<?php

namespace App\Http\Controllers\Cms;

use App\Events\CountNoSeenEvent;
use App\Events\MessageEvent;
use App\Events\NotificationEvent;
use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Jobs\SendEmailJob;
use App\Models\Message;
use App\Models\User;
use App\Repositories\Customer\CustomerRepo;
use App\Repositories\Download\DownloadRepo;
use App\Repositories\Message\MessageRepo;
use App\Repositories\Notification\NotificationRepo;
use App\Repositories\Payment\PaymentDetailRepo;
use App\Repositories\Order\InputRepo;
use App\Repositories\Order\OrderRepo;
use App\Repositories\Order\OutputRepo;
use App\Repositories\Payment\PaymentRepo;
use App\Repositories\Paypal\PaypalRepo;
use App\Repositories\Service\ServiceRepo;
use App\Repositories\Upload\UploadRepo;
use App\Repositories\User\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class CustomerController extends Controller
{
    protected $orderRepo;
    protected $serviceRepo;
    protected $paymentRepo;
    protected $messageRepo;
    protected $customerRepo;
    protected $paymentDetailRepo;
    protected $userRepo;
    protected $uploadRepo;
    protected $outputRepo;
    protected $inputRepo;
    protected $downloadRepo;
    protected $notificationRepo;
    protected $paypalRepo;

    public function __construct(
        CustomerRepo $customerRepo,
        MessageRepo $messageRepo,
        OrderRepo $orderRepo,
        PaymentRepo $paymentRepo,
        ServiceRepo $serviceRepo,
        UserRepo $userRepo,
        UploadRepo $uploadRepo,
        OutputRepo $outputRepo,
        InputRepo $inputRepo,
        DownloadRepo $downloadRepo,
        NotificationRepo $notificationRepo,
        PaypalRepo $paypalRepo,
        PaymentDetailRepo $paymentDetailRepo
    )
    {
        $this->middleware('auth');  // Y/c phải login
        //
        $this->orderRepo = $orderRepo;
        $this->serviceRepo = $serviceRepo;
        $this->paymentRepo = $paymentRepo;
        $this->messageRepo = $messageRepo;
        $this->customerRepo = $customerRepo;
        $this->userRepo = $userRepo;
        $this->uploadRepo = $uploadRepo;
        $this->outputRepo = $outputRepo;
        $this->inputRepo = $inputRepo;
        $this->downloadRepo = $downloadRepo;
        $this->notificationRepo = $notificationRepo;
        $this->paypalRepo = $paypalRepo;
        $this->paymentDetailRepo = $paymentDetailRepo;
    }

    /**
     * Hàm show ds đơn hàng
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function order(Request $request)
    {
        $data['status'] = getOrderStatus(null, Constants::ACCOUNT_TYPE_CUSTOMER);
        return view(getBladeFromPage('/order/customer/order-index'), $data);
    }

    /**
     * Hàm show form chi tiết đơn hàng
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        $id = request('id', null);
        $noti_id = request('noti_id', null);

        // Update đã đọc
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

        //Lấy thông tin nhân viên
        if($data['order']->assigned_sale_id){
            $sale = $this->customerRepo->getDetail(['id' => $data['order']->assigned_sale_id]);
            $data['name_sale'] = $sale->fullname;
            $data['sale_avatar'] = $sale->avatar;
        } else {
            $data['name_sale'] = 'Fotober';
            $data['sale_avatar'] = asset(Constants::DEFAULT_AVATAR);
        }

        //Lấy thông tin customer
        $data['name_cus'] = (Auth::user()->fullname) ? Auth::user()->fullname : 'Me';
        $data['cus_avatar'] = Auth::user()->avatar;

        $data['messages'] = $this->messageRepo->getListAll(['order_id' => $id]);
        $data['file_messages'] = $this->messageRepo->getListAll(['order_id' => $id, 'order_by' => 'DESC']);

        return view(getBladeFromPage('/order/customer/order-detail'), $data);
    }

    /**
     * Hàm show form tạo mới đơn hàng
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $data['url_service_id'] = request('service_id', null);
        $data['services'] = $this->serviceRepo->listing([
            'status' => 1,
            'page_index' => 1,
            'page_size' => 50,
            'order_by' => [
                ['field' => 'sort', 'direction' => 'ASC']
            ],
        ]);
        $data['turn_arround_times'] = getTurnArroundTime();

        return view(getBladeFromPage('/order/customer/order-create'), $data);
    }

    /**
     * Hàm xử lý tạo đơn hàng
     *
     * @param StoreOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        $params['name'] = request('name', null);
        $params['service_id'] = request('service_id', null);
        //$params['options'] = request('options', null);
        $params['email_receiver'] = request('email_receiver', null);
        $params['turn_arround_time'] = request('turn_arround_time', null);
        // $params['upload_file'] = request('upload_file', null);
        $params['notes'] = request('notes', null);
        $params['assigned_sale_id'] = Auth::user()->manager_by;
        //
        $params['status'] = request('status', 0);
        // Tính toán option
        if ( request('link', null) && $request->hasFile('upload_file')) {
            $options = 'ALL';
        } else {
            if (request('link', null)) {
                $options = 'LINK';
            }

            if ($request->hasFile('upload_file')) {
                $options = 'UPLOAD';
            }
        }
        $params['options'] = $options;
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
                            'customer_id' => Auth::id(),
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
                foreach($params['upload_file'] as $key => $file){
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

            if(Auth::user()->manager_by > 0 && !empty($result->notes)){
                //Đẩy ghi chú vào hội thoại chat
                $params['message'] = $result->notes;
                $params['order_id'] = $result->id;
                $params['customer_id'] = $result->customer_id;
                $params['type'] = 'TEXT';
                $result_mess = $this->messageRepo->store($params);
            }

            // Bắn noti cho sale admin và sale member phụ trách
            if ($params['status'] == Constants::ORDER_STATUS_NEW) {
                // Bắn noti cho sale member phụ trách nếu có
                if ($result->assigned_sale_id > 0) {
                    event(new NotificationEvent([
                        'message_vi' => trans('fotober.order.customer_sends_order_request').' '  . $result->name,
                        'order' => $result,
                        'order_id' => $result->id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_SALE,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $result->assigned_sale_id
                    ]));
                    SendEmailJob::dispatch(Constants::EMAIL_ORDER_CREATE, [
                        'customer_id' => $result->assigned_sale_id,
                        'order' => $result
                    ])->onQueue('email_sale');
                }

                // Bắn noti cho sale admin, tìm 1 sale admin rồi bắn cho
                $sale_admin = User::where('account_type', Constants::ACCOUNT_TYPE_SALE)->where('is_admin', 1)->first();
                if ($sale_admin) {
                    event(new NotificationEvent([
                        'message_vi' => trans('fotober.order.customer_sends_order_request').' '  . $result->name,
                        'order' => $result,
                        'order_id' => $result->id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_SALE,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $sale_admin->id
                    ]));
                }
            }

            // Gửi mail cho KH
            SendEmailJob::dispatch(Constants::EMAIL_ORDER_CREATE, [
                'customer_id' => Auth::id(),
                'order' => $result
            ])->onQueue('email_customer');

            $message = trans('fotober.order.mess_create_success');
        } else {
            $message = trans('fotober.order.mess_create_failed');
        }
        //
        return redirect()->route('customer_order')->with('message', $message);
    }

    /**
     * Hàm show form cập nhật đơn hàng
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
        //
        $data['order'] = $this->orderRepo->findById(['id' => $id]);
        $data['turn_arround_times'] = getTurnArroundTime();

        return view(getBladeFromPage('/order/customer/order-edit'), $data);
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
        //
        $result = $this->orderRepo->update($params);
        if ($result) {
            $message = trans('fotober.order.mess_update_success');
        } else {
            $message = trans('fotober.order.mess_update_failed');
        }
        //
        return redirect()->route('customer_order')->with('message', $message);
    }

    /**
     * Hàm thực hiện xóa đơn hàng, kết hợp check quyền trước khi xóa
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $id = request('id', null);
        $result = $this->orderRepo->delete(['id' => $id]);

        if ($result == Constants::PERMISSION_DENIED) {
            $message = trans('fotober.common.permission_denied');
            return redirect()->route('customer_order')->with('message', $message);
        }

        if ($result) {
            $message = trans('fotober.order.mess_delete_success');
        } else {
            $message = trans('fotober.order.mess_delete_failed');
        }

        return redirect()->route('customer_order')->with('message', $message);
    }

    /**
     * KH gửi y/c từ draft order -> new order
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendRequest(Request $request)
    {
        $id = request('id', null);
        $order = $this->orderRepo->findById(['id' => $id]);

        if ($order->status == Constants::ORDER_STATUS_DRAFT) {
            $result = $this->orderRepo->sendRequest(['id' => $id]);

            $order = $this->orderRepo->findById(['id' => $id]);
            if(Auth::user()->manager_by > 0 && !empty($order->notes)){
                //Đẩy ghi chú vào hội thoại chat
                $params['message'] = $order->notes;
                $params['order_id'] = $id;
                $params['customer_id'] = $order->customer_id;
                $params['type'] = 'TEXT';
                $result_mess = $this->messageRepo->store($params);
            }

            if ($result) {
                // Bắn noti cho sale member phụ trách nếu có
                if ($order->assigned_sale_id > 0) {
                    event(new NotificationEvent([
                        'message_vi' => trans('fotober.order.customer_sends_order_request').' ' . $order->name,
                        'order' => $order,
                        'order_id' => $id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_SALE,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $order->assigned_sale_id
                    ]));
                }

                // Bắn noti cho sale admin, tìm 1 sale admin rồi bắn cho
                $sale_admin = User::where('account_type', Constants::ACCOUNT_TYPE_SALE)->where('is_admin', 1)->first();
                if ($sale_admin) {
                    event(new NotificationEvent([
                        'message_vi' => trans('fotober.order.customer_sends_order_request').' ' . $order->name,
                        'order' => $order,
                        'order_id' => $id,
                        'total_no_seen_cus' => 0,
                        'total_no_seen_sale' => 0,
                        'account_type' => Constants::ACCOUNT_TYPE_SALE,
                        'sender_id' => Auth::id(),
                        'receiver_id' => $sale_admin->id
                    ]));
                }

                // Gửi mail cho KH
                SendEmailJob::dispatch(Constants::EMAIL_ORDER_CREATE, [
                    'customer_id' => $order->customer_id,
                    'order' => $order
                ])->onQueue('email_customer');

                $message = trans('fotober.order.request_sent_successfully');
                return redirect()->route('customer_order')->with('success', $message);
            } else {
                $message = trans('fotober.order.request_sent_faild');
                return redirect()->route('customer_order')->with('danger', $message);
            }
        } else {
            $message = trans('fotober.order.request_has_been_sent_before');
            return redirect()->route('customer_order')->with('danger', $message);
        }
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
        $params['sort_by_time'] = request('sort_by_time', -1);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        $params['account_type'] = Constants::ACCOUNT_TYPE_CUSTOMER;
        //
        $data['data'] = $this->orderRepo->getListing($params);
        // print_r($data['data']);
        $total = $this->orderRepo->getListing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        return view(getBladeFromPage('/order/customer/ajax-order-index'), $data);
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
        // print_r($data['inputs']);
        // die();
        $data['outputs'] = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => $user_id]);
        return view(getBladeFromPage('/order/customer/ajax-order-info'), $data);
    }

    /**
     * Hàm lấy thông tin chat theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderChatAjax(Request $request)
    {
        $id = request('order_id', null);
        $noti_id = request('noti_id', null);

        // Update đã đọc
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

        //Lấy thông tin nhân viên
        if($data['order']->assigned_sale_id){
            $sale = $this->customerRepo->getDetail(['id' => $data['order']->assigned_sale_id]);
            $data['name_sale'] = $sale->fullname;
            $data['sale_avatar'] = $sale->avatar;
        } else {
            $data['name_sale'] = 'Fotober';
            $data['sale_avatar'] = asset(Constants::DEFAULT_AVATAR);
        }

        //Lấy thông tin customer
        $data['name_cus'] = (Auth::user()->fullname) ? Auth::user()->fullname : 'Me';
        $data['cus_avatar'] = Auth::user()->avatar;

        $result = $this->messageRepo->updateSeen(['order_id' =>  $data['order']->id, 'sale_id' =>  $data['order']->assigned_sale_id, 'seen' => 1]);
        $data['messages'] = $this->messageRepo->getListAll(['order_id' => $id]);
        $data['file_messages'] = $this->messageRepo->getListAll(['order_id' => $id, 'order_by' => 'DESC']);

        return view(getBladeFromPage('/order/customer/ajax-chat-customer'), $data);
    }
    
    /**
     * Hàm show ds thanh toán ------------------------------------------------------------------------------------------
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function payment(Request $request)
    {
        $data['status'] = getPaymentStatus();
        return view(getBladeFromPage('/payment/customer/payment-index'), $data);
    }

    /**
     * Hàm lấy ds thanh toán theo ajax
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
        return view(getBladeFromPage('/payment/customer/ajax-payment-index'), $data);
    }

    /**
     * Hàm lấy thông tin thanh toán theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function paymentInfoAjax(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $data['payment'] = $this->paymentRepo->findById(['id' => $id]);
        return view(getBladeFromPage('/payment/customer/ajax-payment-info'), $data);
    }

    public function customer_payment_paypal(Request $request)
    {
        $id = request('id', null);
        return redirect('https://www.paypal.com/vn/signin');
    }

    public function referal(Request $request)
    {

        return view(getBladeFromPage('/referal/customer/referal_index'));
    }

    public function report(Request $request)
    {

        return view(getBladeFromPage('/report/customer/report_index'));
    }

    public function setting(Request $request)
    {

        return view(getBladeFromPage('/setting/customer/setting_index'));
    }

    /**
     * Gửi tin nhắn
     *
     */
    public function sendMessage(Request $request)
    {
        $params['message'] = request('message', null);
        $params['order_id'] = request('order_id', null);

        $order = $this->orderRepo->findById(['id' => $params['order_id']]);

        // Lấy tin cuối cùng để check cách hơn 1h thì bắn noti
        $last_message = Message::where('order_id', $order->id)->orderBy('id', 'DESC')->first();

        $params['customer_id'] = $order->customer_id;
        $params['type'] = request('type', Constants::MESSAGE_TYPE_TEXT);
        $params['seen'] = 0;

        if(Auth::id() == $order->customer_id){
            $result = $this->messageRepo->updateSeen(['order_id' => $order->id, 'sale_id' => $order->assigned_sale_id, 'seen' => 1]);
            $result = $this->messageRepo->store($params);
        }
        $total_no_seen_cus = $this->messageRepo->totalNoSeen(['order_id' => $order->id, 'customer_id' => $order->customer_id]); //Số tin nhắn Sale chưa xem
        $total_no_seen_sale = $this->messageRepo->totalNoSeen(['order_id' => $order->id, 'sale_id' => $order->assigned_sale_id]); //số tin nhắn KH chưa xem
        $param_mess = [
            'mess_sale' => '',
            'mess_cus' => $params['message'],
            'type' => 'customer',
            'order_id' => $params['order_id'],
            'type_mess' => $params['type'],
            'total_no_seen_cus' => count($total_no_seen_cus),
            'total_no_seen_sale' => count($total_no_seen_sale),
            'file_name' => '',
            'created_at' => date('h:i a'),
            'sender_name' => (Auth::user()->fullname) ? Auth::user()->fullname : 'Fotober',
            'sender_avatar' => Auth::user()->avatar
        ];
        event(new MessageEvent($param_mess));

        if ($result) {
            $res = ['success' => true];
            // Bắn noti cho sale phụ trách: check xem tin nhắn cuối cách hơn 1h thì bắn noti
            // if ($last_message && (strtotime($last_message->created_at) < time() - 3600)) {
                event(new NotificationEvent([
                    'scope' => 'CHAT',
                    'message_vi' => 'Bạn có tin nhắn từ đơn hàng: ' . $order->code. ' của khách hàng '. Auth::user()->fullname,
                    'order' => $order,
                    'total_no_seen_cus' => count($total_no_seen_cus),
                    'total_no_seen_sale' => count($total_no_seen_sale),
                    'order_id' => $order->id,
                    'account_type' => Constants::ACCOUNT_TYPE_SALE,
                    'sender_id' => Auth::id(),
                    'receiver_id' => $order->assigned_sale_id
                ]));
            // } else {
            //     Log::info('ooo');
            // }
        } else {
            $res = ['success' => false];
        }

        return $res;
    }

    /**
     * Gửi tin nhắn
     *
     */
    public function sendMessageImage(Request $request)
    {
        $params['type'] = request('type', null);
        $params['message'] = null;
        $params['order_id'] = request('order_id', null);

        $order = $this->orderRepo->findById(['id' => $params['order_id']]);

        $params['customer_id'] = $order->customer_id;

        if(Auth::id() == $order->customer_id){
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
                $message = asset('storage/'.$path);
            } else {
                $message = 'Thử lại!';
            }
        } else{
            $message = 'Thử lại!';
        }
        $param_mess = [
            'mess_sale' => '',
            'mess_cus' => $message,
            'type' => 'customer',
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

    /**
     * Hàm lấy thông tin input theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderInputAjax(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $data['inputs'] = $this->inputRepo->getListing(['order_id' => $id, 'customer_id' => $user_id]);
        return view(getBladeFromPage('/order/customer/ajax-order-input-index'), $data);
    }

    /**
     * Hàm lấy thông tin đơn hàng theo ajax
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function orderOutputAjax(Request $request)
    {
        $id = request('order_id', null);
        $user_id = request('user_id', null);
        $data['outputs'] = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => $user_id]);
        return view(getBladeFromPage('/order/customer/ajax-order-output-index'), $data);
    }

    /**
     * Xử lý tải file nén zip
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
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
     * Xử lý tải file nén zip
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downOutputZip()
    {
        $order_id = request('order_id', null);
        $order = $this->orderRepo->findById(['id' => $order_id]);
        if($order->status != Constants::ORDER_STATUS_PAID){
            return redirect()->route('dashboard_home');
        }
        $down = $this->downloadRepo->downloadOutputZip(['order_id' => $order_id]);
        if($down){
            return response()->download($down);
        } else{
            return redirect()->route('dashboard_home');
        }
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
        return view('themes/cms/fotober/pages/service/customer/service-index', $data);
    }

    public function listingAjaxService(Request $request)
    {
        $params['keyword'] = request('keyword', null);
        $params['status'] = request('status', null);
        $params['group_code'] = request('group_code', 0);
        $params['page_index'] = request('page_index', 1);
        $params['page_size'] = request('page_size', 10);
        // Nếu chọn theo nhóm thì sắp xếp theo sort trước
        if ($params['group_code'] > 0) {
            $params['order_by'] = [
                ['field' => 'sort', 'direction' => 'ASC'],
                ['field' => 'name', 'direction' => 'ASC']
            ];
        } else {
            $params['order_by'] = [
                ['field' => 'sort', 'direction' => 'ASC'],
            ];
        }
        //
        $data['data'] = $this->serviceRepo->listing($params);
        $total = $this->serviceRepo->listing($params, true);
        $data['paginate_link'] = paginateAjax($total, $params['page_index'], $params['page_size']);
        $data['offset'] = ($params['page_index'] - 1)*$params['page_size'];
        //
        $data['status'] = getServiceStatus();

        return view('themes/cms/fotober/pages/service/customer/ajax-service-index', $data);
    }

    /**
     * Hàm show Form nhận đánh giá
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function readyPreview(Request $request)
    {
        $id = request('id', null);
        $noti_id = request('noti_id', null);

        // Update đã đọc
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

        //update trạng thái Invoice
        if(count($data['payment_detail']) > 0){
            $pay_status = $this->paypalRepo->checkStatus($data['order']->payment->paypal_id);
            if($pay_status){
                if($data['order']->status != Constants::ORDER_STATUS_PAID && $pay_status == 'PAID'){
                    $result = $this->orderRepo->updateStatus(['id' => $id, 'status' => Constants::ORDER_STATUS_PAID]);
                    $payment = $this->paymentRepo->updateStatus(['id' => $data['order']->payment->id, 'status' => Constants::PAYMENT_STATUS_SUCCESS]);
                }
                if($data['order']->status != Constants::ORDER_STATUS_PAID && $pay_status == 'CANCELLED'){
                    $result = $this->paymentRepo->updateStatus(['id' => $data['order']->payment->id, 'status' => Constants::PAYMENT_STATUS_FALIED]);
                }
                $data['order'] = $this->orderRepo->findById(['id' => $id]);
            }
        }

        $data['outputs'] = $outputs = $this->outputRepo->getListing(['order_id' => $id, 'customer_id' => Auth::id(), 'page_size' => 100]);
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

        return view(getBladeFromPage('/order/customer/order-preview'), $data);
    }

    
    /**
     * Hàm thực hiện duyệt ảnh đầu ra
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptOutput(Request $request)
    {
        $id = request('id', null);
        $result = $this->outputRepo->acceptOutput(['id' => $id]);
        $output = $this->outputRepo->findById(['id' => $id]);
        $order = $this->orderRepo->findById(['id' => $output->order_id]);
        if ($order->assigned_sale_id > 0) {
            event(new NotificationEvent([
                'message_vi' => trans('fotober.order.customer_sends_order_accept_output').' '  . $order->name,
                'order' => $order,
                'order_id' => $order->id,
                'total_no_seen_cus' => 0,
                'total_no_seen_sale' => 0,
                'account_type' => Constants::ACCOUNT_TYPE_SALE,
                'sender_id' => Auth::id(),
                'receiver_id' => $order->assigned_sale_id
            ]));
            // Gửi mail cho Sale
            SendEmailJob::dispatch(Constants::EMAIL_ORDER_ACCEPT_OUTPUT, [
                'customer_id' => $order->assigned_sale_id,
                'order' => $order
            ])->onQueue('email_sale');
        }
        if ($result) {
            $data['code'] = 200;
            $data['message'] = trans('fotober.order.mess_accept_success');
        } else {
            $data['code'] = 200;
            $data['message'] = trans('fotober.order.mess_accept_failed');
        }

        return response()->json($data);
    }

    
    /**
     * Hàm thực hiện gửi yêu cầu sửa đổi
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function requestOutput(Request $request)
    {
        $id = request('id', null);
        $request_revision = request('request_revision', null);
        $result = $this->outputRepo->requestRevision(['id' => $id, 'request_revision' => $request_revision]);
        
        $data = [];
        if ($result) {
            $output = $this->outputRepo->findById(['id' => $id]);
            // if($output->order->status != Constants::ORDER_STATUS_EDITING){
            //     $params_order['id'] = $output->order_id;
            //     // $params_order['status'] = Constants::ORDER_STATUS_EDITING;
            //     $this->orderRepo->update($params_order);
            // }
            
            $order = $this->orderRepo->findById(['id' => $output->order_id]);
            if ($order->assigned_sale_id > 0) {
                event(new NotificationEvent([
                    'message_vi' => trans('fotober.order.customer_sends_order_request_revision').' '  . $order->name,
                    'order' => $order,
                    'order_id' => $order->id,
                    'total_no_seen_cus' => 0,
                    'total_no_seen_sale' => 0,
                    'account_type' => Constants::ACCOUNT_TYPE_SALE,
                    'sender_id' => Auth::id(),
                    'receiver_id' => $order->assigned_sale_id
                ]));
                
                // Gửi mail cho Sale
                SendEmailJob::dispatch(Constants::EMAIL_ORDER_REQUEST_OUTPUT, [
                    'customer_id' => $order->assigned_sale_id,
                    'order' => $order
                ])->onQueue('email_sale');
            }
            $data['code'] = 200;
            $data['message'] = trans('fotober.order.mess_request_success');
        } else {
            $data['code'] = 400;
            $data['message'] = trans('fotober.order.mess_request_failed');
        }
        return response()->json($data);
    }

    
    /**
     * Hàm thực hiện gửi yêu cầu sửa đổi
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function previewSubmit(Request $request)
    {
        $id = request('id', null);
        $rating = request('rating', null);
        $review = request('review', null);
        $result = $this->orderRepo->reviewSubmit(['id' => $id, 'rating' => $rating, 'review' => $review]);
        
        $data = [];
        if ($result) {
            $cate = getIDService($result->service_id);
            if($cate > 0){
                $ch = curl_init();
                $url = "https://fotober.com/wp-json/wp/v2/createreview";
                $dataArray = [
                    'cate' => $cate,
                    'title' => Auth::user()->fullname,
                    'content' => $review
                ];
            
                $dataparam = http_build_query($dataArray);
            
                $getUrl = $url."?".$dataparam;
            
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_URL, $getUrl);
                curl_setopt($ch, CURLOPT_TIMEOUT, 80);
                
                $response = curl_exec($ch);
                    
                if(curl_error($ch)){
                    // echo 'Request Error:' . curl_error($ch);
                }else{
                    $response = json_decode($response);
                    if($response->code == 200){
                        $data['code'] = 200;
                        $data['message'] = trans('fotober.order.mess_review_success');
                    }
                }
                
                curl_close($ch);
            } else{
                $data['code'] = 400;
                $data['message'] ='Không tìm thấy dịch vụ';
            }
        } else {
            $data['code'] = 400;
            $data['message'] = trans('fotober.order.mess_review_failed');
        }
        return response()->json($data);
    }
}
