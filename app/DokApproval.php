<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DokApproval extends Model
{
    public $table = 'document_approval';

    protected $fillable = [
        'idUser',
        'idTindakLanjut',
        'noDokumen',
        'namaDokumen',
        'catatan',
        'tglApproval',
        'fileDokumen',
        'isDeleted'
    ];
}
