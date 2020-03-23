<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checkpallet extends FormRequest
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
            "width_cm"  => "required|numeric",
            "length_cm"  => "required|numeric",
            "hieght_cm"  => "required|numeric",
            "wieght_pallet"  => "required|numeric",
        ];
    }

    public function messages()
    {
        return [
            'width_cm.required'    => 'กรุณาใส่ข้อมูล Width (CM)',
            'width_cm.numeric'    => 'กรุณาใส่ข้อมูล Width (CM) เป็น ตัวเลข เท่านั้น',
            'length_cm.required'    => 'กรุณาใส่ข้อมูล Length (CM)',
            'length_cm.numeric'    => 'กรุณาใส่ข้อมูล Length (CM) เป็น ตัวเลข เท่านั้น',
            'hieght_cm.required'    => 'กรุณาใส่ข้อมูล Hieght (CM)',
            'hieght_cm.numeric'    => 'กรุณาใส่ข้อมูล Hieght (CM) เป็น ตัวเลข เท่านั้น',
            'wieght_pallet.required'    => 'กรุณาใส่ข้อมูล Weight (CM)',
            'wieght_pallet.numeric'    => 'กรุณาใส่ข้อมูล Weight (CM) เป็น ตัวเลข เท่านั้น',
        ];
    }
}
