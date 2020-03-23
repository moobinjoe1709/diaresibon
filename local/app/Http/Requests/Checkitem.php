<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checkitem extends FormRequest
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
            // "cus_id"  => "not_in:0",
            "item"  => "required|unique:tb_items,it_name",
        ];
    }

    public function messages()
    {
        return [
            'item.required'    => 'กรุณาใส่ข้อมูล ITEM',
            'item.unique'    => 'มี ITEM นี้อยู่แล้ว',
            // 'cus_id.not_in'    => 'กรุณาเลือกข้อมูล CUSTOMER',
        ];
    }
}
