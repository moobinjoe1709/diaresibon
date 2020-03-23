<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pallets extends Model
{
    protected $table = 'tb_pallet';
    protected $primaryKey = 'tpl_id';
    protected $fillable = [
                'tpl_mp_id',
                'tpl_num',
                'tpl_bo_id',
                'tpl_pd_id',
                'tpl_qty',
                'tpl_sum_qty'
    ];
    
    // public $timestamps = false;
    
}
