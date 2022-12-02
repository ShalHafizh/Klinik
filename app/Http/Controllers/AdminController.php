<?php

namespace App\Http\Controllers;

use App\Dokter;
use App\Resepsionist;
use App\Speasialis;
use App\NoAntrian;
use App\Pasien;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class AdminController extends Controller
{
    public function __construct() {
    	$this->middleware('admin');
    }

    public function index() {
            $resepsionist = Resepsionist::select('id')->get()->toArray();
            $dokter = Dokter::select('id')->get()->toArray();

    	return view('admin.index', [
                        'resepsionist' => $resepsionist,
                        'dokter' => $dokter
            ]);
    }

    // resepsionist
    public function adminResepsionist() {
    	$resepsionist = Resepsionist::get()->toArray();
    	$id = Resepsionist::select('id')->get()->last();
            if ($id == null) {
                $id = 1;
            }
    	$id  = substr($id['id'], 4);
    	$id = (int) $id;
    	$id += 1;
    	$id  = "RS" . str_pad($id, 3, "0", STR_PAD_LEFT);
    	// dd($resepsionist);
    	return view('admin.resepsionist.index', ['resepsionist' => $resepsionist, 'id' => $id]);
    }

    public function postAdminResepsionist(Request $request) {
        // dd($request->all());
    	if ($request) {
                $resepsionist = new Resepsionist;
                $resepsionist->id = $request->id;
                $resepsionist->username = $request->username;
                $resepsionist->password = bcrypt($request->password);
                $resepsionist->nama = $request->nama;
                $resepsionist->alamat = $request->alamat;
                $resepsionist->tgl_lahir = $request->tgl_lahir;
                $resepsionist->level = $request->level;
                // photo
                if ($request->hasFile('photo')) {
                    $file       =   $request->file('photo');
                    $fileName   =   date('Y-m-d') . "." . $file->getClientOriginalName();
                    $location   =   public_path('images/'. $fileName);
                    Image::make($file)->resize(128, 128)->save($location);
                    $resepsionist->photo  =  $fileName;
            }else {
                    $fileName       =   'user-resepsionist.jpg';
                    $resepsionist->photo  =  $fileName;
            }

                $resepsionist->save();

            return redirect()->route('adminResepsionist');
      }
    }

    public function updateAdminResepsionist(Request $request, Resepsionist $resepsionist) {

            $data = $resepsionist->find($request->id);
            if ($request->password != $data->password) {
                $data->id = $request->id;
                $data->username = $request->username;
                $data->nama = $request->nama;
                $data->alamat = $request->alamat;
                $data->tgl_lahir = $request->tgl_lahir;
                $data->level = 'resepsionist';
                if($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $fileName   =   date('Y-m-d') . "." . $file->getClientOriginalName();
                    $location   =   public_path('images/'. $fileName);
                    Image::make($file)->resize(128, 128)->save($location);
                    //gambar lama
                    $oldFileName = $data->photo;
                    // dd($oldFileName);
                    //hapus gambar lama
                    File::delete(public_path('images/' . $oldFileName));
                    //gambar baru
                    $data->photo = $fileName;

                }
                $data->password = bcrypt($request->password);
                $data->save();
                return redirect()->back();
            }elseif($request->password == $data->password) {
                $data->id = $request->id;
                $data->username = $request->username;
                $data->password = $request->password;
                $data->nama = $request->nama;
                $data->alamat = $request->alamat;
                $data->tgl_lahir = $request->tgl_lahir;
                $data->level = 'resepsionist';
                if($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $fileName   =   date('Y-m-d') . "-" . $file->getClientOriginalName();
                    $location   =   public_path('images/'. $fileName);
                    Image::make($file)->resize(128, 128)->save($location);
                    //gambar lama
                    $oldFileName = $data->photo;
                    //hapus gambar lama
                    File::delete(public_path('images/' . $oldFileName));
                    //gambar baru
                    $data->photo = $fileName;

                }
                $data->save();
                return redirect()->back();
            }

    }

    public function deleteAdminResepsionist(Request $request, Resepsionist $resepsionist) {
        if($request->ajax()) {
            $data = $resepsionist->find($request->id)->delete();
            return response()->json($data);
        }
    }

    //Dokter
    public function adminDokter() {
        $dokter = Dokter::with('spesialis')->get()->toArray();
        $spesialis = Speasialis::get()->toArray();
        $id = Dokter::select('id')->get()->last();
            if ($id == null) {
                $id = 1;
            }
        $id  = substr($id['id'], 4);
        $id = (int) $id;
        $id += 1;
        $id  = "DK" . str_pad($id, 3, "0", STR_PAD_LEFT);
        return view('admin.dokter.index', ['dokter' => $dokter, 'id' => $id, 'spesialis' => $spesialis]);
    }

    public function postAdminDokter(Request $request) {
        if ($request) {
                $dokter = new Dokter;
                $dokter->id = $request->id;
                $dokter->username = $request->username;
                $dokter->password = bcrypt($request->password);
                $dokter->nama = $request->nama;
                $dokter->alamat = $request->alamat;
                $dokter->spesialis_id = $request->spesialis_id;
                $dokter->tgl_lahir = $request->tgl_lahir;
                $dokter->level = $request->level;
                // gambar
                if ($request->hasFile('photo')) {
                    $file       =   $request->file('photo');
                    $fileName   =   date('Y-m-d') . "." . $file->getClientOriginalName();
                    $location   =   public_path('images/'. $fileName);
                    Image::make($file)->resize(128, 128)->save($location);
                    $dokter->photo  =  $fileName;
                }else {
                        $fileName       =   'user-dokter.jpg';
                        $dokter->photo  =  $fileName;
                }
                $dokter->save();
                return redirect()->route('adminDokter');
        }
    }

    public function addSpesialis(Request $request, Speasialis $spesialis) {
        if($request->ajax())    {
            $data = Speasialis::create($request->all());
            return response()->json($data);
        }
    }

    public function updateAdminDokter(Request $request, Dokter $dokter) {
            $data = $dokter->find($request->id);
            if ($request->password != $data->password) {
                $data->id = $request->id;
                $data->username = $request->username;
                $data->nama = $request->nama;
                $data->alamat = $request->alamat;
                $data->tgl_lahir = $request->tgl_lahir;
                $data->level = 'dokter';
                $data->spesialis_id = $request->spesialis_id;
                $data->password = bcrypt($request->password);
                if($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $fileName   =   date('Y-m-d') . "." . $file->getClientOriginalName();
                    $location   =   public_path('images/'. $fileName);
                    Image::make($file)->resize(128, 128)->save($location);
                    //gambar lama
                    $oldFileName = $data->photo;
                    //hapus gambar lama
                    File::delete(public_path('images/' . $oldFileName));
                    //gambar baru
                    $data->photo = $fileName;

                }
                $data->save();
                return redirect()->back();
            }elseif($request->password == $data->password) {
                $data->id = $request->id;
                $data->username = $request->username;
                $data->password = $request->password;
                $data->nama = $request->nama;
                $data->alamat = $request->alamat;
                $data->tgl_lahir = $request->tgl_lahir;
                $data->spesialis_id = $request->spesialis_id;
                $data->level = 'dokter';
                if($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $fileName   =   date('Y-m-d') . "." . $file->getClientOriginalName();
                    $location   =   public_path('images/'. $fileName);
                    Image::make($file)->resize(128, 128)->save($location);
                    //gambar lama
                    $oldFileName = $data->photo;
                    //hapus gambar lama
                    Storage::delete($oldFileName);
                    //gambar baru
                    $data->photo = $fileName;

                }
                $data->save();
                return redirect()->back();
            }
    }

    public function deleteAdminDokter(Request $request, Dokter $dokter) {
        if($request->ajax()) {
            $data = $dokter->find($request->id)->delete();
            return response()->json($data);
        }
    }

    public function getPasien() {
        $HariIni = Pasien::whereDate('created_at', '=', date('Y-m-d'))->where('status', '=', 'antri')->get();
        $bulan = Pasien::whereMonth('created_at', '=', date('m'))->whereYear('created_at', '=', date('Y'))->get()->toArray();
        $pasien = Pasien::orderBy('created_at', 'desc')->groupBy('nama')->get()->toArray();
        $dokter = Dokter::with('spesialis')->get()->toArray();
    	return view('admin.pasien.index', ['pasien'=> $pasien, 'bulan' => $bulan, 'HariIni' => $HariIni, 'dokter' => $dokter]);
    }

    public function postPendaftaranPasien(Request $request) {
            try {
                DB::beginTransaction();

                $data = Pasien::create($request->all());

                $pasien_id = $this->getLastNoAntrian();

                $pasien = Pasien::whereDate('created_at', '=', date('Y-m-d'))->where('status', '=', 'antri')->get();
                $total = Pasien::where('status', 'selesai')->get()->toArray();
                $bulan = Pasien::where('status', 'selesai')->whereMonth('created_at', '=', date('m'))->whereYear('created_at', '=', date('Y'))->get()->toArray();

                // create no antrian
                $id = NoAntrian::select('id')->get()->last();
                $id=$id['id'];
                if ($id == null) {
                    $id = 1;
                } else {
                    $id = (int) $id;
                    $id += 1;
                }
                $id  = str_pad($id, 3, "0", STR_PAD_LEFT);
                $antrian = NoAntrian::create(["no" => $id, 'pasien_id' => $data['id']]);

                DB::commit();
                return response()->json([
                    "success" => [
                        "data" => $data,
                        "id" => $pasien_id,
                        "id_antrian" => $antrian->id,
                        "no_antrian" => $antrian->no,
                        "pasien_hari_ini" => count($pasien),
                        "total_pasien" => count($total),
                        "total_per_bulan" => count($bulan)
                    ],
                    "errors" => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollback();
                $errors = $e->errorInfo[2];
                return response()->json([
                    "success" => null,
                    "errors" => $errors
                ], 400);
            }
    }

    public function postPasienTerdaftar(Request $request) {
        if($request->ajax()){
            $pasien = Pasien::find($request->id);
            $id = Pasien::select('id')->get()->last();
            if ($id == null) {
                $id = 1;
            }
            $id  = substr($id['id'], 4);
            $id = (int) $id;
            $id += 1;
            $id  = "PS" . str_pad($id, 4, "0", STR_PAD_LEFT);
            $create_pasien = Pasien::create([
                'id' => $id,
                'nama' => $pasien->nama,
                'jenis_kelamin' => $pasien->jenis_kelamin,
                'alamat' => $pasien->alamat,
                'tgl_lahir' => $pasien->tgl_lahir,
                'telp' => $pasien->telp,
                'pekerjaan' => $pasien->pekerjaan,
                'status' => 'antri',
                'layanan_dokter' => $request->dokter_id
            ]);

            // create no antrian
            $id = NoAntrian::select('id')->get()->last();
            $id= $id['id'];
            if ($id == null) {
                $id = 1;
            } else {
                $id = (int) $id;
                $id += 1;
            }
            $id  = str_pad($id, 3, "0", STR_PAD_LEFT);
            $antrian = NoAntrian::create(["no" => $id, 'pasien_id' => $create_pasien['id']]);
            return response()->json($create_pasien);
        }
    }

    public function getHapusPasien(Request $request) {
        if ($request->ajax()) {
            $data = Pasien::find($request->id)->delete();
            $deleteNoAntrian = NoAntrian::where('pasien_id', $request->id)->delete();
            return response()->json($data);
        }
    }

    public function postUpdatePasien(Request $request) {
        if ($request->ajax()) {
            $data = Pasien::find($request->id)->update($request->all());
            return response()->json($data);
        }
    }


}
