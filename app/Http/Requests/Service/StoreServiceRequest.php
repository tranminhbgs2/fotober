<?php

namespace App\Http\Requests\Service;

use App\Helpers\Constants;
use App\Rules\UpperCaseCodeRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreServiceRequest extends FormRequest
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
            'name' => 'required|unique:'.Constants::TABLE_SERVICES,
            'code' => [
                'required',
                'unique:'.Constants::TABLE_SERVICES,
                new UpperCaseCodeRule()
            ],
            'from_price' => 'required|numeric|min:0|max:1000000',
            'group_code' => 'required',
            'type' => 'required',
            'image' => 'nullable',
            'before_photo' => 'nullable',
            'after_photo' => 'nullable',
            'video_link' => 'nullable',
            'read_more' => 'required|url',
            'description' => 'required',
            'status' => 'required|in:0,1,2',
            'sort' => 'required|numeric|min:1|max:1000',
        ];
    }

    public function  attributes()
    {
        return [
            'name' => 'Tên dịch vụ',
            'code' => 'Mã dịch vụ',
            'from_price' => 'Giá từ',
            'group_code' => 'Nhóm dịch vụ',
            'before_photo' => 'Ảnh trước khi sửa',
            'after_photo' => 'Ảnh sau khi sửa',
            'read_more' => 'Link đọc thêm',
            'image' => 'Ảnh đại diện',
            'description' => 'Mô tả',
            'status ' => 'Trạng thái',
            'sort ' => 'Số thứ tự sắp xếp',
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
            $type = $this->request->get('type');
            if ($type && $type == Constants::SERVICE_TYPE_ONLY_VIDEO) {
                if (strpos($this->request->get('video_link'), Constants::VIMEO_EMBED_BASE_URL) === false) {
                    $validator->errors()->add('video_link', 'Link Vimeo không hợp lệ.');
                }
            }
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
