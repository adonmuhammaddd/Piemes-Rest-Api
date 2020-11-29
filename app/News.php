<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    
    public $table = 'tbl_news';

    protected $fillable = [
        'title',
        'body',
        'bgImage',
        'userId',
        'satkerId',
        'isActive'
    ];
}
