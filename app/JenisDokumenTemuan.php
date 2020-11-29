<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisDokumenTemuan extends Model
{
    public $table = 'tbl_jenis_dokumen_temuan';

    protected $fillable = [
        'jenisDokumenTemuan'
    ];
}
