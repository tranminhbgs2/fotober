<?php

namespace App\Http\Requests\User;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePasswordUserRequest extends FormRequest
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
                'required'
            ],
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('id', $this->request->get('id'))->first();
                    $check = Hash::check($value, $user->password);
                    if (! $check) {
                        return $fail(trans('fotober.account.mess_incorrect_old_pass'));
                    }
                }
            ],
            'password' => [
                'required',
                'confirmed'
            ],
            'password_confirmation' => [
                'required',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => trans('fotober.account.old_pass'),
            'password' => trans('fotober.account.new_pass'),
            'password_confirmation' => trans('fotober.account.confirm_new_pass'),
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
