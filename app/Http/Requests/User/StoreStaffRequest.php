<?php

namespace App\Http\Requests\User;

use App\Helpers\Constants;
use App\Models\Group;
use App\Models\User;
use App\Rules\MobileRule;
use App\Rules\PasswordRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => [],
            'group_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $data = Group::find($value);
                    if (! $data) {
                        return $fail('Không tìm thấy thông tin nhóm nhân viên');
                    } else {
                        if (in_array($data->code, [Constants::ACCOUNT_TYPE_CUSTOMER, Constants::ACCOUNT_TYPE_SUPER_ADMIN])) {
                            return $fail('Nhóm nhân viên không hợp lệ');
                        }
                    }
                }
            ],
            'is_admin' => 'required|in:0,1',
            'fullname' => 'required',
            'birthday' => 'required|date_format:d/m/Y',
            'gender' => 'required|in:1,2,3',
            'phone' => [
                'required',
                new MobileRule(),
                function ($attribute, $value, $fail) {
                    $data = User::where('phone', formatMobile($value))->first();
                    if ($data) {
                        return $fail(':attribute đã có người sử dụng');
                    }
                }
            ],
            'email' => ['required','email'],
            'password' => [
                'required',
                'confirmed',
                new PasswordRule()
            ],
            'password_confirmation' => ['required'],
            'address' => 'nullable',
            'status' => 'required|in:0,1,2,3',
        ];
    }

    public function  attributes()
    {
        return [
            'group_id' => 'Loại tài khoản',
            'is_admin' => 'Nhân viên/Quản lý',
            'fullname' => 'Họ và tên',
            'phone' => 'Số di động',
            'birthday' => 'Ngày sinh',
            'gender' => 'Giới tính',
            'address' => 'Địa chỉ',
            'email' => 'Email tài khoản',
            'password' => 'Mật khẩu',
            'password_confirmation ' => 'Xác nhận mật khẩu',
            'status ' => 'Trạng thái',
        ];
    }

    public function messages()
    {
        return [
            'password_confirmation.required' => ':attribute bắt buộc nhập'
        ];
    }

    /**
     * @param $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            //
        });
    }

    /**
     * @param Validator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }
}
