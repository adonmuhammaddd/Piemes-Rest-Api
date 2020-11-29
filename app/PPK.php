<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ppk extends Model
{
    public $table = 'tbl_ppk';
    protected $fillable = ['namaPpk', 'satkerId'];
}