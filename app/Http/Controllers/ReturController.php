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

    public function getAutoKode() {
        $barang = DB::table('retur')
                ->where('no_retur', 'like', 'BE%')
                ->select('no_retur')
                ->orderBy('no_retur', 'desc')
                ->first();

        if ($barang == null) {
            return response()->json('RE001'); 
        } else {
            $kembali = str_replace('RE', '', $barang->no_retur);
            $kembali = (int)$kembali;

            $kembali = Utility::sisipkanNol(++$kembali, 5);

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
        //
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
        $retur = Penjualan::find($id);

        return response()->json([
            'id' => $retur->id,
            'invoice' => $retur->no_invoice,
        ]);
    }

    public function tambahretur($id)
    {
        $retur = Penjualan::find($id);
        if ($retur == null) {
            return redirect('/retur');
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
            $data[$cacah] = [$barang->kode, $barang->nama_barang, $d->jumlah, $d->diskon, $d->harga, $d->jumlah * $d->harga - $d->diskon, $d->id];
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
