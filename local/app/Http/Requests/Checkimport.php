<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checkimport extends FormRequest
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
            "upload"  => "required",
        ];
    }

    public function messages()
    {
        return [
            'upload.required'    => 'กรุณาเลือกไฟล์ Import File สินค้า',

        ];
    }
}
