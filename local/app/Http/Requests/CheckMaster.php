<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckMaster extends FormRequest
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
            // "customer_name"  => "not_in:0",
            "fg_id"  => "not_in:0",
            "pallet_type"  => "not_in:0",
            // "type"  => "required",
            // "part_no"  => "required",
            // "size_wheel_mm"  => "required",
            // "size_wheel_inch"  => "required",
            // "spec"  => "required",
            // "inner"  => "required",
            // "outer"  => "required",
            // "over_outer"  => "required",
            // "width_cm"  => "required",
            // "length_cm"  => "required",
            // "highth_cm"  => "required",
            // "cbm"  => "required",
            // "net_weight"  => "required",
            // "gross_weight"  => "required",
            // "pallet_size1"  => "not_in:0",
        ];
    }

    public function messages()
    {
        return [
            // 'customer_name.not_in'    => 'กรุณาเลือกข้อมูล CUSTOMER NAME',
            'fg_id.not_in'    => 'กรุณาเลือกข้อมูล Items',
            'pallet_type.not_in'    => 'กรุณาเลือกข้อมูล Type Pallet',
            // 'fg_id.unique'    => 'มี FG/ID  Master นี้อยู่แล้ว',
            // 'type.required'    => 'กรุณาใส่ข้อมูล TYPE',
            // 'part_no.required'    => 'กรุณาใส่ข้อมูล PART NO. / STOCK NO.',
            // 'size_wheel_mm.required'    => 'กรุณาใส่ข้อมูล SIZE WHEEL (MM)',
            // 'size_wheel_inch.required'    => 'กรุณาใส่ข้อมูล SIZE WHEEL (INCH)',
            // 'spec.required'    => 'กรุณาใส่ข้อมูล SPEC',
            // 'inner.required'    => 'กรุณาใส่ข้อมูล INNER (PCS)',
            // 'outer.required'    => 'กรุณาใส่ข้อมูล OUTER (PCS)',
            // 'over_outer.required'    => 'กรุณาใส่ข้อมูล OVER OUTER (PCS)',
            // 'width_cm.required'    => 'กรุณาใส่ข้อมูล WIDTH (CM)',
            // 'length_cm.required'    => 'กรุณาใส่ข้อมูล LENGTH (CM)',
            // 'highth_cm.required'    => 'กรุณาใส่ข้อมูล HIGHTH (CM)',
            // 'cbm.required'    => 'กรุณาใส่ข้อมูล Width CBM (M2)',
            // 'net_weight.required'    => 'กรุณาใส่ข้อมูล Net Weight (Kgs)',
            // 'gross_weight.required'    => 'กรุณาใส่ข้อมูล Gross Weight (Kgs)',
            // 'pallet_size1.not_in'    => 'กรุณาเลือกข้อมูล PALLET SIZE NO.1',
        ];
    }
}
