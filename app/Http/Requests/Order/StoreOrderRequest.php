<?php

namespace App\Http\Requests\Order;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreOrderRequest extends FormRequest
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
            'name' => ['required'],
            'service_id' => ['required'],
            'options' => ['required'],
            'link' => [
                function ($attribute, $value, $fail) {
                    $options = $this->request->get('options');
                    if ($options == 'LINK') {
                        if (! $value) {
                            return $fail('Bắt buộc nhập');
                        }
                    }
                }
            ],
            'turn_arround_time' => [
                'required',
                'integer',
                'min:12'
            ],
            /*'deadline' => [
                'required',
                'date_format:d/m/Y H:i:s'
            ],*/
            'upload_file.*' => [
                'nullable',
                'file',
                'mimes:'.Constants::UPLOAD_MIMES_TYPE,
                'max:'.Constants::UPLOAD_MAX_SIZE
            ],
        ];
    }

    public function attributes()
    {
        return [
            'service_id' => 'Service type',
            'upload_file' => 'File',
        ];
    }

    public function messages()
    {
        return [
            'options.required' => 'Upload Photo or Paste Link is required.',
            //'service_id.required' => 'Bắt buộc chọn',
            //'deadline.required' => 'Bắt buộc nhập',
            //'deadline.date_format' => 'Không đúng định dạng dd/mm/yyyy H:i:s',
            //'upload_file.*.file' => ':attribute không đúng định dạng file',
            //'upload_file.*.required' => 'Bắt buộc nhập',
            'upload_file.*.mimes' => 'File type: '.Constants::UPLOAD_MIMES_TYPE,
            'upload_file.*.max' => 'File max size :max KB',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check sự tồn tại
            /*$customer = Customer::where('uid', Auth::user()->admin_id)->first();
            if (!$customer) {
                $validator->errors()->add('check_exist', 'Không tìm thấy thông tin khách hàng');
            }*/
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }
}
