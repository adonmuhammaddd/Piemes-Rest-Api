<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RevisiTindakLanjut extends Model
{
    public $table = 'tbl_revisi_tindak_lanjut';

    protected $fillable = [
        'tindakLanjutId', 
        'tglResponTindakLanjut', 
        'responTindakLanjut', 
        'dokumenId'
    ];
}