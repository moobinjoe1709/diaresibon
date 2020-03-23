<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubItems extends Model
{
    protected $table = 'tb_subitems';
    protected $primaryKey = 'sit_id';
    protected $fillable = [
        'sit_it_id',
        'sit_netweight',
        'sit_grossweight',
        'sit_cbm',
        'sit_cartonwidth',
        'sit_cartonlenght',
        'sit_cartonheigh',
        'sit_palletvolume',
        'sit_cartonlayer',
        'sit_cartonperlayer',
        'sit_typepallet',
        'sit_pallet'
    ];
    
    // public $timestamps = false;
}
