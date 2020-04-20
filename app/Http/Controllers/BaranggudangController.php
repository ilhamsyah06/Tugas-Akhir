<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

use Auth;
// model
use App\Barang;
use App\Utility;

class BaranggudangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                $userCari = Barang::where('kode', $input['kode'])->where('status','gudang')->first();
                    if ($userCari != null) {
                            return response()->json([
                                'data' => ['Kode Barang Sudah Digunakan Data Barang Lain !!!']
                            ], 422);
                    }
                $hasil = $this->simpanTransaksiCreate($input);
                if ($hasil == '') {
                    return response()->json([
                        'data' => 'Sukses Menyimpan'
                    ]);
                } else {
                    return response()->json([
                        'data' => ['Gagal menyimpan data barang! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                    ], 422);
                }

            }
        }
    }

    protected function simpanTransaksiCreate($input) {
        
        DB::beginTransaction();

        try {
            $barang = new Barang();
            $barang->kode = $input['kode'];
            $barang->nama_barang = $input['nama'];
            $barang->user_id = Auth::user()->id ;
            $barang->kategori_id = $input['jenis'] ;
            $barang->harga_beli = $input['hargabeli'];
            $barang->harga_jual = $input['hargajual'];
            $barang->profit = $input['profit'];
            $barang->stok = 0;
            $barang->tanggal = $input['tanggal'];
            $barang->status = 'gudang' ;
            $barang->save();

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
        $barang = Barang::find($id);

        return response()->json([
            'kode' => $barang->kode,
            'nama' => $barang->nama_barang,
            'user' => $barang->user->name,
            'kategori' => $barang->kategori->nama,
            'hargabeli' => $barang->harga_beli,
            'stok' => $barang->stok,
            'hargajual' => $barang->harga_jual,
            'profit' => $barang->profit,
            'tanggal' => $barang->tanggal,
            'status' => $barang->status
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
            }  else {
                $barang = Barang::find($id);
                $barangCari = Barang::where('kode', $input['kode'])->where('status','gudang')->first();
                if ($barangCari != null) {
                    if ($barang->id != $barangCari->id) {
                        return response()->json([
                            'data' => ['kode barang ini sudah digunakan oleh data lainnya!']
                        ], 422);
                    }
                }
                if ($barang != null) {
                        $hasil = $this->simpanTransaksiUpdate($input, $barang);
                        if ($hasil == '') {
                            return response()->json([
                                'data' => 'Sukses Mengubah Data'
                            ]);
                        } else {
                            return response()->json([
                                'data' => ['Gagal mengubah data barang! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                        }
                } else {
                    return response()->json([
                        'data' => ['Gagal mengubah data barang! Barang tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $barang) {
        DB::beginTransaction();
        try {
            $kirimstok = $barang->stok ;
            $pengurangan = $kirimstok - $input['stok'];
            $dataubah = [
                'kode' => $input['kode'],
                'nama_barang' => $input['nama'],
                'user_id' => Auth::user()->id,
                'kategori_id' => $input['jenis'],
                'harga_beli' => $input['hargabeli'],
                'harga_jual' => $input['hargajual'],
                'profit' => $input['profit'],
                'stok' => $pengurangan,
                'tanggal' => $input['tanggal'],
                'status' => 'gudang',
                'updated_at' => date('Y/m/d H:i:s')
            ];

            DB::table('barang')->where('id', $barang->id)->update($dataubah);
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
    public function destroy($id)
    {
        //
    }

    public function apiBarangGudang()
    {
        $barang = Barang::where('status', 'gudang')->get();
        $cacah = 0;
        $data = [];

        foreach ($barang as $i => $d) {
        	$data[$cacah] = [
                $d->id,
        		$d->kode, 
        		$d->nama_barang,
        		$d->kategori->nama, 
                $d->stok,
                $d->harga_jual,
                $d->profit,
        		$d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }

}
