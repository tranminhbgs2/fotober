<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Constants;
use App\Http\Controllers\Controller;
use App\Jobs\AccountActivationJob;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\User\UserRepo;
use App\Services\Email\SendMail;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $userRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepo $userRepo)
    {
        $this->middleware('guest')->except('logout');
        $this->userRepo = $userRepo;
    }

    /**
     * Show form login
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        //return view(Constants::VIEW_PAGE_PATH . '.auth.login');
        return view('themes/cms/fotober/pages/auth/login');
    }

    /**
     * Override lại username = username
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    public function status()
    {
        return 'status';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Nếu là phân hệ KH thì set mặc định là tiếng anh
        if (Auth::user()->account_type == Constants::ACCOUNT_TYPE_CUSTOMER) {
            App::setLocale(Constants::LANGUAGE_DEFAULT);
            session()->put('locale', Constants::LANGUAGE_DEFAULT);
        }

        // Điều hướng về trang orders theo quyền
        switch (Auth::user()->account_type) {
            case Constants::ACCOUNT_TYPE_CUSTOMER: return redirect(route('customer_order')); break;
            case Constants::ACCOUNT_TYPE_SALE: return redirect(route('sale_order')); break;
            case Constants::ACCOUNT_TYPE_ADMIN: return redirect(route('admin_order')); break;
            case Constants::ACCOUNT_TYPE_EDITOR: return redirect(route('editor_order')); break;
            case Constants::ACCOUNT_TYPE_QAQC: return redirect(route('qaqc_order')); break;
            case Constants::ACCOUNT_TYPE_SUPER_ADMIN: return redirect(route('superadmin_listing_order')); break;
        }
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate(
            $request,
            [
                $this->username() => [
                    'required',
                    'string',
                    function($attribute, $value, $fail){
                        $user = User::where($this->username(), $value)->first();
                        if ($user) {
                            switch ($user->status) {
                                case 0: $message = trans('fotober.login.status_0'); break;
                                case 2: $message = trans('fotober.login.status_2'); break;
                                case 3: $message = trans('fotober.login.status_3'); break;
                            }
                            if (isset($message)) {
                                return $fail($message);
                            }
                        } else {
                            return $fail(trans('fotober.login.status_404'));
                        }
                    }
                ],
                'password' => 'required|string',
            ],
            [],
            [
                'email' => 'Email',
                'password' => trans('fotober.login.password'),
            ]
        );
    }

    /**
     * Show form forgot password
     *
     */
    public function showForgotPassForm(Request $request)
    {
        //return view(Constants::VIEW_PAGE_PATH . '.auth.forgot-password');
        return view('themes/cms/fotober/pages/auth/forgot-password');
    }

    /**
     * Process forgot password
     *
     */
    public function processForgotPass(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255'
            ],
            [],
            [
                'email' => trans('fotober.register.email'),
            ]
        );
        $email = request('email', null);
        // Tạo thông tin user
        $result = $this->userRepo->findByEmail($email);

        if ($result) {
            $param = ['email' => $result->email, 'token' => Str::random(60), 'created_at' => date('Y-m-d H:i:s')];
            $res = $this->userRepo->createPasswordReset($param);
            if($res){
                $sendgrid = new SendMail();
                $subject = trans('fotober.login.change_the_password');
                $message = trans('fotober.login.access_link').'<br><a href="'. route('change_password_form',['token' => $param['token']]) . '" taget="_blank">'.trans('fotober.login.here').'</a>';
                $result = $sendgrid->sendMail($subject, $result->email, $message);
                if($result){
                    $message = trans('fotober.login.password_change_path');
                } else {
                    
                    $message = 'Send Mail Failded.';
                }
            }
            return redirect()->route('forgot_password_form')->with(['success'=> $message]);
        } else {
            $message = trans('fotober.login.please_try_again_later');
            return redirect()->route('forgot_password_form')->with(['danger'=> $message]);
        }
    }

    public function showChangePassForm(Request $request)
    {
        $data['token'] = request('token', null);
        $data['corect'] = false;
        $password_reset = $this->userRepo->passwordFindByToken($data['token']);
        if($password_reset){
            if (strtotime(date('Y-m-d H:i:s')) <= strtotime(Carbon::parse($password_reset->created_at)->addMinutes(5))) {
                $data['corect'] = true;
            }
        }
        //return view(Constants::VIEW_PAGE_PATH . '.auth.change-password', $data);
        return view('themes/cms/fotober/pages/auth/change-password', $data);
    }

    public function processChangePass(Request $request)
    {
        $token = request('token', null);
        $password = request('password', null);
        $this->validate(
            $request,
            [
                'password' => [
                    'required',
                    'confirmed'
                ],
                'password_confirmation' => [
                    'required',
                ]
            ],
            [],
            [
                'password' => trans('fotober.account.new_pass'),
                'password_confirmation' => trans('fotober.account.confirm_new_pass'),
            ]
        );

        $password_reset = $this->userRepo->passwordFindByToken($token);

        if($password_reset){
            if (strtotime(date('Y-m-d H:i:s')) <= strtotime(Carbon::parse($password_reset->created_at)->addMinutes(5))) {
                $user =$this->userRepo->findByEmail($password_reset->email);
                $updatePasswordUser = $this->userRepo->changePassword(['id' => $user->id, 'password' => $password]);
                $password_reset->where('email', $password_reset->email )->delete();
                $mess = ['success' => trans('fotober.account.mess_change_pass_success')];
            }else {
                // echo $password_reset->email;
                // die();
                $mess = ['danger' => trans('fotober.account.token_have_expired')];
            }
            return redirect()->route('login')->with($mess);
        } else {
            echo $token;
            die();
            $message = trans('fotober.account.the_path_has_expired');
            return redirect()->route('forgot_password_form')->with(['danger'=> $message]);
        }
    }

    /**
     * Link kích hoạt tài khoản từ email
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activation(Request $request, $token)
    {
        $customer = Customer::where('status', 0)->where('activation_key', $token)->first();

        if ($customer) {
            // Chưa kích hoạt
            $customer->status = 1;
            $customer->activated_at = date('Y-m-d H:i:s');
            $customer->save();

            // Gửi mail cho admin biết có KH kích hoạt tài khoản

            return redirect()->route('login_form')->with('success', 'Your account is activated. Please, login & create order.');
        }

        return redirect()->route('login_form')->with('warning', 'Please, login & create order.');
    }
}
