<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NominalGaji;
use DB;
use Exception;
use Illuminate\Validation\ValidationException;

class NominalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('master.nominal.index');
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
                    'data' => ['Token invalid !!']
                ]);

            } else {

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

            $nominal = new NominalGaji();
            $nominal->nominal = $input['nominal'];
            $nominal->save();

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
        $nominal = NominalGaji::find($id);

        return response()->json([
            'id'=>$nominal->id,
            'nominal' => $nominal->nominal,
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
                $nominal = NominalGaji::find($id);

                $cari = NominalGaji::where('nominal', $input['nominalubah'])->first();
                if ($cari != null) {
                    if ($nominal->id != $cari->id) {
                        return response()->json([
                            'data' => ['Nama Nominal Gaji sudah digunakan oleh data lainnya!']
                        ], 422);
                    }
                }

                if ($nominal != null) {
                    $hasil = $this->simpanTransaksiUpdate($input, $nominal);
                    if ($hasil == '') {
                        return response()->json([
                            'data' => 'Sukses Mengubah Data'
                        ]);
                    } else {
                        return response()->json([
                            'data' => ['Gagal mengubah data! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                        ], 422);
                    }
                } else {
                    return response()->json([
                        'data' => ['Gagal mengubah data! Data jenis barang tidak ditemukan di database !']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $nominal) {
        DB::beginTransaction();

        try {

            DB::table('nominal_gaji')
                ->where('id', $nominal->id)
                ->update(
                    [
                        'nominal' => $input['nominalubah'],
                        'updated_at' => date('Y/m/d H:i:s')
                    ]);
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
        $nominal = NominalGaji::find($id);

        $hasil = $this->simpanTransaksiDelete($nominal);

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

    protected function simpanTransaksiDelete($nominal)
    {
        DB::beginTransaction();

        try {
            $nominal->delete();
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

    public function nominalapi()
    {
        $nominal = NominalGaji::all();
        $cacah = 0;
        $data = [];

        foreach ($nominal as $i => $d) {
        	$data[$cacah] = [
        		$d->nominal, 
        		$d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
