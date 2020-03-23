<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'tb_products';
    protected $primaryKey = 'pd_id';
    protected $fillable = [
                'sales_ccn',
                'mas_loc',
                'promise_date',
                'ship_via',
                'customer',
                'cus_ship_loc',
            ];
    
    // public $timestamps = false;
}
