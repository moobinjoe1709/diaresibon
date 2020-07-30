<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    protected $table = 'tb_remarks';
    protected $primaryKey = 're_id';
    protected $fillable = [
        're_pd_id',
        're_remark',
    ];
    
    public $timestamps = false;
}
