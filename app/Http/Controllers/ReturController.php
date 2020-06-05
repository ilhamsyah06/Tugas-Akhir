<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;

use Auth;
// model
use App\Utility;
use App\Penjualan;
use App\Sementara;
use App\SementaraRetur;
use App\Uang_modal_kasir;
use App\Detailpenjualan;
use App\DetailRetur;
use App\Retur;
use App\History;
use Carbon\Carbon;

class ReturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        DB::table('sementara')->truncate();
        DB::table('sementara_retur')->truncate();
        return view('master.retur.index');
    }

    public function listretur()
    {
        return view('master.retur.listretur');
    }

    public function dataretur(Request $request) {
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
            $retur = Retur::all();
        } else {
            $arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0]. ' 23:59:59';

            $retur = Retur::whereBetween('tgl_retur',[$from, $to], 'and')->get();

            // dd($from.', to : '.$to.', penjualan : '.$penjualan);
        }     

        foreach ($retur as $i => $d) {
            $data[$cacah] = [
                $d->no_retur, 
                $d->created_at->format('d-m-Y H:i:s'), 
                $d->penjualan->no_invoice,
                $d->user->name,
                $d->id
            ];

            $cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function getAutoKode() {
        $barang = DB::table('retur')
                ->where('no_retur', 'like', 'RE%')
                ->select('no_retur')
                ->orderBy('no_retur', 'desc')
                ->first();

        if ($barang == null) {
            return response()->json('RE001'); 
        } else {
            $kembali = str_replace('RE', '', $barang->no_retur);
            $kembali = (int)$kembali;

            $kembali = Utility::sisipkanNol(++$kembali, 3);

            return response()->json('RE'.$kembali); 
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
                $tanggal = date('Y-m-d');
                $uangmodal = Uang_modal_kasir::where('tanggal', $tanggal)->first();
                $sementara = Sementara::where('kode', $input['invoice'])->get();
                $sementararetur = SementaraRetur::where('noretur', $input['nomoretur'])->get();
                $penjualan = Penjualan::where('no_invoice', $input['invoice'])->first();
                if ($uangmodal === null) {
                    return response()->json([
                        'data' => ['Silahkan Isi Uang Modal Kasir Agar Bisa Melakukan Transaksi!']
                    ], 422);
                }
                if ($sementara != null && $penjualan != null && $sementararetur != null) {
                    $hasil = $this->simpanTransaksiUpdate($input, $sementara, $sementararetur, $penjualan, $uangmodal);
                    if ($hasil == '') {
                        return response()->json([
                                'data' => 'Sukses retur penjualan barang'
                            ]);
                    } else {
                        dd($hasil);
                            return response()->json([
                                'data' => ['Gagal koreksi transaksi retur! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                    }
                    
                } else {
                    return response()->json([
                        'data' => ['Gagal  koreksi transaksi retur! Data transaksi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $sementara, $sementararetur, $penjualan, $uangmodal) {
        DB::beginTransaction();
    try {
            //memasukkan data di tabel retur
            $retur = new Retur;
            $retur->no_retur = $input['nomoretur'];
            $retur->penjualan_id = $penjualan->id;
            $retur->tgl_retur = $input['tglretur'];
            $retur->user_id = Auth::user()->id;
            $retur->save();

            foreach ($sementararetur as $key => $value){
                $returdetail = new DetailRetur;
                $returdetail->retur_id = $retur->id;
                $barang = $value->barang;
                $returdetail->barang_id = $barang->id;
                $returdetail->harga = $value->harga;
                $returdetail->qty = $value->jumlah;
                $returdetail->diskon_item = $value->diskon;
                $returdetail->total = ($value->jumlah * $value->harga) - ($value->diskon * $value->jumlah);
                $returdetail->save();

                $stok_sebelumnya = $barang->stok_toko;

                $dataubah = [
                    'stok_toko' => $barang->stok_toko + $value->jumlah,
                    'updated_at' => date('Y/m/d H:i:s')
                ];

                DB::table('barang')
                    ->where('id', $barang->id)
                    ->update($dataubah);

                $history = new History;
                $history->nama = 'retur';
                $history->kode = $penjualan->no_invoice;
                $history->tgl = Carbon::now();
                $history->barang_id = $barang->id;
                $history->stok = $stok_sebelumnya;
                $history->masuk = $value->jumlah;
                $history->keluar = 0;
                $history->saldo = $stok_sebelumnya + $value->jumlah;
                $history->user_id = $penjualan->user_id;
                $history->keterangan = 'Retur Barang, No. Bukti : '.$input['nomoretur'];
                $history->save();
            }

            foreach ($penjualan->penjualandetail as $key => $value) {

                $barang = $value->barang; //proses foregnkey eloquent

                $dataubahuangmodal = [
                        'uang_akhir' => $uangmodal->uang_akhir - $penjualan->total_bayar //dikembalikan / dikurangi lagi uang akhir lama
                ];
                $now = date('Y-m-d');
                DB::table('uang_modal_kasir')
                    ->where('tanggal', $now)
                    ->update($dataubahuangmodal);

                $value->delete(); //menghapus data lama di penjualan detail

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
                $penjualandetail->total = ($value->jumlah * $value->harga) - ($value->diskon * $value->jumlah);
                $penjualandetail->save();

                $stok_sebelumnya = $barang->stok_toko;

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


                //proses update total jumlah bayar dan kembalian
                $dataubahtotalbayar = [
                        'total_bayar' => $input['totalbayar'],
                        'updated_at' => date('Y/m/d H:i:s')
                    ];

                    DB::table('penjualan')
                        ->where('id', $penjualan->id)
                        ->update($dataubahtotalbayar);

                //proses update uangkasir
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
            DB::table('sementara_retur')->truncate();
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

    public function show($id){
        $retur = Retur::find($id);
        if ($retur == null) {
            return redirect('/retur');
        }
        $countbarang = DB::table('retur_detail')->where('retur_id', $retur->id)->count();
        return view('master.retur.tampil_detail', compact('retur','countbarang'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $retur = Penjualan::find($id);

        return response()->json([
            'id' => $retur->id,
            'invoice' => $retur->no_invoice,
        ]);
    }

    public function tambahretur($id)
    {
        $tglnow = date('Y-m-d');
        $retur = Penjualan::find($id);
        $cekdatabarang = Detailpenjualan::where('penjualan_id', $id)->count();
        if ($retur == null) {
            return redirect('/retur');
        }elseif($cekdatabarang < 2){
            return redirect('/retur')->with(['warning' => 'Barang yang Dibeli Kurang Dari 2 Item.']);
        }elseif($retur->tgl_penjualan->format('Y-m-d') != $tglnow){
            return redirect('/retur')->with(['warning' => 'Tidak Dapat Retur, Data Penjualan Lebih Dari Waktu Yang Ditentukan.']);
        }
            return view('master.retur.tambah_retur', compact('retur'));

    }

    public function getsementararetur(Request $request)
    {
        if (!$request->ajax()) {
    		return null;
    	}

    	$input = $request->all();

    	$noretur = $input['kode'];
    	// dd($kode);
    	$sementaras = SementaraRetur::where('kode', $noretur)->get();
    	
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

    public function siapkanretur(Request $request)
    {
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
                        'data' => ['Gagal menyiapkan data koreksi! Retur tidak ditemukan di database']
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }


    public function ApiPenjualan()
    {
        $penjualan = Penjualan::all();
        $cacah = 0;
        $data = [];

        foreach ($penjualan as $i => $d) {
        	$data[$cacah] = [
                $d->id,
        		$d->no_invoice, 
        		$d->tgl_penjualan->format('d-m-Y'),
        		$d->total_bayar, 
                $d->jumlah_bayar,
                $d->kembalian,
                $d->user->name,
        		$d->id
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function retursemua(Request $request)
    {

        if ($request->ajax()) {
            $input = $request->all();

            $sementara = Sementara::find($input['id']);
            $cekdataretur = SementaraRetur::where('barang_id', $sementara->barang_id)->first();

            $cekdatabarang = SementaraRetur::count();
            if ($cekdatabarang === 1) {
             return response()->json([
                    'data' => ['Barang Yang Diretur Tidak Boleh Melebihi 1 Item!']
                ], 422);
            }

            $hasil = $this->simpanTransaksiReturSemua($sementara, $input, $cekdataretur);
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

    }

        //method
        protected function simpanTransaksiReturSemua($sementara, $input, $cekdataretur)
        {
    //        dd($input);
            DB::beginTransaction();
    
            try {
    
                if ($sementara->jumlah === 1 && $cekdataretur != null) { //jika data barang di tabel sementara retur tidak ada makamelakukan ini
                            
                    $dataubah = [
                        'jumlah' => $cekdataretur->jumlah + $sementara->jumlah,
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
    
                    DB::table('sementara_retur')
                        ->where('barang_id', $cekdataretur->barang_id)
                        ->update($dataubah);

                    $dataubah2 = [
                        'jumlah' => $sementara->jumlah - 1,
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
    
                DB::table('sementara')
                        ->where('id', $sementara->id)
                        ->update($dataubah2);

                    $sementara->delete();
                }elseif ($sementara->jumlah > 1 && $cekdataretur != null){
                    $dataubah = [
                        'jumlah' => $cekdataretur->jumlah + $sementara->jumlah,
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
    
                    DB::table('sementara_retur')
                        ->where('barang_id', $cekdataretur->barang_id)
                        ->update($dataubah);
                    
                    $sementara->delete();
                }elseif ($sementara->jumlah > 1){
                        $sementararetur = new SementaraRetur;
                        $sementararetur->noretur = $input['noretur'];
                        $sementararetur->kode = $sementara->kode;
                        $sementararetur->barang_id = $sementara->barang_id;
                        $sementararetur->harga = $sementara->harga;
                        $sementararetur->diskon = $sementara->diskon;
                        $sementararetur->jumlah = $sementara->jumlah;
                        $sementararetur->save();

                        $sementara->delete();
                }else{
                    $dataubah = [
                        'jumlah' => $sementara->jumlah - 1,
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
    
                    DB::table('sementara')
                        ->where('id', $sementara->id)
                        ->update($dataubah);
    
                        $sementararetur = new SementaraRetur;
                        $sementararetur->noretur = $input['noretur'];
                        $sementararetur->kode = $sementara->kode;
                        $sementararetur->barang_id = $sementara->barang_id;
                        $sementararetur->harga = $sementara->harga;
                        $sementararetur->diskon = $sementara->diskon;
                        $sementararetur->jumlah = 1;
                        $sementararetur->save();
                    
                    $sementara->delete();
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

        public function kembalisemua(Request $request)
        {
    
            if ($request->ajax()) {
                $input = $request->all();
    
                $sementararetur = SementaraRetur::find($input['id']);
                $cekdatasementara = Sementara::where('barang_id', $sementararetur->barang_id)->first();

    
                $hasil = $this->simpanTransaksiKembaliSemua($sementararetur, $input, $cekdatasementara);
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
    
        }
            //method
            protected function simpanTransaksiKembaliSemua($sementararetur, $input, $cekdatasementara)
            {
        //        dd($input);
                DB::beginTransaction();
        
                try {
                   
                if ($sementararetur->jumlah === 1 && $cekdatasementara != null) { //jika data barang di tabel sementara retur tidak ada makamelakukan ini
                            
                    $dataubah = [
                        'jumlah' => $cekdatasementara->jumlah + $sementararetur->jumlah,
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
    
                    DB::table('sementara')
                        ->where('barang_id', $cekdatasementara->barang_id)
                        ->update($dataubah);

                    $sementararetur->delete();
                }elseif ($sementararetur->jumlah > 1 && $cekdatasementara != null){
                    $dataubah = [
                        'jumlah' => $cekdatasementara->jumlah + $sementararetur->jumlah,
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
    
                    DB::table('sementara')
                        ->where('barang_id', $cekdatasementara->barang_id)
                        ->update($dataubah);
                    
                    $sementararetur->delete();
                }elseif ($sementararetur->jumlah > 1){
                        $sementara = new Sementara;
                        $sementara->kode = $sementararetur->kode;
                        $sementara->barang_id = $sementararetur->barang_id;
                        $sementara->harga = $sementararetur->harga;
                        $sementara->diskon = $sementararetur->diskon;
                        $sementara->jumlah = $sementararetur->jumlah;
                        $sementara->save();

                        $sementararetur->delete();
                }else{
                    $dataubah = [
                        'jumlah' => $sementararetur->jumlah - 1,
                        'updated_at' => date('Y/m/d H:i:s')
                    ];
    
                    DB::table('sementara')
                        ->where('id', $sementararetur->id)
                        ->update($dataubah);
    
                        $sementara = new Sementara;
                        $sementara->kode = $sementararetur->kode;
                        $sementara->barang_id = $sementararetur->barang_id;
                        $sementara->harga = $sementararetur->harga;
                        $sementara->diskon = $sementararetur->diskon;
                        $sementara->jumlah = 1;
                        $sementara->save();
                    
                    $sementararetur->delete();
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


    //---------------------- kembali & meretur satu ---------------------//

            public function kembalisatu(Request $request)
        {
    
            if ($request->ajax()) {
                $input = $request->all();
    
                $sementararetur = SementaraRetur::find($input['id']);
                $cekdatasementara = Sementara::where('barang_id', $sementararetur->barang_id)->first();
    
                $hasil = $this->simpanTransaksiKembaliSatu($sementararetur, $input, $cekdatasementara);
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
    
        }
    
            //method
            protected function simpanTransaksiKembaliSatu($sementararetur, $input, $cekdatasementara)
            {
        //        dd($input);
                DB::beginTransaction();
        
                try {

                    
                    if ($sementararetur->jumlah === 1 && $cekdatasementara != null) { //jika data barang di tabel sementara retur tidak ada makamelakukan ini
                            
                        $dataubah = [
                            'jumlah' => $cekdatasementara->jumlah + $sementararetur->jumlah,
                            'updated_at' => date('Y/m/d H:i:s')
                        ];
        
                        DB::table('sementara')
                            ->where('barang_id', $cekdatasementara->barang_id)
                            ->update($dataubah);

                        $dataubah2 = [
                            'jumlah' => $sementararetur->jumlah - 1,
                            'updated_at' => date('Y/m/d H:i:s')
                        ];
        
                        DB::table('sementara_retur')
                            ->where('id', $sementararetur->id)
                            ->update($dataubah2);

                        $sementararetur->delete();
                    }elseif ($sementararetur->jumlah > 1 && $cekdatasementara != null){

                        $dataubah = [
                            'jumlah' => $cekdatasementara->jumlah + 1,
                            'updated_at' => date('Y/m/d H:i:s')
                        ];
        
                        DB::table('sementara')
                            ->where('barang_id', $cekdatasementara->barang_id)
                            ->update($dataubah);
                        
                        $dataubah2 = [
                                'jumlah' => $sementararetur->jumlah - 1,
                                'updated_at' => date('Y/m/d H:i:s')
                            ];
            
                        DB::table('sementara_retur')
                                ->where('id', $sementararetur->id)
                                ->update($dataubah2);

                    }elseif ($sementararetur->jumlah > 1){
                        $dataubah = [
                            'jumlah' => $sementararetur->jumlah - 1,
                            'updated_at' => date('Y/m/d H:i:s')
                        ];
        
                        DB::table('sementara_retur')
                            ->where('id', $sementararetur->id)
                            ->update($dataubah);
        
                            $sementara = new Sementara;
                            $sementara->kode = $sementararetur->kode;
                            $sementara->barang_id = $sementararetur->barang_id;
                            $sementara->harga = $sementararetur->harga;
                            $sementara->diskon = $sementararetur->diskon;
                            $sementara->jumlah = 1;
                            $sementara->save();
                    }else{
                        $dataubah = [
                            'jumlah' => $sementararetur->jumlah - 1,
                            'updated_at' => date('Y/m/d H:i:s')
                        ];
        
                        DB::table('sementara_retur')
                            ->where('id', $sementararetur->id)
                            ->update($dataubah);
        
                            $sementara = new Sementara;
                            $sementara->kode = $sementararetur->kode;
                            $sementara->barang_id = $sementararetur->barang_id;
                            $sementara->harga = $sementararetur->harga;
                            $sementara->diskon = $sementararetur->diskon;
                            $sementara->jumlah = 1;
                            $sementara->save();
                        
                        $sementararetur->delete();
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

            public function retursatu(Request $request)
            {
        
                if ($request->ajax()) {
                    $input = $request->all();

                    $sementara = Sementara::find($input['id']);

                    $cekdataretur = SementaraRetur::where('barang_id', $sementara->barang_id)->first();
                    
                    $cekdatabarang = SementaraRetur::count();
                    if ($cekdatabarang === 1) {
                     return response()->json([
                            'data' => ['Barang Yang Diretur Tidak Boleh Melebihi 1 Item!']
                        ], 422);
                    }

                    $hasil = $this->simpanTransaksiReturSatu($sementara, $input, $cekdataretur);
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
        
            }
        
                //method
                protected function simpanTransaksiReturSatu($sementara, $input, $cekdataretur)
                {

                    DB::beginTransaction();
                    
                    try {

                        if ($sementara->jumlah === 1 && $cekdataretur != null) { //jika data barang di tabel sementara retur tidak ada makamelakukan ini
                            
                            $dataubah = [
                                'jumlah' => $cekdataretur->jumlah + $sementara->jumlah,
                                'updated_at' => date('Y/m/d H:i:s')
                            ];
            
                            DB::table('sementara_retur')
                                ->where('barang_id', $cekdataretur->barang_id)
                                ->update($dataubah);

                            $dataubah2 = [
                                'jumlah' => $sementara->jumlah - 1,
                                'updated_at' => date('Y/m/d H:i:s')
                            ];
            
                        DB::table('sementara')
                                ->where('id', $sementara->id)
                                ->update($dataubah2);

                            $sementara->delete();
                        }elseif ($sementara->jumlah > 1 && $cekdataretur != null){
                            $dataubah = [
                                'jumlah' => $cekdataretur->jumlah + 1,
                                'updated_at' => date('Y/m/d H:i:s')
                            ];
            
                            DB::table('sementara_retur')
                                ->where('barang_id', $cekdataretur->barang_id)
                                ->update($dataubah);
                            
                            $dataubah2 = [
                                    'jumlah' => $sementara->jumlah - 1,
                                    'updated_at' => date('Y/m/d H:i:s')
                                ];
                
                            DB::table('sementara')
                                    ->where('id', $sementara->id)
                                    ->update($dataubah2);
                        }elseif ($sementara->jumlah > 1){
                            $dataubah = [
                                'jumlah' => $sementara->jumlah - 1,
                                'updated_at' => date('Y/m/d H:i:s')
                            ];
            
                            DB::table('sementara')
                                ->where('id', $sementara->id)
                                ->update($dataubah);
            
                                $sementararetur = new SementaraRetur;
                                $sementararetur->noretur = $input['noretur'];
                                $sementararetur->kode = $sementara->kode;
                                $sementararetur->barang_id = $sementara->barang_id;
                                $sementararetur->harga = $sementara->harga;
                                $sementararetur->diskon = $sementara->diskon;
                                $sementararetur->jumlah = 1;
                                $sementararetur->save();
                        }else{
                            $dataubah = [
                                'jumlah' => $sementara->jumlah - 1,
                                'updated_at' => date('Y/m/d H:i:s')
                            ];
            
                            DB::table('sementara')
                                ->where('id', $sementara->id)
                                ->update($dataubah);
            
                                $sementararetur = new SementaraRetur;
                                $sementararetur->noretur = $input['noretur'];
                                $sementararetur->kode = $sementara->kode;
                                $sementararetur->barang_id = $sementara->barang_id;
                                $sementararetur->harga = $sementara->harga;
                                $sementararetur->diskon = $sementara->diskon;
                                $sementararetur->jumlah = 1;
                                $sementararetur->save();
                            
                            $sementara->delete();
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
}
