<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $table = 'tbl_log';

    protected $fillable = [
        'userId',
        'namaUser',
        'subject',
        'status',
        'url',
        'method',
        'ip',
        'agent'
    ];
}