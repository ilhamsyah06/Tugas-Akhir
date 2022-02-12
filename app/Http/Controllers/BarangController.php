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
use App\Historiharga;
use App\Historipengiriman;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view ('master.barang.index');
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
                $userCari = Barang::where('kode', $input['kode'])->first();
                    if ($userCari != null) {
                            return response()->json([
                                'data' => ['Kode Barang Sudah Digunakan Data Barang Lain !!!']
                            ], 422);
                    }
                $hasil = $this->simpanTransaksiCreatetoko($input);
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
    protected function simpanTransaksiCreatetoko($input) {
        DB::beginTransaction();
        try {
            $barang = new Barang();
            $barang->kode = $input['kode'];
            $barang->nama_barang = $input['nama'];
            $barang->user_id = Auth::user()->id;
            $barang->kategori_id = $input['jenis'];
            $barang->harga_beli = $input['hargabeli'];
            $barang->harga_jual = $input['hargajual'];
            $barang->profit = $input['profit'];
            $barang->tanggal = $input['tanggal'];
            $barang->stok_toko = '0';
            $barang->stok_gudang = '0';
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
            'hargajual' => $barang->harga_jual,
            'profit' => $barang->profit,
            'stoktoko' => $barang->stok_toko,
            'stokgudang' => $barang->stok_gudang,
            'tanggal' => $barang->tanggal,
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
        $barang = Barang::find($id);
        $hargaterakhir = Historiharga::where('barang_id', $id)->first();
        if($hargaterakhir != null){
            $data = $hargaterakhir->harga_terakhir;
        }else{
            $data = null;
        }

        return response()->json([
            'id' => $barang->id,
            'kode' => $barang->kode,
            'nama' => $barang->nama_barang,
            'jenisbarang' => $barang->kategori_id,
            'stoktoko' => $barang->stok_toko,
            'stokgudang' => $barang->stok_gudang,
            'hargabeli' => $barang->harga_beli,
            'hargajual' => $barang->harga_jual,
            'hargaterakhir' => $data,
            'profit' => $barang->profit,
            'tanggal' => $barang->tanggal
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
                $barang = Barang::find($id);
                $barangCari = Barang::where('kode', $input['kode'])->first();
                $historihargacari = Historiharga::where('barang_id', $id)->first();
                if ($barangCari != null) {
                   if ($barang->id != $barangCari->id) {
                        return response()->json([
                            'data' => ['kode barang ini sudah digunakan oleh data lainnya!']
                        ], 422);
                    }
                }
                if ($barang != null) {
                        $hasil = $this->simpanTransaksiUpdate($input, $barang, $historihargacari);
                        if ($hasil == '') {
                            return response()->json([
                                'data' => 'Sukses Mengubah Data'
                            ]);
                        } else {
                            return response()->json([
                                'data' => ['Gagal mengubah data user! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                        }
                } else {
                    return response()->json([
                        'data' => ['Gagal mengubah data user! User Aplikasi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $barang, $historihargacari) {
        DB::beginTransaction();
        try {
            if ($historihargacari != null) {
                if ($historihargacari->harga_naik == $input['hargajual']) {
                    $dataubah = [
                        'kode' => $input['kode'],
                        'nama_barang' => $input['nama'],
                        'user_id' => Auth::user()->id,
                        'kategori_id' => $input['jenis'],
                        'harga_beli' => $input['hargabeli'],
                        'harga_jual' => $input['hargajual'],
                        'profit' => $input['profit'],
                        'stok_toko' => $input['stoktoko'],
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
                    DB::table('barang')->where('id', $barang->id)->update($dataubah); 
                }else{
                    $dataubahhistoriharga = [
                        'harga_terakhir' => $input['hargaterakhir'],
                        'harga_naik' => $input['hargajual'],
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
                    $dataubah = [
                        'kode' => $input['kode'],
                        'nama_barang' => $input['nama'],
                        'user_id' => Auth::user()->id,
                        'kategori_id' => $input['jenis'],
                        'harga_beli' => $input['hargabeli'],
                        'harga_jual' => $input['hargajual'],
                        'profit' => $input['profit'],
                        'stok_toko' => $input['stoktoko'],
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
                    DB::table('histori_harga')->where('barang_id', $historihargacari->barang_id)->update($dataubahhistoriharga);
                    DB::table('barang')->where('id', $barang->id)->update($dataubah); 
                }
            } else {
                $historiharga = new Historiharga();
                $historiharga->barang_id = $barang->id;
                $historiharga->harga_terakhir = $input['hargaterakhir'];
                $historiharga->harga_naik = $input['hargajual'];
                $historiharga->save();

                $dataubah = [
                    'kode' => $input['kode'],
                    'nama_barang' => $input['nama'],
                    'user_id' => Auth::user()->id,
                    'kategori_id' => $input['jenis'],
                    'harga_beli' => $input['hargabeli'],
                    'harga_jual' => $input['hargajual'],
                    'profit' => $input['profit'],
                    'stok_toko' => $input['stoktoko'],
                    'updated_at' => date('Y/m/d H:i:s')
                ];
    
                DB::table('barang')->where('id', $barang->id)->update($dataubah); 
            }
           
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
        $barang = Barang::find($id);

        $hasil = $this->simpanTransaksiDelete($barang);

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

    protected function simpanTransaksiDelete($barang)
    {
        DB::beginTransaction();

        try {
            $barang->delete();
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

    public function apiBarang()
    {
        $barang = Barang::all();
        $cacah = 0;
        $data = [];

        foreach ($barang as $i => $d) {
        	$data[$cacah] = [
                $d->id,
        		$d->kode, 
        		$d->nama_barang,
        		$d->kategori->nama, 
                $d->stok_toko,
                $d->stok_gudang,
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

    public function getAutoKode() {
        $barang = DB::table('barang')
                ->where('kode', 'like', 'BA%')
                ->select('kode')
                ->orderBy('kode', 'desc')
                ->first();

        if ($barang == null) {
            return response()->json('BA00001'); 
        } else {
            $kembali = str_replace('BA', '', $barang->kode);
            $kembali = (int)$kembali;

            $kembali = Utility::sisipkanNol(++$kembali, 5);

            return response()->json('BA'.$kembali); 
        }
    }

    public function barangtokohabis()
    {
        $barang = DB::table('barang')
        ->join('kategori', 'barang.kategori_id', '=', 'kategori.id')
        ->where('stok_gudang', 0 )->get();
        $cacah = 0;
        $data = [];

        foreach ($barang as $i => $d) {
        	$data[$cacah] = [
        		$d->kode, 
        		$d->nama_barang,
        		$d->nama, 
                $d->stok_gudang,
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

    public function kirimstoktoko(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $now = date('Y-m-d');
                $barangcari = Barang::where('id', $input['idbarang'])->first();
                $historicari = Historipengiriman::where('barang_id', $input['idbarang'])->where('tgl_pengiriman', $now)->first();
                
                if($historicari != null) {
                         return response()->json([
                             'data' => ['Barang Sudah Dikirim Hari Ini, Pastikan Pihak Toko Sudah Meng-konfirmasi Data.']
                         ], 422);
                 }

                $hasil = $this->simpanTransaksiKirimStok($input, $barangcari);
                if ($hasil == '') {
                    return response()->json([
                        'data' => 'Sukses Menyimpan',
                    ]);
                } else {
                    return response()->json([
                        'data' => ['Gagal menyimpan data barang! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                    ], 422);
                }

            }
        }
    }

    protected function simpanTransaksiKirimStok($input, $barangcari) {
        
        DB::beginTransaction();

        try {

            $stokgudang = $barangcari->stok_gudang;
            $stoktoko = $barangcari->stok_toko;
            $stokgudangbaru = $stokgudang - $input['stokkirim'];
            //$stoktokobaru = $stoktoko + $input['stokkirim'];
            
            $barang = new Historipengiriman();
            $barang->barang_id = $barangcari->id;
            $barang->stok_dikirim = $input['stokkirim'];
            $barang->tgl_pengiriman = date('Y-m-d');
            $barang->save();

            $dataubah = [
                'stok_gudang' => $stokgudangbaru,
              //'stok_toko' => $stoktokobaru,
                'updated_at' => date('Y/m/d H:i:s')
            ];

            DB::table('barang')->where('id', $barangcari->id)->update($dataubah); 

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


}
