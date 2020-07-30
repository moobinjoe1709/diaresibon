<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContainerDetial extends Model
{
    protected $table = 'tb_container_detail';
    protected $primaryKey = 'ctnd_id';
    protected $fillable = [
        'ctnd_ctn_id',
        'ctnd_pd_id',
        'ctnd_group',
        'ctnd_type',
        'ctnd_key',
        'ctnd_max',
    ];
    
    // public $timestamps = false;
}
