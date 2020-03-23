<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_details extends Model
{
    protected $table = 'tb_product_details';
    protected $primaryKey = 'pdt_id';
    protected $fillable = [
                'so',
                'so_line',
                'so_delivery',
                'fullfill_from',
                'cus_item',
                'cus_po',
                'item',
                'revision',
                'ordered_qty',
                'um_scalar',
                'currency',
                'sell_um',
                'unit_price',
                'pack_qty',
                'cus_spec',
                'size_mm',
                'pc',
                'pd_status',
                'total'
            ];
    
    // public $timestamps = false;
}
