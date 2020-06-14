<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

use Auth;
//--model--//
use App\Barang;
use App\Sementara;
use App\Detailpenjualan;
use App\Penjualan;

class SementaraController extends Controller
{

    public function getSementara(Request $request) {
    	if (!$request->ajax()) {
    		return null;
    	}

    	$input = $request->all();

    	$kode = $input['kode'];
    	// dd($kode);
    	$sementaras = Sementara::where('kode', $kode)->get();
    	
        $cacah = 0;
        $data = [];

        foreach ($sementaras as $d) {
        	$barang = $d->barang;
            $data[$cacah] = [$barang->kode, $barang->nama_barang, $d->jumlah, $d->harga, $d->jumlah * $d->harga, $d->id];
            $cacah++;

        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function getSementarapenjualan(Request $request) {
    	if (!$request->ajax()) {
    		return null;
    	}

    	$input = $request->all();

    	$kode = $input['kode'];
    	// dd($kode);
    	$sementaras = Sementara::where('kode', $kode)->get();
    	
        $cacah = 0;
        $data = [];

        foreach ($sementaras as $d) {
        	$barang = $d->barang;
            $data[$cacah] = [$barang->kode, $barang->nama_barang, $d->jumlah, $d->diskon, $d->harga, ($d->jumlah * $d->harga) - ($d->diskon * $d->jumlah), $d->id];
            $cacah++;

        }

        return response()->json([
            'data' => $data
        ]);
    }

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
    public function store(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
            	$barang = Barang::where('kode', '=', $input['barang'], 'and')->first();
            	$sementara = Sementara::where(['kode'=> $input['kode'], 'barang_id'=>$barang->id])->first();

            	if ($sementara != null) {
            		return response()->json([
                                'data' => ['Gagal! Barang Dengan Kode '.$input['barang'].' Sudah Ada Dalam Keranjang, Silahkan Update Qty-nya!']
                            ], 422);	
            	}

            	if ($barang != null) {
            		$hasil = $this->simpanTransaksiCreate($input, $barang);
	                if ($hasil == '') {
	                        return response()->json([
	                                'data' => 'Sukses Menambahkan Detail Pembelian'
	                            ]);
	                    } else {
	                            return response()->json([
	                                'data' => ['Gagal Menambahkan Detail Pembelian! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
	                            ], 422);
	                    }	
            	} else {
                            return response()->json([
                                'data' => ['Gagal Menambahkan Detail Pembelian! Barang tidak ditemukan']
                            ], 422);
                    }
            }
        }
    }

    protected function simpanTransaksiCreate($input, $barang) {
        DB::beginTransaction();
        try {

            $sementara = new Sementara;
            $sementara->barang_id = $barang->id;
            $sementara->harga = $input['harga'];
            $sementara->jumlah = $input['qty'];
            $sementara->kode = $input['kode'];

            $sementara->save();
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

    public function storeJual(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $barang = Barang::where('kode', '=', $input['kodebarang'])->first();
                $sementara = Sementara::where(['kode'=> $input['invoice'], 'barang_id'=>$barang->id])->first();

                if ($sementara != null) {
                    return response()->json([
                                'data' => ['Barang dengan kode '.$input['kodebarang'].' sudah ada di keranjang, Silahkan Update Qty!']
                            ], 422);    
                }
                if ($barang != null) {

                    if ($barang->stok_toko < $input['qty']) {
                        return response()->json([
                                'data' => ['Gagal! Stok barang tidak mencukupi !']
                            ], 422);      
                    }

                    $hasil = $this->simpanTransaksiJualCreate($input, $barang);
                    if ($hasil == '') {
                            return response()->json([
                                    'data' => 'Sukses Menambahkan Detail Penjualan'
                                ]);
                        } else {
                                return response()->json([
                                    'data' => ['Gagal Menambahkan Detail Penjualan! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                                ], 422);
                        }   
                } else {
                            return response()->json([
                                'data' => ['Gagal Menambahkan Detail Penjualan! Barang tidak ditemukan']
                            ], 422);
                    }
            }
        }
    }

    protected function simpanTransaksiJualCreate($input, $barang) {
        DB::beginTransaction();
        try {

            $sementara = new Sementara;
            $sementara->barang_id = $barang->id;
            $sementara->harga = $input['harga'];
            $sementara->jumlah = $input['qty'];
            $sementara->kode = $input['invoice'];
            $sementara->diskon = $input['diskon'];

            $sementara->save();
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
        $sementara = Sementara::find($id);
        $barang = $sementara->barang;

        return response()->json([
            'id' => $sementara->id,
            'barang' => $barang->kode,
            'nama' => $barang->nama_barang,
            'jumlah'=>$sementara->jumlah,
            'stok'=>$barang->stok_toko,
            'harga' => $sementara->harga,
            'total' => $sementara->jumlah * $sementara->harga,
            'diskon' => $sementara->diskon,
        ]);
    }

    public function editkoreksi($id)
    {
        $sementara = Sementara::find($id);
        $barang = $sementara->barang;
        $penjualan = Penjualan::where('no_invoice', $sementara->kode)->first();

        $penjualandetail = Detailpenjualan::where(['penjualan_id'=>$penjualan->id, 'barang_id'=>$barang->id])->first();

            if ($penjualandetail != null) {
                return response()->json([
            'id' => $sementara->id,
            'barang' => $barang->kode,
            'nama' => $barang->nama_barang,
            'jumlah'=>$sementara->jumlah,
            'stok'=>$barang->stok_toko + $penjualandetail->qty,
            'harga' => $sementara->harga,
            'total' => $sementara->jumlah * $sementara->harga,
            'diskon' => $sementara->diskon,
        ]);
            } else {
                return response()->json([
            'id' => $sementara->id,
            'barang' => $barang->kode,
            'nama' => $barang->nama_barang,
            'jumlah'=>$sementara->jumlah,
            'stok'=>$barang->stok_toko,
            'harga' => $sementara->harga,
            'total' => $sementara->jumlah * $sementara->harga,
            'diskon' => $sementara->diskon,
        ]);
            }

        
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
                $sementara = Sementara::find($id);

                if ($sementara != null) {

                    $hasil = $this->simpanTransaksiUpdate($input, $sementara);
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
                        'data' => ['Gagal mengubah data! Data tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $sementara) {
        // dd($input);
        DB::beginTransaction();

        try {
                $dataubah = [
                    'jumlah' => $input['qty'],
                    'harga' => $input['harga'],
                    'updated_at' => date('Y/m/d H:i:s')
                ];
                DB::table('sementara')->where('id', $sementara->id)->update($dataubah);
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

    public function updatetoko(Request $request, $id)
    {
        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $sementara = Sementara::find($id);

                if ($sementara != null) {

                    $hasil = $this->simpanTransaksiUpdateToko($input, $sementara);
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
                        'data' => ['Gagal mengubah data! Data tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdateToko($input, $sementara) {
        // dd($input);
        DB::beginTransaction();

        try {
                $dataubah = [
                    'jumlah' => $input['qty'],
                    'harga' => $input['harga'],
                    'diskon' => $input['diskon'],
                    'updated_at' => date('Y/m/d H:i:s')
                ];
                DB::table('sementara')->where('id', $sementara->id)->update($dataubah);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sementara = Sementara::find($id);

        $hasil = $this->simpanTransaksiDelete($sementara);
        if ($hasil == '') {
            return response()->json([
                'data' => 'Sukses Menghapus Data'
            ]);
        } else {
            return response()->json([
                'data' => ['Gagal Menghapus data! Mungkin data ini sedang digunakan oleh data di tabel lainnya!']
            ], 422);
        }
    }

    protected function simpanTransaksiDelete($sementara)
    {

        DB::beginTransaction();

        try {
            $sementara->delete();
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

}
