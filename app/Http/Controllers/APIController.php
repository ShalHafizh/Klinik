<?php

namespace App\Http\Controllers;

use App\API;
use App\Pasien;
use Illuminate\Http\Request;

class APIController extends Controller {
   
    public function postRegistrasi(Request $request){
    // $var = Pasien::select('id')->get();
    //     ['username'=>$request->username,
    //      'password'=>$request->password,];
    //     if($var == null) {
    //         echo 'Data Pasien belum ada';
    //     }
    //         return $var;
    // }
    echo '{"kode":"100", "Pesan":"Regis Sukses"}';
    }
    
}