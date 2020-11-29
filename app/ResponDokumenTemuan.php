<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponDokumenTemuan extends Model
{
    public $table = 'tbl_respon_dokumen_temuan';

    protected $fillable = [
        'revisiTindakLanjutId', 
        'tindakLanjutId', 
        'tglResponDokumenTemuan', 
        'dokumenId',
        'responTindakLanjut'
    ];
}
