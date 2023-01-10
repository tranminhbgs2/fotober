<?php

namespace App\Http\Requests\User;

use App\Models\Group;
use App\Models\User;
use App\Rules\MobileRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UpdateStaffRequest extends FormRequest
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
            'id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $data = User::find($value);
                    if (! $data) {
                        return $fail('Không tìm thấy thông tin nhân viên');
                    }
                }
            ],
            /*'group_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $data = Group::find($value);
                    if (! $data) {
                        return $fail('Không tìm thấy thông tin nhóm nhân viên');
                    }
                }
            ],*/
            //'is_admin' => 'required|in:0,1',
            'fullname' => 'required',
            'birthday' => 'required|date_format:d/m/Y',
            'gender' => 'required|in:1,2,3',
            'phone' => [
                'required',
                new MobileRule(),
                function ($attribute, $value, $fail) {
                    $data = User::where('phone', formatMobile($value))
                        ->whereNotIn('id', [$this->request->get('id')])
                        ->first();

                    if ($data) {
                        return $fail(':attribute đã có người sử dụng');
                    }
                }
            ],
            'address' => 'nullable',
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
        return [];
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
