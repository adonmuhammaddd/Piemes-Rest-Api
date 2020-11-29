<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    public $table = 'tbl_satker';

    protected $fillable = ['namaSatker', 'alamat'];
}
