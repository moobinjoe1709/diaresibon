<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'tb_customers';
    protected $primaryKey = 'ct_id';
    protected $fillable = [
        'ct_sales_ccn',
        'ct_name',
        'ct_cus_ship_loc',
    ];
    
    public $timestamps = false;
}
