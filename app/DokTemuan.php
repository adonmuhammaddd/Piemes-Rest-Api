<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class DokTemuan extends Model implements JWTSubject
{
    protected $table = 'tbl_dokumen_temuan';

    protected $fillable = [
        'jenisDokumenTemuanId', 
        'deadlineDokumenTemuan', 
        'tglTerimaDokumenTemuan',
        'keadaanSdBulan', 
        'namaKegiatan', 
        'namaInstansi',
        'unitKerjaEselon1',
        'satkerId',
        'noLHA',
        'tglLHA',
        'header',
        'footer'
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
