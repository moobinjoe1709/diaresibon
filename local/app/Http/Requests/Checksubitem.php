<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Checksubitem extends FormRequest
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
            "net_weight"  => "required",
            "gross_weight"  => "required",
            "cbm"  => "required",
            "carton_width"  => "required",
            "carton_length"  => "required",
            "carton_height"  => "required",
            "pallet_layer"  => "required",
            "pallet_per_layer"  => "required",
            "type_pallet"  => "not_in:0",
            "pallet"  => "not_in:0",
            "c_id"  => "not_in:0",
            // "item"  => "required",
            // "item"  => "required",
        ];
    }

    public function messages()
    {
        return [
            'net_weight.required'           => 'กรุณาใส่ข้อมูล Net Weight ',
            'gross_weight.required'         => 'กรุณาใส่ข้อมูล Gross Weight',
            'cbm.required'                  => 'กรุณาใส่ข้อมูล CBM',
            'carton_width.required'         => 'กรุณาใส่ข้อมูล Carton Width',
            'carton_length.required'        => 'กรุณาใส่ข้อมูล Carton Length',
            'carton_height.required'        => 'กรุณาใส่ข้อมูล Carton Height',
            'pallet_layer.required'         => 'กรุณาใส่ข้อมูล Pallet Layer',
            'pallet_per_layer.required'     => 'กรุณาใส่ข้อมูล Pallet Per Layer',
            'type_pallet.not_in'            => 'กรุณาเลือกข้อมูล Type Pallet MAX : MIN',
            'pallet.not_in'                 => 'กรุณาเลือกข้อมูล Type Pallet ',
            'c_id.not_in'                 => 'กรุณาเลือกข้อมูล Customer ',
        ];
    }
}
