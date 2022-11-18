<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalPoli extends Model
{
    protected $guard = 'jadwal_poli';
    protected $table = 'jadwal_poli';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
    	'id',
    	'hari_buka',
    	'jam_buka',
    	'jam_tutup',
        'kuota',
    ];
}
