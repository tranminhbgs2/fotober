<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Constants;
use App\Jobs\AccountActivationJob;
use App\Repositories\Country\CountryRepo;
use App\Repositories\User\UserRepo;
use App\Rules\PasswordRule;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    protected $userRepo;
    protected $countryRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepo $userRepo, CountryRepo $countryRepo)
    {
        $this->middleware('guest');
        $this->userRepo = $userRepo;
        $this->countryRepo = $countryRepo;
    }

    /**
     * Hàm show form đăng ký
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        //return view(getBladeFromPage('/auth/register'));
        $countries = $this->countryRepo->getListing([]);
        return view('themes/cms/fotober/pages/auth/register', ['countries' => $countries]);
    }

    /**
     * Hàm xử lý đăng ký
     *
     * @param Request $request
     * @return mixed
     */
    public function processRegister(Request $request)
    {
        $this->validate(
            $request,
            [
                'fullname' => 'required|string|max:50',
                'country_code' => 'required|string|max:5',
                'website' => 'nullable|url|max:255',
                'phone' => 'nullable|min:9|max:15',
                'email_paypal' => 'nullable|email',
                'email' => 'required|email|max:191|unique:users',
                'password' => [
                    'required',
                    'min:8',
                    'max:32',
                    new PasswordRule(),
                    'confirmed'
                ],
                'password_confirmation' => 'required|min:8',
            ],
            [],
            [
                'email_paypal' => trans('fotober.register.email_paypal'),
                'email' => trans('fotober.register.email'),
                'password' => trans('fotober.register.password'),
                'password_confirmation' => trans('fotober.register.password_confirmation'),
            ]
        );

        // Tạo thông tin user
        $result = $this->userRepo->storeCustomer($request->all());

        if ($result) {
            AccountActivationJob::dispatch(Constants::EMAIL_ACCOUNT_ACTIVATION, [
                'customer_id' => $result->id,
                'url' => route('account_activation_url', ['token' => $result->activation_key])
            ])->onQueue('email_customer');

            $message = trans('fotober.account.mess_created_success');
            return redirect()->route('login_form')->with('success', $message);
        } else {
            $message = trans('fotober.account.mess_created_faild');
            return redirect()->route('register_form')->with('danger', $message);
        }
    }

}
