<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordUserRequest;
use App\Repositories\Upload\UploadRepo;
use App\Repositories\User\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected $userRepo;
    protected $uploadRepo;

    public function __construct(UserRepo $userRepo, UploadRepo $uploadRepo)
    {
        $this->middleware('auth');  // Y/c phải login
        //
        $this->userRepo = $userRepo;
        $this->uploadRepo = $uploadRepo;
    }

    /**
     * Hàm show thông tin tài khoản
     * URL: /accounts/profile?id=1
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showProfile(Request $request)
    {
        $id = request('id', null);
        if ($id == Auth::id()) {
            $data['user'] = $this->userRepo->findById(['id' => $id]);
        } else {
            $data['user'] = null;
            $data['danger'] = trans('fotober.common.profile_error_permission');
        }

        return view(Constants::VIEW_PAGE_PATH . '/account/profile', $data);
    }

    /**
     * Hàm show form để thay đổi mật khẩu
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showChangePassword(Request $request)
    {
        $id = request('id', null);
        if ($id == Auth::id()) {
            $data['user'] = $this->userRepo->findById(['id' => $id]);
        } else {
            $data['user'] = null;
            $data['danger'] = trans('fotober.common.profile_error_permission');
        }

        return view(Constants::VIEW_PAGE_PATH . '/account/change-password', $data);
    }

    /**
     * Hàm xử lý đổi mật khẩu
     *
     * @param ChangePasswordUserRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function processChangePassword(ChangePasswordUserRequest $request)
    {
        $params['id'] = request('id', null);
        $params['password'] = request('password', null);

        $data['user'] = $this->userRepo->changePassword($params);
        if ($data['user']) {
            $data['success'] = trans('fotober.account.mess_change_pass_success');
        } else {
            $data['danger'] = trans('fotober.account.mess_change_pass_failed');
        }

        return view(Constants::VIEW_PAGE_PATH . '/account/change-password', $data);
    }

    /**
     * Hiển thị thông tin cập nhật
     * 
     */
    public function editProfile(Request $request)
    {
        $id = request('id', null);
        if ($id == Auth::id()) {
            $data['user'] = $this->userRepo->findById(['id' => $id]);
        } else {
            $data['user'] = null;
            $data['danger'] = trans('fotober.common.profile_error_permission');
        }

        return view(Constants::VIEW_PAGE_PATH . '/account/edit-profile', $data);
    }
    /**
     * Cập nhật thông tin
     * 
     */
    public function updateProfile(Request $request)
    {
        $params['id'] = request('id', null);
        $params['phone'] = request('phone', null);
        $params['address'] = request('address', null);
        $params['email_paypal'] = request('email_paypal', null);
        $params['fullname'] = request('fullname', null);
        $params['birthday'] = request('birthday', null);
        if ($params['birthday']) {
            $params['birthday'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $params['birthday'])));
        }
        $params['phone'] = formatMobile($params['phone']);
        $data['user'] = $this->userRepo->update($params);
        $params['avatar'] = request('avatar', null);
        if ($data['user']) {
            $param_file['id'] = $data['user']->id;
            $param_file['type'] = 'AVATAR';
            $param_file['file'] = ($request->hasFile('avatar')) ? $request->file('avatar') : null;
            if($param_file['file']){
                $path = $this->uploadRepo->updateFile($param_file);
                if($path){
                    $param_upload['id'] = $data['user']->id;
                    $param_upload['avatar'] = $path;
                    $result = $this->userRepo->update($param_upload);
                }
            }
            $data['success'] = trans('fotober.account.mess_update_profile_success');
        } else {
            $data['danger'] = trans('fotober.account.mess_update_profile_failed');
        }

        return redirect()->route('account_edit_profile', ['id' => Auth::id()])->with('data', $data);
    }

}
