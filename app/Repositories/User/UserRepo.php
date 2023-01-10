<?php
namespace App\Repositories\User;

use App\Helpers\Constants;
use App\Models\PasswordReset;
use App\Models\Group;
use App\Models\User;
use App\Repositories\BaseRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepo extends BaseRepo
{

    public function __construct()
    {
        //
    }

    /**
     * Hàm lấy ds KH, có tìm kiếm và phân trang
     *
     * @param $params
     * @param false $is_counting
     *
     * @return mixed
     */
    public function listing($params, $is_counting = false)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : null;
        $manager_by = isset($params['manager_by']) ? $params['manager_by'] : -1;
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        $status = isset($params['status']) ? $params['status'] : -1;
        $page_index = isset($params['page_index']) ? $params['page_index'] : 1;
        $page_size = isset($params['page_size']) ? $params['page_size'] : 10;
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : 0;
        $order_by = isset($params['order_by']) ? $params['order_by'] : [];
        //
        $keyword = translateKeyWord($keyword);
        $offset = ($page_index - 1) * $page_size;
        //
        $query = User::select('*');

        $query->when(!empty($keyword), function ($sql) use ($keyword) {
            return $sql->where(function ($sub_sql) use ($keyword) {
                $sub_sql->where('username', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('fullname', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('address', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('email', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('email_paypal', 'LIKE', "%" . $keyword . "%")
                    ->orWhere('phone', 'LIKE', "%" . $keyword . "%");
            });
        });

        $query->where('account_type', '!=', Constants::ACCOUNT_TYPE_CUSTOMER);

        if ($account_type) {
            $query->where('account_type', $account_type);
        }

        // Lọc theo người quản lý Sale nào
        if ($manager_by > 0) {
            $query->where('manager_by', $manager_by);
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
            if ($page_size > 0 && $offset >= 0) {
                $query->take($page_size)->skip($offset);
            }
        }

        // Nếu có nhiều đk sắp xếp thì xử lý
        if (is_array($order_by) && count($order_by) > 0) {
            foreach ($order_by as $order) {
                $query->orderBy($order['field'], $order['direction']);
            }
        } else {
            $query->orderBy('id', 'DESC');
        }

        return $query->get();
    }

    /**
     * Hàm tìm kiếm thông tin user theo id
     *
     * @param $params
     * @return mixed
     */
    public function findById($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $user = User::where('id', $id)->first();
        return $user;
    }

    /**
     * Hàm tìm kiếm thông tin user theo id
     *
     * @param $params
     * @return mixed
     */
    public function findByEmail($email)
    {
        $email = isset($email) ? $email : null;
        $user = User::where('email', $email)->first();
        return $user;
    }

    /**
     * Hàm cập nhật mật khẩu
     *
     * @param $params
     * @return null
     */
    public function changePassword($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $password = isset($params['password']) ? $params['password'] : null;
        $user = User::where('id', $id)->first();

        if ($user && $password) {
            $user->password = Hash::make($password);
            $user->save();
            return $user;
        }

        return null;
    }

    /**
     * Tạo mới thông tin khách hàng
     *
     * @param $params
     * @return mixed
     */
    public function storeCustomer($params)
    {
        $user = User::create([
            'group_id' => 1,
            'account_type' => Constants::ACCOUNT_TYPE_CUSTOMER,
            'username' => isset($params['email']) ? $params['email'] : null,
            'password' => isset($params['password']) ? Hash::make($params['password']) : null,
            'fullname' => isset($params['fullname']) ? $params['fullname'] : null,
            'email_paypal' => isset($params['email_paypal']) ? $params['email_paypal'] : null,
            'email' => isset($params['email']) ? $params['email'] : null,
            'phone' => isset($params['phone']) ? $params['phone'] : null,
            'country_code' => isset($params['country_code']) ? $params['country_code'] : 'FTB',
            'website' => isset($params['website']) ? $params['website'] : null,
            'status' => 0,
            'activation_key' => sha1($params['email'] . time()),
        ]);

        return $user;
    }

    /**
     * Hàm cập nhật mật khẩu
     *
     * @param $params
     * @return null
     */
    public function update($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $user = User::where('id', $id)->first();
        if ($user) {
            $phone = isset($params['phone']) ? $params['phone'] : $user->phone;
            $email_paypal = isset($params['email_paypal']) ? $params['email_paypal'] : $user->email_paypal;
            $address = isset($params['address']) ? $params['address'] : $user->address;
            $birthday = isset($params['birthday']) ? $params['birthday'] : $user->birthday;
            $avatar = isset($params['avatar']) ? $params['avatar'] : $user->avatar;
            $fullname = isset($params['fullname']) ? $params['fullname'] : $user->fullname;
            $password = isset($params['password']) ? Hash::make($params['password']) : $user->password;

            $user->phone = $phone;
            $user->email_paypal = $email_paypal;
            $user->address = $address;
            $user->birthday = $birthday;
            $user->avatar = $avatar;
            $user->fullname = $fullname;
            $user->password = $password;
            $user->save();
            return $user;
        }

        return false;
    }

    /**
     * Hàm lấy ds user theo loại tài khoản
     *
     * @param $params
     * @return mixed
     */
    public function getListingByAccountType($params)
    {
        $account_type = isset($params['account_type']) ? $params['account_type'] : null;
        $selection = isset($params['selection']) ? $params['selection'] : null;
        $is_admin = isset($params['is_admin']) ? $params['is_admin'] : -1;
        $sale_id = isset($params['sale_id']) ? $params['sale_id'] : -1;
        $get_object = isset($params['get_object']) ? $params['get_object'] : false;

        if ($account_type) {
            if ($selection) {
                $query = User::select($selection);
            } else {
                $query = User::select(['id', 'email', 'fullname']);
            }

            $query->where('account_type', $account_type);

            if ($is_admin >= 0) {
                $query->where('is_admin', $is_admin);
            }
            if ($sale_id >= 0) {
                $query->where('manager_by', $sale_id);
            }

            $query->take(50)->skip(0);
            $query->orderBy('email', 'ASC');

            if ($get_object) {
                return $query->first();
            } else {
                return $query->get();
            }
        }

        return null;

    }

    public function createPasswordReset($params)
    {
        $user = PasswordReset::create([
            'email' => $params['email'],
            'token' => $params['token'],
            'created_at' => $params['created_at']
        ]);

        return $user;
    }
    /**
     * Thay đổi trạng thái nhân viên
     *
     * @param $params
     * @return bool
     */
    public function changeStatusStaff($params)
    {
        $status = (isset($params['status']) && in_array($params['status'], [0,1,2,3])) ? $params['status'] : 0;
        $user_id = (isset($params['user_id']) && $params['user_id'] > 0) ? $params['user_id'] : null;

        if ($status >= 0 && $user_id > 0) {
            User::where('id', $user_id)
                ->update(['status' => $status]);

            return true;
        }

        return false;
    }

    public function storeStaff($params)
    {
        $email = (isset($params['email']) && $params['email']) ? $params['email'] : null;
        $password = (isset($params['password']) && $params['password']) ? Hash::make(trim($params['password'])) : null;
        $group_id = isset($params['group_id']) ? $params['group_id'] : null;
        $birthday = ($params['birthday']) ? date('Y-m-d', strtotime(str_replace('/', '-', $params['birthday']))) : null;
        if ($email && $params && $group_id > 0) {
            $group = Group::find($group_id);
            $user = User::create([
                'group_id' => $params['group_id'],
                'account_type' => strtoupper($group->code),
                'email' => $email,
                'username' => $email,
                'password' => $password,
                'fullname' => $params['fullname'],
                'birthday' => $birthday,
                'gender' => $params['gender'],
                'phone' => formatMobile($params['phone']),
                'address' => $params['address'],
                'is_admin' => $params['is_admin'],
                'status' => $params['status'],
                'manager_by' => Auth::id(),
            ]);

            return $user;
        }

        return null;
    }

    /**
     * Câp nhật thông tin nhân viên
     * @param $params
     * @return bool
     */
    public function updateStaff($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $birthday = ($params['birthday']) ? date('Y-m-d', strtotime(str_replace('/', '-', $params['birthday']))) : null;
        if ($id > 0) {
            $staff = User::where('id', $id)->update([
                'fullname' => $params['fullname'],
                'birthday' => $birthday,
                'gender' => $params['gender'],
                'phone' => formatMobile($params['phone']),
                'address' => $params['address']
            ]);

            if ($staff) {
                return true;
            }
        }

        return false;
    }

    /**
     * Backup hàm cũ
     *
     * @param $params
     * @return bool
     */
    public function updateStaffBk($params)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $group_id = isset($params['group_id']) ? $params['group_id'] : null;
        $birthday = ($params['birthday']) ? date('Y-m-d', strtotime(str_replace('/', '-', $params['birthday']))) : null;
        if ($id > 0 && $group_id > 0) {
            $group = Group::find($group_id);
            if ($group) {
                $staff = User::where('id', $id)->update([
                    'group_id' => $params['group_id'],
                    'account_type' => strtoupper($group->code),
                    'fullname' => $params['fullname'],
                    'birthday' => $birthday,
                    'gender' => $params['gender'],
                    'phone' => formatMobile($params['phone']),
                    'address' => $params['address'],
                    'is_admin' => $params['is_admin'],
                ]);

                if ($staff) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Hàm lấy user theo token gửi vòa mail
     *
     * @param $token
     * @return mixed
     */
    public function passwordFindByToken($token)
    {
        $token = isset($token) ? $token : null;
        $pass_reset = PasswordReset::where('token', $token)->first();
        return $pass_reset;
    }
}
