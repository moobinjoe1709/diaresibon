<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boxs extends Model
{
    protected $table = 'tb_boxs';
    protected $primaryKey = 'bo_id';
    protected $fillable = [
        'bo_pd_id',
        'bo_pdt_id',
        'bo_sale_ccn',
        'bo_mas_loc',
        'bo_promise_date',
        'bo_ship_via',
        'bo_customer',
        'bo_fullfill_from',
        'bo_cus_ship_loc',
        'bo_so',
        'bo_so_line',
        'bo_so_delivery',
        'bo_cus_item',
        'bo_cus_po',
        'bo_item',
        'bo_revision',
        'bo_order_qty_sum',
        'bo_um_scalar',
        'bo_currency',
        'bo_sell_um',
        'bo_total',
        'bo_unit_price',
        'bo_pack_qty',
        'bo_cus_spec',
        'bo_size_mm',
        'bo_pc',
        'bo_unit_box',
        'bo_frangment'
    ];
    
    // public $timestamps = false;
}
