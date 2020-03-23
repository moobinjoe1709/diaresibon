<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckCustomer extends FormRequest
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
            "customer"  => "required|unique:tb_customers,ct_name".$this->name_customer_edit,
            "sales_ccn"  => "required",
            "cus_ship_loc"  => "required",
        ];
    }

    public function messages()
    {
        return [
            'customer.required'    => 'กรุณาใส่ข้อมูล CUSTOMER NAME ',
            'sales_ccn.required'    => 'กรุณาใส่ข้อมูล SALES CCN',
            'cus_ship_loc.required'    => 'กรุณาใส่ข้อมูล CUS SHIP LOC',
            'customer.unique'    => 'มีชื่อลูกค้านี้อยู่แล้ว',
        ];
    }
}
