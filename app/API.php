<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    use Notifiable;
    protected $guard = 'hakakses';
    protected $table = 'hak_akses';

    protected $fillable = [
    	'username',
    	'password',
    ];
}
