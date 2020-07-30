<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'tb_reporth';
    protected $primaryKey = 'rh_id';
    protected $fillable = [
        'rh_pd_id',
        'rh_sono',
        'rh_customer',
    ];
    
    public $timestamps = false;
}
