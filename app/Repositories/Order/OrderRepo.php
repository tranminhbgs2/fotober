<?php

namespace App\Repositories\Order;

use App\Helpers\Constants;
use App\Models\Customer;
use App\Models\Log\LogFollow;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Repositories\BaseRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderRepo extends BaseRepo
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hàm lấy ds Order, có tìm kiếm và phân trang
     *
     * @param $params
     * @param false $is_counting
     *
     * @return mixed
     */
    public function getListing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;

        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : -1;
        $service_id = isset($params['service_id']) ? $params['service_id'] : -1;
        $assigned_sale_id = isset($params['assigned_sale_id']) ? $params['assigned_sale_id'] : -1;
        $assigned_admin_id = isset($params['assigned_admin_id']) ? $params['assigned_admin_id'] : -1;
        $assigned_editor_id = isset($params['assigned_editor_id']) ? $params['assigned_editor_id'] : -1;
        $assigned_qaqc_id = isset($params['assigned_qaqc_id']) ? $params['assigned_qaqc_id'] : -1;

        $status = isset($params['status']) ? $params['status'] : -1;
        $sort_by_time = isset($params['sort_by_time']) ? $params['sort_by_time'] : -1;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        //
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : 0;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : Auth::id();
        // Lấy số requirement nếu là true
        $is_requirement = isset($params['is_requirement']) ? $params['is_requirement'] : false;
        // Bắt tham số khi gọi ở dashboard
        $group = isset($params['group']) ? $params['group'] : null;
        //
        $query = Order::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('name', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('code', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('notes', 'LIKE', "%" . $keyword . "%");
            });
        });

        // Lọc theo customer nào
        if ($customer_id >= 0) {
            $query->where('customer_id', $customer_id);
        }

        // Lọc theo dịch vụ nào
        if ($service_id >= 0) {
            $query->where('service_id', $service_id);
        }

        // Lọc theo sale nào
        if ($assigned_sale_id > 0) {
            $query->where('assigned_sale_id', $assigned_sale_id);
        }

        // Lọc theo admin nào
        if ($assigned_admin_id > 0) {
            $query->where('assigned_admin_id', $assigned_admin_id);
        }

        // Lọc theo editor nào
        if ($assigned_editor_id > 0) {
            $query->where('assigned_editor_id', $assigned_editor_id);
        }

        // Lọc theo qaqc nào
        if ($assigned_qaqc_id > 0) {
            $query->where('assigned_qaqc_id', $assigned_qaqc_id);
        }

        // Check trạng thái theo từng quyền
        switch ($account_type) {
            case Constants::ACCOUNT_TYPE_CUSTOMER:
                $array_status = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
                $query->where('customer_id', Auth::id());
                break;
            case Constants::ACCOUNT_TYPE_SALE:
                $array_status = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
                // Phân quyền sale member chỉ nhìn được order đã được gán và có thể nhận thêm order chưa được gán
                if ($is_admin == 0 && $sale_id > 0) {
                    $query->where(function ($sub_sql) use ($sale_id){
                        $sub_sql->where('assigned_sale_id', $sale_id);  // Lấy order được gán
                        // $sub_sql->orWhere('assigned_sale_id', null);    // Lấy cả những order chưa được gán để nhận thêm
                    });
                }
                break;
            case Constants::ACCOUNT_TYPE_ADMIN:
                $array_status = [2, 3, 4, 5, 6, 7, 8, 9];
                break;
            case Constants::ACCOUNT_TYPE_EDITOR:
                $array_status = [3, 4, 5, 6, 7, 8, 9];
                break;
            case Constants::ACCOUNT_TYPE_QAQC:
                $array_status = [4, 5, 6, 7, 8, 9];
                break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN:
                $array_status = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
                break;
            default:
                $array_status = [];
        }

        // Nếu truyền lên status thì so sánh theo status, trái lại lấy theo quyền
        if ($status >= 0) {
            // Nếu truyền lên editing(3) thì lấy cả 4,5,6,7. Vì 3,4,5,6,7=editing trong thiết kế mới
            if ($status == Constants::ORDER_STATUS_EDITING) {
                $query->whereIn('status', [3,4,5,6,7]);
            } 
            else {
                // die($status);
                $query->where('status', $status);
            }
        } else {
            if (count($array_status) > 0) {
                $query->whereIn('status', $array_status);
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

        $query->with([
            'customer' => function($sql){
                $sql->select(['id', 'fullname', 'email', 'email_paypal']);
            },
            'payment' => function($sql){
                $sql->select(['id', 'order_id', 'amount', 'method', 'email_paypal', 'status', 'link_payment']);
            },
            'service' => function($sql){
                $sql->select(['id', 'name']);
            },
            'requirements' => function($sql){
                $sql->select(['id', 'order_id', 'name', 'status']);
            },
            'requirementDone' => function($sql){
                $sql->select(['id', 'order_id', 'name', 'status'])->whereNotIn('status', [2])->get();
            },
            'total_no_seen' => function($sql){
                $sql->select(['id', 'order_id', 'customer_id', 'sale_id', 'seen'])->where('seen', 0);
            },
        ]);

        //Sắp xếp theo thời gian: mới nhất, cũ nhất
        if (in_array($sort_by_time, [Constants::ORDER_NEWEST, Constants::ORDER_OLDEST])) {
            if ($sort_by_time == Constants::ORDER_NEWEST) {
                $query->orderBy('created_at', 'DESC');
            } else {
                $query->orderBy('created_at', 'ASC');
            }
        } else {
            if (is_array($order_by) && count($order_by) > 0) {
                foreach ($order_by as $order) {
                    $query->orderBy($order['field'], $order['direction']);
                }
            } else {
                $query->orderBy('id', 'DESC');
            }
        }
        // die($query->get());
        return $query->get();
    }

    /**
     * Hàm lấy data tổng hợp ở dashboard
     *
     * @param $params
     * @return mixed
     */
    public function summaryOrder($params)
    {
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        $group = isset($params['group']) ? $params['group'] : null;
        $from_date = isset($params['from_date']) ? $params['from_date'] : null;
        $to_date = isset($params['to_date']) ? $params['to_date'] : null;
        //
        $query = Order::selectRaw('customer_id, status, COUNT(id) AS total');
        switch (strtoupper($group)) {
            case Constants::ACCOUNT_TYPE_CUSTOMER:
                $query->where('customer_id', $customer_id);
                break;
            case Constants::ACCOUNT_TYPE_SALE: break;
            case Constants::ACCOUNT_TYPE_ADMIN: break;
            case Constants::ACCOUNT_TYPE_EDITOR: break;
            case Constants::ACCOUNT_TYPE_QAQC: break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: break;
            default:
        }

        // Lọc theo ngày from-to đưa vào
        if ($from_date && $to_date) {
            $from_date = date('Y-m-d 00:00:00', strtotime($from_date));
            $to_date = date('Y-m-d 23:59:59', strtotime($to_date));
            //
            $query->where('created_at', '>=', $from_date);
            $query->where('created_at', '<=', $to_date);
        }

        // Nhóm theo từng trạng thái
        $query->groupBy('status');

        return $query->get();

    }

    /**
     * Hàm lấy những order nháp của Customer
     *
     * @param $params
     * @return mixed
     */
    public function draftOrder($params)
    {
        $status = isset($params['status']) ? $params['status'] : Constants::ORDER_STATUS_DRAFT;
        $group = isset($params['group']) ? $params['group'] : null;
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        //
        $query = Order::select('*');
        if ($group == Constants::ACCOUNT_TYPE_CUSTOMER && $customer_id >= 0) {
            $query->where('customer_id', $customer_id);
        }
        $query->where('status', $status);
        $query->orderBy('id', 'DESC');
        //
        return $query->get();
    }

    /**
     * Hàm lấy new order theo từng quyền
     *
     * @param $params
     * @return mixed
     */
    public function newOrder($params)
    {
        $status = isset($params['status']) ? $params['status'] : Constants::ORDER_STATUS_DRAFT;
        $group = isset($params['group']) ? $params['group'] : null;
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : false;
        //
        $query = Order::select('*');
        switch ($account_type) {
            case Constants::ACCOUNT_TYPE_SALE:
                if (! $is_admin) {
                    $query->where('assigned_sale_id', Auth::id());
                }
                $query->where('status', Constants::ORDER_STATUS_NEW);
                break;
            case Constants::ACCOUNT_TYPE_ADMIN:
                $query->where('assigned_admin_id', Auth::id());
                $query->where('status', Constants::ORDER_STATUS_PENDING);
                break;
            case Constants::ACCOUNT_TYPE_EDITOR:
                $query->where('assigned_editor_id', Auth::id());
                $query->where('status', Constants::ORDER_STATUS_EDITING);
                break;
            case Constants::ACCOUNT_TYPE_QAQC:
                $query->where('assigned_qaqc_id', Auth::id());
                $query->where('status', Constants::ORDER_STATUS_EDITED);
                break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN:
                $query->where('status', Constants::ORDER_STATUS_NEW);
                break;
        }
        $query->orderBy('id', 'DESC');
        //
        return $query->get();
    }

    /**
     * Hàm lấy deadline của order
     *
     * @param $params
     * @return mixed
     */
    public function deadlineOrder($params)
    {
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : false;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        //
        $query = Order::select('*');
        switch ($account_type) {
            case Constants::ACCOUNT_TYPE_SALE:
                if (! $is_admin) {
                    $query->where('assigned_sale_id', Auth::id());
                }
                $query->where('status', '>=', Constants::ORDER_STATUS_NEW);
                break;
            case Constants::ACCOUNT_TYPE_ADMIN:
                $query->where('assigned_admin_id', Auth::id());
                $query->where('status', '>=', Constants::ORDER_STATUS_PENDING);
                break;
            case Constants::ACCOUNT_TYPE_EDITOR:
                $query->where('assigned_editor_id', Auth::id());
                $query->where('status', '>=', Constants::ORDER_STATUS_EDITING);
                break;
            case Constants::ACCOUNT_TYPE_QAQC:
                $query->where('assigned_qaqc_id', Auth::id());
                $query->where('status', '>=', Constants::ORDER_STATUS_EDITED);
                break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: break;
        }

        $offset = ($page_index - 1) * $page_size;
        if ($page_size > 0 && $offset >= 0) {
            $query->take($page_size)->skip($offset);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $order) {
                $query->orderBy($order['field'], $order['direction']);
            }
        } else {
            $query->orderBy('id', 'DESC');
        }
        //
        return $query->get();
    }

    /**
     * Hàm lấy các order gần nhất
     * @param $params
     * @return mixed
     */
    public function recentOrder($params)
    {
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : false;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        //
        $query = Order::select('*');
        switch ($account_type) {
            case Constants::ACCOUNT_TYPE_CUSTOMER:
                $query->where('customer_id', Auth::id());
                break;
            case Constants::ACCOUNT_TYPE_SALE:
                if (! $is_admin) {
                    $query->where('assigned_sale_id', Auth::id());
                }
                break;
            case Constants::ACCOUNT_TYPE_ADMIN:
                $query->where('assigned_admin_id', Auth::id());
                break;
            case Constants::ACCOUNT_TYPE_EDITOR:
                $query->where('assigned_editor_id', Auth::id());
                break;
            case Constants::ACCOUNT_TYPE_QAQC:
                $query->where('assigned_qaqc_id', Auth::id());
                break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: break;
        }

        $offset = ($page_index - 1) * $page_size;
        if ($page_size > 0 && $offset >= 0) {
            $query->take($page_size)->skip($offset);
        }

        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $order) {
                $query->orderBy($order['field'], $order['direction']);
            }
        } else {
            $query->orderBy('id', 'DESC');
        }
        //
        return $query->get();
    }

    /**
     * Hàm lấy order theo id
     * @param $id
     * @return mixed
     */
    public function findById($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;

        $query = Order::where('id', $id)->with([
            'service' => function($sql){
                $sql->select(['id', 'name']);
            },
            'customer' => function($sql){
                $sql->select(['id', 'fullname', 'email', 'email_paypal', 'manager_by']);
            },
            'payment' => function($sql){
                $sql->select(['id', 'order_id', 'customer_id', 'note_sale', 'email_paypal', 'link_payment', 'paypal_id', 'amount', 'status', 'created_at']);
            },
            'payment_detail' => function($sql){
                $sql->select(['id', 'order_id', 'payment_id', 'order_name', 'quantity', 'price', 'amount', 'description', 'created_at']);
            },
            'output' => function($sql){
                $sql->select(['id', 'order_id', 'fix_request', 'customer_id', 'link', 'file']);
            }
        ]);

        return $query->first();
    }


    /**
     * Hàm lấy order theo id
     * @param $id
     * @return mixed
     */
    public function findByCustomerId($params)
    {
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'DESC';

        $query = Order::where('customer_id', $customer_id)->orderBy('id', $order_by)->with([
            'service' => function($sql){
                $sql->select(['id', 'name']);
            },
            'customer' => function($sql){
                $sql->select(['id', 'fullname', 'email', 'email_paypal', 'manager_by']);
            },
            'payment' => function($sql){
                $sql->select(['id', 'order_id', 'customer_id', 'note_sale', 'email_paypal', 'link_payment', 'paypal_id', 'amount', 'status', 'created_at']);
            },
            'payment_detail' => function($sql){
                $sql->select(['id', 'order_id', 'payment_id', 'order_name', 'quantity', 'price', 'amount', 'description', 'created_at']);
            },
            'output' => function($sql){
                $sql->select(['id', 'order_id', 'fix_request', 'customer_id', 'link', 'file']);
            }
        ]);

        return $query->first();
    }

    /**
     * Hàm lưu thông tin đơn hàng
     *
     * @param $params
     * @return Order|false
     */
    public function store($params)
    {
        $param_fill = [];
        //
        (isset($params['name']) && $params['name']) ? $param_fill['name'] = $params['name'] : null;
        (isset($params['service_id']) && $params['service_id']) ? $param_fill['service_id'] = $params['service_id'] :  $param_fill['service_id'] = null;
        (isset($params['options']) && $params['options']) ? $param_fill['options'] = $params['options'] : null;
        (isset($params['link']) && $params['link']) ? $param_fill['link'] = $params['link'] : null;
        (isset($params['email_receiver']) && $params['email_receiver']) ? $param_fill['email_receiver'] = $params['email_receiver'] : null;
        (isset($params['turn_arround_time']) && $params['turn_arround_time']) ? $param_fill['turn_arround_time'] = $params['turn_arround_time'] : null;
        (isset($params['upload_file']) && $params['upload_file']) ? $param_fill['upload_file'] = $params['upload_file'] : null;
        (isset($params['notes']) && $params['notes']) ? $param_fill['notes'] = $params['notes'] : null;
        (isset($params['assigned_sale_id']) && $params['assigned_sale_id'] > 0) ? $param_fill['assigned_sale_id'] = $params['assigned_sale_id'] : $param_fill['assigned_sale_id'] = 0;
        //
        (isset($params['customer_id']) && $params['customer_id']) ? $param_fill['customer_id'] = $params['customer_id'] : $param_fill['customer_id'] = Auth::id();
        (isset($params['created_type']) && $params['created_type']) ? $param_fill['created_type'] = $params['created_type'] : $param_fill['created_type'] = Constants::ACCOUNT_TYPE_CUSTOMER;
        //
        if ($param_fill['name'] && $param_fill['service_id'] > 0) {
            //$param_fill['code'] = strtoupper(uniqid('ORD'.date('-Ymd-')));    //Sinh mã theo cách cũ
            // Tính toán mã đơn hàng theo y/c mới 03/2022
            $service = Service::select(['id', 'code'])->where('id', $param_fill['service_id'])->first();
            $customer = Customer::select(['id', 'fullname', 'country_code'])->where('id', $param_fill['customer_id'])->first();
            if ($service && $customer) {
                $param_fill['code'] = generateOrderCode(
                    $customer->country_code,
                    ($customer->fullname) ? substr(unsigned($customer->fullname), 0, 1) . $customer->id : 'FTB' . $customer->id,
                    $service->code,
                    date('Y-m-d H:i:s')
                );
            } else {
                $param_fill['code'] = strtoupper(uniqid('FTB'));
            }

            $param_fill['status'] = ($param_fill['created_type'] == Constants::ACCOUNT_TYPE_CUSTOMER) ? $params['status'] : Constants::ORDER_STATUS_NEW;
            if($param_fill['status'] == Constants::ORDER_STATUS_NEW){
                $param_fill['sent_sale_at'] = Carbon::now();
                if($param_fill['assigned_sale_id'] > 0){
                    $param_fill['status'] = Constants::ORDER_STATUS_PENDING;
                }
            }
            $param_fill['created_by'] = Auth::id();
            // Deadline = Current + Turn Arround Time
            if ($param_fill['turn_arround_time'] > 0) {
                $param_fill['deadline'] = Carbon::now()->addHour($param_fill['turn_arround_time']);
            }
            // Lấy thông tin QAQC để fill vào luôn từ đầu - 01/11/2021
            $qaqc = User::where('account_type', Constants::ACCOUNT_TYPE_QAQC)->where('status', 1)->first();
            if ($qaqc) {
                $param_fill['assigned_qaqc_id'] = $qaqc->id;
            }

            // Set thời gian tạo
            $param_fill['created_at'] = Carbon::now();

            $order = new Order();
            $order->fill($param_fill);
            $order->save();
            //
            return $order;
        }

        return false;
    }

    /**
     * Hàm cập nhật thông tin đơn hàng
     *
     * @param $params
     * @return false
     */
    public function update($params)
    {
        $param_update = [];
        //
        $id = (isset($params['id']) && $params['id'] > 0) ? $params['id'] : null;
        (isset($params['name']) && $params['name']) ? $param_update['name'] = $params['name'] : null;
        (isset($params['service_id']) && $params['service_id']) ? $param_update['service_id'] = $params['service_id'] : null;
        (isset($params['options']) && $params['options']) ? $param_update['options'] = $params['options'] : null;
        (isset($params['link']) && $params['link']) ? $param_update['link'] = $params['link'] : null;
        (isset($params['email_receiver']) && $params['email_receiver']) ? $param_update['email_receiver'] = $params['email_receiver'] : null;
        (isset($params['turn_arround_time']) && $params['turn_arround_time']) ? $param_update['turn_arround_time'] = $params['turn_arround_time'] : null;
        (isset($params['upload_file']) && $params['upload_file']) ? $param_update['upload_file'] = $params['upload_file'] : null;
        (isset($params['notes']) && $params['notes']) ? $param_update['notes'] = $params['notes'] : null;
        (isset($params['assigned_sale_id']) && $params['assigned_sale_id'] > 0) ? $param_update['assigned_sale_id'] = $params['assigned_sale_id'] : null;
        (isset($params['status']) && $params['status'] > 0) ? $param_update['status'] = $params['status'] : null;
        (isset($params['discount']) && $params['discount'] > 0) ? $param_update['discount'] = $params['discount'] : 0;
        (isset($params['discount_money']) && $params['discount_money'] > 0) ? $param_update['discount_money'] = $params['discount_money'] : 0;
        (isset($params['quantity']) && $params['quantity'] > 0) ? $param_update['quantity'] = $params['quantity'] : 0;
        (isset($params['cost']) && $params['cost'] > 0) ? $param_update['cost'] = $params['cost'] : 0;
        (isset($params['total_payment']) && $params['total_payment'] > 0) ? $param_update['total_payment'] = $params['total_payment'] : 0;
        //
        $order = Order::find($id);
        if ($order) {
            // Deadline = Current + Turn Arround Time
            if (isset($param_update['turn_arround_time']) && $param_update['turn_arround_time'] > 0) {
                $param_update['deadline'] = Carbon::now()->addHour($param_update['turn_arround_time']);
            }
            //
            $order->update($param_update);
            return $order;
        }

        return false;
    }

    /**
     * Hàm thực hiện cập nhật trạng thái xóa và thực hiện xóa mềm
     *
     * @param $params
     * @return bool
     */
    public function delete($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = Order::where('id', $id)->first();

        if ($query) {
            //Nếu là KH và tạo ra order thì mới được xóa order đó
            if (Auth::user()->account_type == Constants::ACCOUNT_TYPE_CUSTOMER && (Auth::id() == $query->created_by)) {
                $query->status = Constants::ORDER_STATUS_DELETED;
                $query->save();
                $query->delete();
                //
                return true;
            } else {
                return Constants::PERMISSION_DENIED;
            }
        }

        return false;
    }

    /**
     * Sale admin cập nhật giao việc cho Sale member
     *
     * @param $params
     * @return bool
     */
    public function updateAssignSale($params)
    {
        $order_id = isset($params['order_id']) ? $params['order_id'] : null;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : null;
        $editor_id = isset($params['editor_id']) ? $params['editor_id'] : null;

        // Gán cho nhiều sale
        if (is_array($order_id) && count($order_id) > 0 && $sale_id > 0) {
            Order::whereIn('id', $order_id)->update([
                'assigned_sale_id' => $sale_id,
                'sent_sale_at' => Carbon::now()
            ]);
            $order = Order::where('id', $order_id[0])->first();
            return $order;
        }

        // Gán cho 1 sale
        if ($order_id > 0 && $sale_id > 0) {
            $order = Order::where('id', $order_id)->first();
            if ($order) {
                $order->assigned_sale_id = $sale_id;
                $order->sent_sale_at = Carbon::now();
                $order->save();
            }

            return $order;
        }

        // Gán cho editor
        if ($order_id > 0 && $editor_id > 0) {
            $order = Order::where('id', $order_id)->first();
            if ($order) {
                $order->assigned_editor_id = $editor_id;
                $order->status = Constants::ORDER_STATUS_EDITING;
                $order->sent_editor_at = Carbon::now();
                $order->save();
            }

            // Ghi log timeline
            LogFollow::create([
                'order_id' => $order_id,
                'before_status' => Constants::ORDER_STATUS_PENDING,
                'after_status' => Constants::ORDER_STATUS_EDITING,
                'receiver_id' => Auth::id(),
                'summary' => 'Admin chuyển tiếp cho Editor',
                'content' => 'Admin chuyển tiếp cho Editor',
            ]);

            return $order;
        }

        return false;
    }

    /**
     * Hàm cập nhật trạng thái gửi yêu cầu order cho Sale
     *
     * @param $params
     * @return bool
     */
    public function sendRequest($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = Order::where('id', $id)->first();

        if ($query) {
            $query->status = Constants::ORDER_STATUS_NEW;
            if(Auth::user()->manager_by > 0){
                $query->assigned_sale_id = Auth::user()->manager_by;
            }
            $query->sent_sale_at = Carbon::now();
            $query->save();
            // Ghi log timeline
            LogFollow::create([
                'order_id' => $id,
                'before_status' => Constants::ORDER_STATUS_DRAFT,
                'after_status' => $query->status,
                'receiver_id' => Auth::id(),
                'summary' => 'Customer gửi yêu cầu',
                'content' => 'Customer gửi yêu cầu',
            ]);
            //
            return true;
        }

        return false;
    }

    /**
     * Hàm thực hiện chuyển tiếp từ Sale cho Admin
     *
     * @param $params
     * @return bool
     */
    public function forwardSaleToAdmin($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $assigned_admin_id = isset($params['assigned_admin_id']) ? $params['assigned_admin_id'] : null;

        $query = Order::where('id', $id)->first();

        if ($query) {
            $query->status = Constants::ORDER_STATUS_PENDING;
            $query->assigned_admin_id = $assigned_admin_id;
            $query->sent_admin_at = Carbon::now();
            $query->save();

            // Ghi log timeline
            LogFollow::create([
                'order_id' => $id,
                'before_status' => Constants::ORDER_STATUS_NEW,
                'after_status' => Constants::ORDER_STATUS_PENDING,
                'receiver_id' => $assigned_admin_id,
                'summary' => 'Sale chuyển tiếp cho Admin',
                'content' => 'Sale chuyển tiếp cho Admin',
            ]);

            //
            return $query;
        }

        return false;
    }

    public function forwardAdminToEditor($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = Order::where('id', $id)->first();

        if ($query) {
            $query->status = Constants::ORDER_STATUS_EDITING;
            $query->sent_sale_at = Carbon::now();
            $query->save();
            // Ghi log timeline
            LogFollow::create([
                'order_id' => $id,
                'summary' => 'Admin chuyển tiếp cho Editor',
                'content' => 'Admin chuyển tiếp cho Editor',
            ]);
            //
            return true;
        }

        return false;
    }

    public function editorEditting($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = Order::where('id', $id)->first();

        if ($query) {
            $query->status = Constants::ORDER_STATUS_NEW;
            $query->sent_sale_at = Carbon::now();
            $query->save();
            // Ghi log timeline
            LogFollow::create([
                'order_id' => $id,
                'summary' => 'Editor đang xử lý yêu cầu',
                'content' => 'Editor đang xử lý yêu cầu',
            ]);
            //
            return true;
        }

        return false;
    }

    public function forwardEditorToQaqc($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = Order::where('id', $id)->first();

        if ($query) {
            $query->status = Constants::ORDER_STATUS_NEW;
            $query->sent_sale_at = Carbon::now();
            $query->save();
            // Ghi log timeline
            LogFollow::create([
                'order_id' => $id,
                'summary' => 'Editor chuyển tiếp cho QaQc',
                'content' => 'Editor chuyển tiếp cho QaQc',
            ]);
            //
            return true;
        }

        return false;
    }

    public function qaqcChecked($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = Order::where('id', $id)->first();

        if ($query) {
            $query->status = Constants::ORDER_STATUS_CHECKED;
            $query->sent_sale_at = Carbon::now();
            $query->save();
            // Ghi log timeline
            LogFollow::create([
                'order_id' => $id,
                // 'before_status' => Constants::ORDER_STATUS_CHECKING,
                'before_status' => Constants::ORDER_STATUS_EDITED,
                'after_status' => $query->status,
                'receiver_id' => Auth::id(),
                'summary' => 'QaQc đã kiểm tra xong, chuyển cho Sale',
                'content' => 'QaQc đã kiểm tra xong, chuyển cho Sale',
            ]);
            //
            return true;
        }

        return false;
    }

    public function forwardSaleToCustomer($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = Order::where('id', $id)->first();

        if ($query) {
            $query->status = Constants::ORDER_STATUS_NEW;
            $query->sent_sale_at = Carbon::now();
            $query->save();
            // Ghi log timeline
            LogFollow::create([
                'order_id' => $id,
                'summary' => 'QaQc đã kiểm tra xong, chuyển cho Sale',
                'content' => 'QaQc đã kiểm tra xong, chuyển cho Sale',
            ]);
            //
            return true;
        }

        return false;
    }

    public function updateStatus($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $query = Order::where('id', $id)->first();

        if ($query) {
            $status = isset($params['status']) ? $params['status'] : $query->status;
            if(isset($params['status'])){
                $data = [
                    'order_id' => $id,
                    'before_status' => $query->status,
                    'after_status' => $status,
                    'receiver_id' => Auth::id(),
                    'summary' => 'Editor đã xử lý xong đơn hàng',
                    'content' => 'Editor đã xử lý xong đơn hàng',
                ];
                switch($params['status']){
                    case Constants::ORDER_STATUS_EDITING:
                        $data['summary'] = 'Editor đang xử lý đơn hàng';
                        $data['content'] = 'Editor đang xử lý đơn hàng';
                        break;
                    case Constants::ORDER_STATUS_EDITED:
                        $data['summary'] = 'Editor đã xử lý xong đơn hàng';
                        $data['content'] = 'Editor đã xử lý xong đơn hàng';
                        //
                        $query->sent_qaqc_at = Carbon::now();
                        break;
                    case Constants::ORDER_STATUS_CHECKING:
                        $data['summary'] = 'QAQC đang kiểm tra đơn hàng';
                        $data['content'] = 'QAQC đang kiểm tra đơn hàng';
                        break;
                    case Constants::ORDER_STATUS_CHECKED:
                        $data['summary'] = 'QAQC đã kiểm tra xong đơn hàng';
                        $data['content'] = 'QAQC đã kiểm tra xong đơn hàng';
                        break;
                    case Constants::ORDER_STATUS_DELIVERING:
                        $data['summary'] = 'Sale đang chuyển giao cho KH';
                        $data['content'] = 'Sale đang chuyển giao cho KH';
                        //
                        $query->delivered_at = Carbon::now();
                        break;
                    case Constants::ORDER_STATUS_COMPLETED:
                        $data['summary'] = 'Sale đã chuyển trạng thái hoàn thành cho đơn hàng';
                        $data['content'] = 'Sale đã chuyển trạng thái hoàn thành cho đơn hàng';
                        break;
                    case Constants::ORDER_STATUS_REDO:
                        $data['summary'] = 'Sale đã yêu cầu QAQC kiểm tra lại đơn hàng';
                        $data['content'] = 'Sale đã yêu cầu QAQC kiểm tra lại đơn hàng';
                        break;
                    case Constants::ORDER_STATUS_AWAITING_PAYMENT:
                        $data['summary'] = 'Sale đã tạo đơn hàng paypal';
                        $data['content'] = 'Sale đã tạo đơn hàng paypal';
                        break;
                }
                // Ghi log timeline
                LogFollow::create($data);
            }
            $query->status = $status;
            $query->save();
            //
            return $query;
        }

        return false;
    }

    public function OrderByCustomer($params)
    {
        $query = Order::where('customer_id', $params['customer_id']);
        $query->where('assigned_sale_id', null);
        $query->where('status', '>', 0);
        return $query->get();
    }


    /**
     * Hàm thực hiện cập nhật review cho đơn hàng
     *
     * @param $params
     * @return bool
     */
    public function reviewSubmit($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $output = Order::find($id);
        Order::where('id', $id)->update([
            'rating' => $params['rating'],
            'review' => $params['review'],
            'reviewed_at' => Carbon::now()
        ]);
        return $output;
    }
}
