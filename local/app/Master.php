<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    protected $table = 'tb_master';
    protected $primaryKey = 'mt_id';
    protected $fillable = [
        'mt_fg_id',
        'mt_ct_id',
        'mt_pl_id',
    ];
    
    public $timestamps = false;
}
