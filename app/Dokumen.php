<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    public $table = 'tbl_dokumen';

    protected $fillable = [
        'dokumenTemuanId', 
        'noUraianTemuan', 
        'uraianTemuan', 
        'rekomendasi', 
        'kodeRekomendasi', 
        'kodeRingkasanTindakLanjut',
        'ringkasanTindakLanjut',
        'statusTindakLanjut', 
        'tindakLanjut', 
        'subNomorRekomendasi', 
        'nomorHeader', 
        'titleHeader', 
        'satkerId', 
        'ppkId', 
        'tindakLanjutId', 
        'userid',
        'nomorDraft',
        'dokumenTindakLanjut',
        'uniqueColumn'
    ];
}
