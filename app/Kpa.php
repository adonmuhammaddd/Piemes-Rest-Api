<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Kpa extends Model Implements JWTSubject
{
    use Notifiable;

    public $table = 'tbl_kpa';

    protected $fillable = ['namaKpa', 'satkerId'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
