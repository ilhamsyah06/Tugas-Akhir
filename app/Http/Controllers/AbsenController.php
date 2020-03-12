<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

use App\Absen;
use Auth;
use Cache;

class AbsenController extends Controller
{

    public function absenmasuk(Request $request)
    {
        $tanggal = date('Y-m-d');
            if ($request->ajax()) {
                $input = $request->all();
    
                if (!isset($input['_token'])) {
                    return response()->json([
                        'data' => ['Token invalid !!']
                    ]);
                } else {
                    $userCari = Absen::where('tgl_absen', $tanggal)->where('user_id', Auth::user()->id)->first();
                    if ($userCari != null) {
                            return response()->json([
                                'data' => ['Data Absen Sudah Masuk !!!']
                            ], 422);
                    }
                    $hasil = $this->simpanTransaksiCreate($input);
                    if ($hasil == '') {
                        return response()->json([
                            'data' => 'Sukses Menyimpan'
                        ]);
                    } else {
                        return response()->json([
                            'data' => ['Gagal menyimpan data ! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                        ], 422);
                    }
    
                }
            }
        
    }

    protected function simpanTransaksiCreate($input) {
    	DB::beginTransaction();
        try {
            $tanggal = date('Y-m-d');
            $jam = date('H:i:s');
            $absen = new Absen();
            $absen->user_id = Auth::user()->id;
            $absen->tgl_absen = $tanggal;
            $absen->save();
        } catch (ValidationException $ex) {
            DB::rollback();
            return $ex->getMessage();;
        } catch (Exception $ex) {
            DB::rollback();
            return $ex->getMessage();;
        }

        DB::commit();

        return '';
    }

    public function laporan()
    {
        return view ('master.absen.index');
    }
}
