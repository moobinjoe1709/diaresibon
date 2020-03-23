<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContainerDetial extends Model
{
    protected $table = 'tb_container_detail';
    protected $primaryKey = 'ctnd_id';
    protected $fillable = [
        'ctnd_ctn_id',
        'ctnd_mp_id',
        'ctnd_pallet_qty',
        'remark',
    ];
    
    // public $timestamps = false;
}
