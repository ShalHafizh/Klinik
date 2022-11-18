<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    protected $guard = 'jadwal_dokter';
    protected $table = 'jadwal_dokter';

    protected $fillable = [
    	'dokter_id',
    	'jadwal_poli_id',
    ];

    public function dokter() {
    	return $this->belongsTo('App\Dokter');
    }

    public function poli() {
    	return $this->belongsTo('App\Poli');
    }
}
