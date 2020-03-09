<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

use App\User;
use App\Gaji;
use App\NominalGaji;
use App\Utility;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.gaji.index');
    }

    public function getAutoKode() {
        $gaji = DB::table('gaji')
                ->where('nomor_gaji', 'like', 'GA%')
                ->select('nomor_gaji')
                ->orderBy('nomor_gaji', 'desc')
                ->first();

        if ($gaji == null) {
            return response()->json('GA00001'); 
        } else {
            $kembali = str_replace('GA', '', $gaji->nomor_gaji);
            $kembali = (int)$kembali;

            $kembali = Utility::sisipkanNol(++$kembali, 5);

            return response()->json('GA'.$kembali); 
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $nominal = NominalGaji::where('nominal', $input['nominal'])->first();
                $hasil = $this->simpanTransaksiCreate($input, $nominal);
                if ($hasil == '') {
                    return response()->json([
                        'data' => 'Sukses Menyimpan'
                    ]);
                } else {
                    return response()->json([
                        'data' => ['Gagal menyimpan data gaji! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                    ], 422);
                }

            }
        }
    }

    protected function simpanTransaksiCreate($input, $nominal) {
        
                DB::beginTransaction();
        
                try {
        
                    $gaji = new Gaji();
                    $gaji->nomor_gaji = $input['nomorgaji'];
                    $gaji->user_id = $input['user'];
                    $gaji->nominal_id = $nominal->id;
                    $gaji->jumlah_hari_kerja = $input['jumlahhari'];
                    $gaji->total_gaji = $input['totalgaji'];
                    $gaji->tgl_gajian = $input['tanggal'];
                    $gaji->save();

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gaji = Gaji::find($id);

        return response()->json([
            'id' => $gaji->id,
            'nomorgaji' => $gaji->nomor_gaji,
            'jumlahhari' => $gaji->jumlah_hari_kerja,
            'totalgaji' => $gaji->total_gaji,
            'tanggal' => $gaji->tgl_gajian,
            'user' => $gaji->user_id,
            'nominal' => $gaji->nominal->nominal,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $gaji = Gaji::find($id);
                $nominal = NominalGaji::where('nominal', $input['nominal'])->first();
                $gajiCari = Gaji::where('nomor_gaji', $input['nomorgaji'])->where('user_id', $input['user'])->first();
                if ($gajiCari != null) {
                    if ($gaji->id != $gajiCari->id) {
                        return response()->json([
                            'data' => ['Data gaji ini sudah digunakan oleh data lainnya!']
                        ], 422);
                    }
                }
                if ($gaji != null) {
                        $hasil = $this->simpanTransaksiUpdate($input, $gaji, $nominal);
                        if ($hasil == '') {
                            return response()->json([
                                'data' => 'Sukses Mengubah Data'
                            ]);
                        } else {
                            return response()->json([
                                'data' => ['Gagal mengubah data gaji! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                        }
                } else {
                    return response()->json([
                        'data' => ['Gagal mengubah data gaji! gaji Aplikasi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $gaji, $nominal) {
        DB::beginTransaction();
        try {
            $dataubah = [
                'jumlah_hari_kerja' => $input['jumlahhari'],
                'total_gaji' => $input['totalgaji'],
                'tgl_gajian' => $input['tanggal'],
                'user_id' => $input['user'],
                'nominal_id' => $nominal->id,
                'updated_at' => date('Y/m/d H:i:s')
            ];

            DB::table('gaji')->where('id', $gaji->id)->update($dataubah);
        } catch (ValidationException $ex) {
            DB::rollback();
            return $ex->getMessage();
        } catch (Exception $ex) {
            DB::rollback();
            return $ex->getMessage();
        }

        DB::commit();

        return '';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $gaji = Gaji::find($id);

        $hasil = $this->simpanTransaksiDelete($gaji);

        if ($hasil === '') {
            return response()->json([
                'data' => 'Sukses Menghapus Data'
            ]);
        } else {
            return response()->json([
                'data' => ['Gagal Menghapus data! Mungkin data ini sedang digunakan oleh data di tabel lainnya!']
            ], 422);
        }
    }

    protected function simpanTransaksiDelete($gaji)
    {
        DB::beginTransaction();

        try {
            $gaji->delete();
        } catch (ValidationException $ex) {
            DB::rollback();
            return $ex->getMessage();
        } catch (Exception $ex) {
            DB::rollback();
            return $ex->getMessage();
        }

        DB::commit();

        return '';
    }


    public function userapi() {
    	$user = User::all();
        $cacah = 0;
        $data = [];

        foreach ($user as $i => $d) {
        	$data[$cacah] = [
        		$d->name, 
        		$d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function gajiapi() {
    	$gaji = Gaji::all();
        $cacah = 0;
        $data = [];

        foreach ($gaji as $i => $d) {
        	$data[$cacah] = [
                $d->id,
                $d->user->name,
                $d->jumlah_hari_kerja,
                $d->nominal->nominal,
                $d->total_gaji,
                date('d-m-Y', strtotime($d->tgl_gajian)),
        		$d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
