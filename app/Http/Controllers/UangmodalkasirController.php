<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UangmodalSimpanRequest;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Jenssegers\Date\Date;
//model
use App\Uang_modal_kasir;

class UangmodalkasirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = Carbon::now()->format('Y-m-d');
        Date::setLocale('id');//id kode untuk indonesia
        $hariini = Date::now()->format('j F Y');
        $uangawal = DB::table('uang_modal_kasir')->where('tanggal', $now)->limit(1)->get();
        return view ('master.modal_kasir.index',['uangawal' => $uangawal, 'hariini' => $hariini]);
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
                $tanggalcari = Uang_modal_kasir::where('tanggal', $input['tanggal'])->first();
                if ($tanggalcari != null) {
                    return response()->json([
                            'data' => ['Tanggal ini sudah digunakan oleh data lainnya!']
                        ], 422);
                }
                $hasil = $this->simpanTransaksiCreate($input);
                if ($hasil == '') {
                    return response()->json([
                        'data' => 'Sukses Menyimpan'
                    ]);
                } else {
                    return response()->json([
                        'data' => ['Gagal menyimpan data Uang modal kasir! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                    ], 422);
                }

            }
        }
    }

    protected function simpanTransaksiCreate($input) {
        
                DB::beginTransaction();

                try {
        
                    $uangmodal = new Uang_modal_kasir();
                    $uangmodal->uang_awal = $input['uangawal'];
                    $uangmodal->uang_akhir = $input['uangawal'] ;
                    $uangmodal->tanggal = $input['tanggal'] ;
                    $uangmodal->user_id = 1 ;
                    $uangmodal->save();

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
        $uangmodal = Uang_modal_kasir::find($id);

        return response()->json([
            'uangawal' => $uangmodal->uang_awal,
            'uangakhir' => $uangmodal->uang_akhir,
            'tanggal' => $uangmodal->tanggal->format('Y-m-d'),
            'userinput' => $uangmodal->user->name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uangmodal = Uang_modal_kasir::find($id);

        return response()->json([
            'id' => $uangmodal->id,
            'uang_awal' => $uangmodal->uang_awal,
            'tanggal' =>  $uangmodal->tanggal->format('Y-m-d'),
            'user_id' => $uangmodal->user->name
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
                $uangmodal = Uang_modal_kasir::find($id);
                $tanggalcari = Uang_modal_kasir::where('tanggal', $input['tanggal'])->first();
                if ($tanggalcari != null) {
                    if ($uangmodal->id != $tanggalcari->id) {
                        return response()->json([
                            'data' => ['Tanggal ini sudah digunakan oleh data lainnya!']
                        ], 422);
                    }
                }
                if ($uangmodal != null) {
                        $hasil = $this->simpanTransaksiUpdate($input, $uangmodal);
                        if ($hasil == '') {
                            return response()->json([
                                'data' => 'Sukses Mengubah Data'
                            ]);
                        } else {
                            return response()->json([
                                'data' => ['Gagal mengubah data Uang modal Kasir! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                        }
                } else {
                    return response()->json([
                        'data' => ['Gagal mengubah data Uang modal kasir! Uang modal Aplikasi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $uangmodal) {
        DB::beginTransaction();
        try {
            $datalamauangakhir = $uangmodal->uang_akhir;
            $databaruakhir = $datalamauangakhir - $uangmodal->uang_awal;
            $uangakhirbaru = $databaruakhir + $input['nominal'];
            $dataubah = [
                'uang_awal' => $input['nominal'],
                'uang_akhir' => $uangakhirbaru,
                'tanggal' => $input['tanggal'],
                'updated_at' => date('Y/m/d H:i:s')
            ];
            DB::table('uang_modal_kasir')
                ->where('id', $uangmodal->id)
                ->update($dataubah);
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
        $uangmodal = Uang_modal_kasir::find($id);
        $hasil = $this->simpanTransaksiDelete($uangmodal);
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

    protected function simpanTransaksiDelete($uangmodal)
    {
        DB::beginTransaction();
        try {
            $uangmodal->delete();
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

    public function apiModalkasir()
    {
        $user = Uang_modal_kasir::all();
        $cacah = 0;
        $data = [];

        

        foreach ($user as $i => $d) {
        	$data[$cacah] = [ 
                $d->id,
                $d->uang_awal,
                $d->uang_akhir,
                $d->tanggal->format('d-m-Y'),
                $d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
