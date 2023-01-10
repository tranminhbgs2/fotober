<?php

namespace App\Repositories\Customer;

use App\Helpers\Constants;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\BaseRepo;
use App\Services\Email\MailerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mockery\Matcher\Contains;

class CustomerRepo extends BaseRepo
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hàm lấy ds KH, có tìm kiếm và phân trang
     *
     * @param $params
     * @param false $is_counting
     *
     * @return mixed
     */
    public function getListing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $manager_by = isset($params['manager_by']) ? $params['manager_by'] : -1;
        $status = isset($params['status']) ? $params['status'] : -1;
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : 0;
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        //
        $query = User::select(['id', 'username', 'fullname', 'avatar', 'email', 'phone', 'email_paypal', 'birthday', 'address', 'last_login','updated_at', 'status', 'manager_by']);

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            $keyword = translateKeyWord($keyword);
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('username', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('fullname', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('address', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('email', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('email_paypal', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('phone', 'LIKE', "%" . $keyword . "%");
            });
        });

        $query->where('account_type', Constants::ACCOUNT_TYPE_CUSTOMER);

        // Nếu là super admin thì lấy tất cả, không phải super admin thì lấy theo quyền quản lý
        if ($account_type != Constants::ACCOUNT_TYPE_SUPER_ADMIN) {
            if ($account_type == Constants::ACCOUNT_TYPE_SALE) {
                // Nếu là sale admin thì lấy tất, sale member thì lấy theo quyền quản lý
                if($is_admin){
                    // Lọc theo người quản lý Sale nào
                    if ($manager_by > 0) {
                        $query->where('manager_by', $manager_by);
                    }
                } else {
                    $query->where('manager_by', Auth::id());
                }
            }
        }

        // Lọc theo trạng thái
        if ($status >= 0) {
            $query->where('status', $status);
        }

        // Nếu là super admin thì lấy tất status, ngược lại không lấy tk đã khóa vĩnh viễn
        if (Auth::user()->account_type != Constants::ACCOUNT_TYPE_SUPER_ADMIN) {
            $query->where('status', '!=', Constants::USER_STATUS_DELETED);
        }

        if ($is_counting) {
            return $query->count();
        } else {
            $offset = ($page_index - 1) * $page_size;
            if ($page_size > 0 && $offset >= 0) {
                $query->take($page_size)->skip($offset);
            }
        }

        $query->orderBy('id', 'DESC');

        return $query->get();
    }

    /**
     * Hàm tạo thông tin Khách hàng
     * @param $params
     * @return bool
     */
    public function store($params)
    {
        $fullname = isset($params['fullname']) ? $params['fullname'] : null;
        $country_code = isset($params['country_code']) ? $params['country_code'] : 'FTB';
        $phone = isset($params['phone']) ? $params['phone'] : null;
        $email = isset($params['email']) ? $params['email'] : null;
        $email_paypal = isset($params['email_paypal']) ? $params['email_paypal'] : null;
        $password = isset($params['password']) ? $params['password'] : $email;
        $website = isset($params['website']) ? $params['website'] : null;
        $address = isset($params['address']) ? $params['address'] : null;
        $notes = isset($params['notes']) ? $params['notes'] : null;
        $birthday = isset($params['birthday']) ? $params['birthday'] : null;

        if ($fullname && $email && $password) {
            $user = new User();

            $user->fill([
                'group_id' => 1,
                'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
                'username' => $email,
                'fullname' => $fullname,
                'birthday' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $birthday))),
                'address' => $address,
                'country_code' => $country_code,
                'website' => $website,
                'email' => $email,
                'email_paypal' => $email_paypal,
                'phone' => formatMobile($phone),
                'password' => Hash::make($password),
                'notes' => $notes,
                'manager_by' => (Auth::user()->account_type == Constants::ACCOUNT_TYPE_SALE && !Auth::user()->is_admin) ? Auth::id() : null,
                'status' => Constants::USER_STATUS_ACTIVE
            ]);
            if ($user->save()) {
                return true;
            }

        }

        return false;
    }

    /**
     * Hàm lấy chi tiết thông tin KH
     *
     * @param $params
     * @return Customer|\Illuminate\Database\Eloquent\Model|null
     */
    public function getDetail($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;

        if ($id) {
            return User::where('id', $id)->first();
        } else {
            return null;
        }
    }

    /**
     * Hàm gán quyền quản lý customer
     *
     */
    public function updateAssignSale($params)
    {
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : null;

        if ($customer_id > 0 && $sale_id > 0) {
            User::where('id', $customer_id)->update(['manager_by' => $sale_id]);
            return true;
        }

        return false;
    }

    /**
     * Hàm cập nhật thông tin cá nhân
     *
     * @param $params
     * @param $user_obj
     * @return false
     */
    public function updateInfo($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $params_update['fullname'] = isset($params['fullname']) ? $params['fullname'] : null;
        $params_update['country_code'] = isset($params['country_code']) ? $params['country_code'] : 'FTB';
        $params_update['phone'] = isset($params['phone']) ? $params['phone'] : null;
        $params_update['email'] = isset($params['email']) ? $params['email'] : null;
        $params_update['email_paypal'] = isset($params['email_paypal']) ? $params['email_paypal'] : null;
        $pass = isset($params['password']) ? ( $params_update['password'] =  $params['password']) : null;
        $params_update['website'] = isset($params['website']) ? $params['website'] : null;
        $params_update['address'] = isset($params['address']) ? $params['address'] : null;
        $params_update['notes'] = isset($params['notes']) ? $params['notes'] : null;
        $params_update['birthday'] = isset($params['birthday']) ? $params['birthday'] : null;

        $user = User::find($id);
        if ($user) {
            $user->update($params_update);
            //
            return $user;
        }

        return false;
    }

    /**
     * Hàm xóa user
     *
     */
    public function delete($params)
    {
        $user = User::find($params['id']);
        if ($user) {
            $user->update(['status' => Constants::USER_STATUS_DELETED, 'deleted_at' => date('Y-m-d H:i:s H:i:S')]);
            //
            return $user;
        }

        return false;
    }
    /**
     * Hàm thay đổi mật khẩu
     *
     * @param $params
     * @param $user_obj
     * @return false
     */
    public function changePassword($params, $user_obj)
    {
        $password = isset($params['password']) ? $params['password'] : null;

        if (is_object($user_obj) && $user_obj && $password) {
            $user_obj->password = Hash::make($password);

            if ($user_obj->save()) {
                return $user_obj;
            }
        }

        return false;
    }

    /**
     * Thay đổi trạng thái khách hàng
     * @param $params
     * @return bool
     */
    public function changeStatus($params)
    {
        $status = (isset($params['status']) && in_array($params['status'], [0,1,2,3])) ? $params['status'] : 0;
        $customer_id = (isset($params['customer_id']) && $params['customer_id'] > 0) ? $params['customer_id'] : null;
        $account_type = (isset($params['account_type']) && $params['account_type']) ? $params['account_type'] : null;

        if ($status >= 0 && $customer_id > 0 && $account_type) {
            User::where('id', $customer_id)
                ->where('account_type', $account_type)
                ->update(['status' => $status]);

            return true;
        }

        return false;
    }


}
