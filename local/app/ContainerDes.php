<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContainerDes extends Model
{
    protected $table = 'tb_container_des';
    protected $primaryKey = 'ctnds_id';
    protected $fillable = [
        'ctnds_ctnd_id',
        'ctnds_key',
        'ctnds_group',
        'ctnds_max',
    ];
    
    public $timestamps = false;
}
