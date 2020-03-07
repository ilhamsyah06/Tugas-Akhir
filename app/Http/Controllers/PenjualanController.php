<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;
use Auth;
use Carbon\Carbon;

use App\Utility;
use App\Barang;
use App\Sementara;
use App\Penjualan;
use App\History;
use App\Detailpenjualan;
use App\Uang_modal_kasir;


class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        DB::table('sementara')->truncate();
        return view ('master.penjualan.index');
    }
    
    public function listpenjualan()
    {
        return view('master.penjualan.listpenjualan');
    }

    public function datapenjualan(Request $request) {
        if (!$request->ajax()) {
            return response()->json([
                'data' => []
            ]);
        }


        $input = $request->all();

        $start = $input['start'];
        $end = $input['end'];

        $data = [];
        $cacah = 0;

        if ($start == '' || $end == '') {
            $penjualan = Penjualan::all();
        } else {
            $arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0]. ' 23:59:59';

            $penjualan = Penjualan::whereBetween('tgl_penjualan',[$from, $to], 'and')->get();

            // dd($from.', to : '.$to.', penjualan : '.$penjualan);
        }     

        foreach ($penjualan as $i => $d) {
            $data[$cacah] = [
                $d->no_invoice, 
                $d->created_at->format('d-m-Y H:i:s'), 
                $d->total_bayar,
                $d->user->name,
                $d->id
            ];

            $cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function getpenjualanautocode() {
        $penjualan = DB::table('penjualan')
                ->where('no_invoice', 'like', 'PJ-%')
                ->select('no_invoice')
                ->orderBy('no_invoice', 'desc')
                ->first();

        if ($penjualan == null) {
            return response()->json('PJ-00000001'); 
        } else {
            $kembali = str_replace('PJ-', '', $penjualan->no_invoice);
            $kembali = (int)$kembali;

            $kembali = Utility::sisipkanNol(++$kembali, 8);

            return response()->json('PJ-'.$kembali); 
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
    public function store(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();

            // dd($input);

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $tanggal = date('Y-m-d');
                $uangkasir = DB::table('uang_modal_kasir')->select('uang_akhir')->where('tanggal', $tanggal)->first();
                $sementara = Sementara::where('kode', $input['kode'])->get();
                $uangmodalCari = Uang_modal_kasir::where('tanggal', $tanggal)->first();
                if ($uangmodalCari === null) {
                        return response()->json([
                            'data' => ['Silahkan Isi Uang Modal Kasir Agar Bisa Melakukan Transaksi!']
                        ], 422);
                }

                if ($sementara != null) {

                    $hasil = $this->simpanTransaksiCreate($input, $sementara, $uangkasir);
                    if ($hasil == '') {
                        return response()->json([
                                'data' => 'Sukses menyimpan penjualan barang'
                            ]);
                    } else {
                        dd($hasil);
                            return response()->json([
                                'data' => ['Gagal menyimpan! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                    }
                    
                } else {
                    return response()->json([
                        'data' => ['Gagal menyimpan! Data transaksi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiCreate($input, $sementara, $uangkasir) {
        DB::beginTransaction();
        try {
            
           // dd(Auth::user());
            $penjualan = new Penjualan;
            $penjualan->no_invoice = $input['kode'];
            $penjualan->jumlah_bayar = $input['bayar'];
            $penjualan->user_id = Auth::user()->id;
            $penjualan->total_bayar = $input['totalbayar'];
            $penjualan->kembalian = $input['kembalian'];
            $arr_tgl = explode ("/", $input['tgl'], 3);
            $datatgl = Carbon::now();
            $datatgl = $datatgl->setDate((int)$arr_tgl[2],(int)$arr_tgl[1],(int)$arr_tgl[0]);
            $penjualan->tgl_penjualan= $datatgl;
            $penjualan->save();

            foreach ($sementara as $key => $value) {
                $penjualandetail = new Detailpenjualan;
                $penjualandetail->penjualan_id = $penjualan->id;
                $barang = $value->barang;

                $penjualandetail->barang_id = $barang->id;
                $penjualandetail->harga = $value->harga;
                $penjualandetail->harga_beli = $barang->harga_beli;
                $penjualandetail->qty = $value->jumlah;
                $penjualandetail->diskon_item = $value->diskon;
                $penjualandetail->total = $value->jumlah * $value->harga;
                $penjualandetail->save();

                $stok_sebelumnya = $barang->stok;

                $dataubah = [
                    'stok' => $barang->stok - $value->jumlah,
                    'updated_at' => date('Y/m/d H:i:s')
                ];

                DB::table('barang')
                    ->where('id', $barang->id)
                    ->update($dataubah);

                $history = new History;
                $history->nama = 'penjualan';
                $history->kode = $penjualan->no_invoice;
                $history->tgl = Carbon::now();
                $history->barang_id = $barang->id;
                $history->stok = $stok_sebelumnya;
                $history->masuk = 0;
                $history->keluar = $value->jumlah;
                $history->saldo = $stok_sebelumnya - $value->jumlah;
                $history->user_id = $penjualan->user_id;
                $history->keterangan = 'Penjualan Barang, No. Bukti : '.$penjualan->no_invoice;
                $history->save();

                $dataubahuangmodal = [
                    'uang_akhir' => $input['totalbayar'] + $uangkasir->uang_akhir
                ];

                DB::table('uang_modal_kasir')
                    ->where('tanggal', date('Y-m-d'))
                    ->update($dataubahuangmodal);
             
            }

            DB::table('sementara')->truncate();
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
        $penjualan = Penjualan::find($id);
        if ($penjualan == null) {
            return redirect('/penjualan');
        }

        return view('master.penjualan.tampil_detail', compact('penjualan'));
    }

    public function getdetailpenjualan(Request $request)
    {
        if (!$request->ajax()) {
            return null;
        }

        $kode = $request->all()['kode'];
        // dd($kode);
        $penjualan = Penjualan::where('no_invoice', $kode)->first();
        
        $cacah = 0;
        $data = [];

        if ($penjualan != null) {
            foreach ($penjualan->penjualandetail as $i => $d) {
                $barang = $d->barang;
                $data[$cacah] = [$barang->kode, $barang->nama_barang, $barang->kategori->nama, $d->harga, $d->qty, $d->qty * $d->harga];
                $cacah++;

            }

        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function siapkanKoreksi(Request $request) {

        if ($request->ajax()) {
            $input = $request->all();

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $penjualan = Penjualan::find($input['id']);

                if ($penjualan != null) {
                    $hasil = $this->memprosesKoreksi($penjualan);
                    if ($hasil == '') {
                        return response()->json([
                                'data' => 'Sukses menyiapkan data koreksi'
                            ]);
                    } else {
                            return response()->json([
                                'data' => ['Gagal menyiapkan data koreksi! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                    }
                    
                } else {
                    return response()->json([
                        'data' => ['Gagal menyiapkan data koreksi! Transaksi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function memprosesKoreksi($penjualan) {
        DB::beginTransaction();

        try {
            DB::table('sementara')->truncate();

            foreach ($penjualan->penjualandetail as $key => $value) {
                $sementara = new Sementara;
                $sementara->kode = $penjualan->no_invoice;
                $sementara->barang_id = $value->barang_id;
                $sementara->harga = $value->harga;
                $sementara->diskon = $value->diskon_item;
                $sementara->jumlah = $value->qty;

                $sementara->save();
            }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penjualan = Penjualan::find($id);
        if ($penjualan == null) {
            return redirect('/penjualan');
        }

        return view('master.penjualan.edit', compact('penjualan'));
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
                $tanggal = date('Y-m-d');
                $uangmodal = Uang_modal_kasir::where('tanggal', $tanggal)->first();
                $sementara = Sementara::where('kode', $input['kode'])->get();
                $penjualan = Penjualan::where('no_invoice', $input['kode'])->first();
                if ($uangmodal === null) {
                    return response()->json([
                        'data' => ['Silahkan Isi Uang Modal Kasir Agar Bisa Melakukan Transaksi!']
                    ], 422);
            }

                if ($sementara != null && $penjualan != null) {
                    $hasil = $this->simpanTransaksiUpdate($input, $sementara, $penjualan, $uangmodal);
                    if ($hasil == '') {
                        return response()->json([
                                'data' => 'Sukses koreksi penjualan barang'
                            ]);
                    } else {
                        dd($hasil);
                            return response()->json([
                                'data' => ['Gagal koreksi transaksi penjualan! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                    }
                    
                } else {
                    return response()->json([
                        'data' => ['Gagal  koreksi transaksi penjualan! Data transaksi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $sementara, $penjualan, $uangmodal) {
        DB::beginTransaction();
    try {


            foreach ($penjualan->penjualandetail as $key => $value) {
                $barang = $value->barang;

                $dataubah = [
                    'stok' => $barang->stok + $value->qty
                ];

                DB::table('barang')
                    ->where('id', $barang->id)
                    ->update($dataubah);

                $dataubahuangmodal = [
                        'uang_akhir' => $uangmodal->uang_akhir - $penjualan->total_bayar
                ];

                $now = date('Y-m-d');

                DB::table('uang_modal_kasir')
                    ->where('tanggal', $now)
                    ->update($dataubahuangmodal);

                $value->delete();

                $historylama = History::where(['barang_id' => $barang->id, 'kode' => $penjualan->no_invoice, 'nama' => 'penjualan'])->first();

                if ($historylama != null) {
                    $historylama->delete();
                }
            }

            foreach ($sementara as $key => $value) {
                $penjualandetail = new Detailpenjualan;
                $penjualandetail->penjualan_id = $penjualan->id;
                $barang = $value->barang;

                $penjualandetail->barang_id = $barang->id;
                $penjualandetail->harga = $value->harga;
                $penjualandetail->harga_beli = $barang->harga_beli;
                $penjualandetail->qty = $value->jumlah;
                $penjualandetail->diskon_item = $value->diskon;
                $penjualandetail->total = $value->jumlah * $value->harga;
                $penjualandetail->save();

                $stok_sebelumnya = $barang->stok;

                $dataubah = [
                    'stok' => $barang->stok - $value->jumlah,
                    'updated_at' => date('Y/m/d H:i:s')
                ];

                DB::table('barang')
                    ->where('id', $barang->id)
                    ->update($dataubah);

                $history = new History;
                $history->nama = 'penjualan';
                $history->kode = $penjualan->no_invoice;
                $history->tgl = Carbon::now();
                $history->barang_id = $barang->id;
                $history->stok = $stok_sebelumnya;
                $history->masuk = 0;
                $history->keluar = $value->jumlah;
                $history->saldo = $stok_sebelumnya - $value->jumlah;
                $history->user_id = $penjualan->user_id;
                $history->keterangan = 'Penjualan Barang, No. Bukti : '.$penjualan->no_invoice;
                $history->save();
            }

                $dataubahtotalbayar = [
                        'total_bayar' => $input['totalbayar'],
                        'jumlah_bayar' => $input['bayar'],
                        'kembalian' => $input['kembalian'],
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
                    DB::table('penjualan')
                        ->where('id', $penjualan->id)
                        ->update($dataubahtotalbayar);


                $tanggal = date('Y-m-d');
                $uangkasir = DB::table('uang_modal_kasir')
                        ->select('uang_akhir')
                        ->where('tanggal', $tanggal)
                        ->first();
            
                    $hasilakhir = $uangkasir->uang_akhir;
                    $updateuangkasir = $hasilakhir;
                    $totaluangmodal = $input['totalbayar'];
                    $totalakhir = $totaluangmodal + $updateuangkasir;
                    $update = DB::table('uang_modal_kasir')
                        ->where('tanggal', $tanggal)
                        ->update(['uang_akhir' => $totalakhir]); 
                       

            DB::table('sementara')->truncate();
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
        $penjualan = Penjualan::find($id);

        // dd($pembelian);

        $hasil = $this->simpanTransaksiDelete($penjualan);
        if ($hasil == '') {
            return response()->json([
                'data' => 'Sukses Menghapus Transaksi'
            ]);
        } else {
            return response()->json([
                'data' => ['Gagal Menghapus transaksi! Mungkin data ini sedang digunakan oleh data di tabel lainnya!']
            ], 422);
        }
    }

    protected function simpanTransaksiDelete($penjualan)
    {
       // dd($pembelian);
        DB::beginTransaction();

        try {

            foreach ($penjualan->penjualandetail as $key => $value) {
                $barang = $value->barang;

                $dataubah = [
                    'stok' => $barang->stok + $value->qty,
                    'updated_at' => date('Y/m/d H:i:s')
                ];

                DB::table('barang')
                    ->where('id', $barang->id)
                    ->update($dataubah);

                $historylama = History::where(['barang_id' => $barang->id, 'kode' => $penjualan->no_invoice, 'nama' => 'penjualan'])->first();

                if ($historylama != null) {
                    $historylama->delete();
                }
            }

            $penjualan->delete();
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


    public function barangpenjualan()
    {
        $barang = Barang::where('status','toko')->get();
        $cacah = 0;
        $data = [];

        foreach ($barang as $i => $d) {
            $data[$cacah] = [
                $d->kode, 
                $d->nama_barang, 
                $d->kategori->nama,
                $d->harga_beli,
                $d->stok,
                $d->id
            ];

            $cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }
    public function totalbarang()
    {
        $barang = Sementara::all()->count();
        return response()->json([
            'totalbarang' => $barang
        ]);
    }

    public function strukjual($kode) {
        Utility::printStruk($kode);
    }


}
