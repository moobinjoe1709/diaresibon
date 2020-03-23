<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = 'tb_items';
    protected $primaryKey = 'it_id';
    protected $fillable = [
        'it_name',
        'it_ct_id'
    ];
    
    // public $timestamps = false; 
}
