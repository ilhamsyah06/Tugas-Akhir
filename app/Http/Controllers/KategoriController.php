<?php

namespace App\Http\Controllers;

use App\Http\Requests\KategoriSimpanRequest;
use App\Http\Requests\KategoriUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

//model
use App\Kategori;

class KategoriController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.kategori.index');
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
    public function store(KategoriSimpanRequest $request)
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

            $jenis = new Kategori();
            $jenis->nama = $input['nama'];
            $jenis->save();

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
        $jenis = Kategori::find($id);

        return response()->json([
            'id'=>$jenis->id,
            'nama' => $jenis->nama,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KategoriUpdateRequest $request, $id)
    {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $jenis = Kategori::find($id);

                $cari = Kategori::where('nama', $input['nama'])->first();
                if ($cari != null) {
                    if ($jenis->id != $cari->id) {
                        return response()->json([
                            'data' => ['Nama jenis barang sudah digunakan oleh data lainnya!']
                        ], 422);
                    }
                }

                if ($jenis != null) {
                    $hasil = $this->simpanTransaksiUpdate($input, $jenis);
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

    protected function simpanTransaksiUpdate($input, $jenis) {
        DB::beginTransaction();
        try {
            DB::table('kategori')
                ->where('id', $jenis->id)
                ->update(
                    [
                        'nama' => $input['nama'],
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
        $jenis = Kategori::find($id);

        $hasil = $this->simpanTransaksiDelete($jenis);

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

    protected function simpanTransaksiDelete($jenis)
    {
        DB::beginTransaction();

        try {
            $jenis->delete();
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

    public function apiKategori()
    {
 
        $jenis = Kategori::all();
        $cacah = 0;
        $data = [];

        foreach ($jenis as $i => $d) {
        	$data[$cacah] = [
        		$d->nama, 
        		$d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);

    }

    public function levelapi() {
    	$jenis = Kategori::all();
        $cacah = 0;
        $data = [];

        foreach ($jenis as $i => $d) {
        	$data[$cacah] = [
        		$d->nama, 
        		$d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
