<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipeDokumen extends Model
{
    
    public $table = 'tbl_tipe_dokumen';

    protected $fillable = [
        'tipeDokumen'
    ];
}
