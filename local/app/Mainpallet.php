<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mainpallet extends Model
{
    protected $table = 'tb_mainpallet';
    protected $primaryKey = 'mp_id';
    protected $fillable = [
                'mp_pd_id',
                'mp_qty',
                'mp_location',
                'mp_weight',
                'mp_height',
                'mp_pallet_qty',
                'mp_status',
                'mp_layer',
                'mp_status_load'
    ];
    
    // public $timestamps = false;
    
}
