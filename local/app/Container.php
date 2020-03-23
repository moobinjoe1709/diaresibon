<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $table = 'tb_containers';
    protected $primaryKey = 'ctn_id';
    protected $fillable = [
        'ctn_type',
        'ctn_over_kk',
        'ctn_number',
        'ctn_pallet',
        
    ];
    
    // public $timestamps = false;
}
