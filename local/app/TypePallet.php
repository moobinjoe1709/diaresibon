<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypePallet extends Model
{
    protected $table = 'tb_typepalate';
    protected $primaryKey = 'tp_id';
    protected $fillable = ['tp_weight','tp_width','tp_length','tp_hieght'];
    
    // public $timestamps = false;
}
