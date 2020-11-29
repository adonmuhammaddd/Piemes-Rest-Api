<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class TindakLanjut extends Model implements JWTSubject
{
    public $table = 'tbl_tindak_lanjut';

    protected $fillable = [
        'satkerId', 
        'dokumenTemuanId', 
        'tglTindakLanjut', 
        'ppkId', 
        'userId', 
        'nomorDraft',
        'dokumenTindakLanjut',
        'uniqueColumn',
        'tglResponTindakLanjut',
        'responTindakLanjut',
        'isRevisi'

    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}