<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Auth;

use App\Pembelian;
use App\Barang;
use App\Utility;
use App\Sementara;
use App\History;
use App\Detailpembelian;

class PembelianController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        DB::table('sementara')->truncate();
        return view('master.pembelian.index');
    }

    public function listpembelian()
    {
        return view ('master.pembelian.list_pembelian');
    }

    public function pembelians(Request $request) {

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
            $pembelian = Pembelian::all();
        } else {
            $arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0];
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0];

            $pembelian = Pembelian::whereBetween('tgl_pembelian',[$from, $to], 'and')->get();
        } 

        foreach ($pembelian as $i => $d) {
            $data[$cacah] = [
                $d->kode, 
                $d->tgl_pembelian->format('d-m-Y'), 
                $d->total_bayar,
                $d->supplier->nama_supplier,
                $d->user->name,
                $d->id
            ];

            $cacah++;    
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
                $pembelian = Pembelian::find($input['id']);

                if ($pembelian != null) {
                    $hasil = $this->memprosesKoreksi($pembelian);
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

    protected function memprosesKoreksi($pembelian) {
        DB::beginTransaction();

        try {
            DB::table('sementara')->truncate();
            $pembeliandetail = $pembelian->pembeliandetail;

            foreach ($pembeliandetail as $key => $value) {
                $sementara = new Sementara;
                $sementara->kode = $pembelian->kode;
                $sementara->barang_id = $value->barang_id;
                $sementara->harga = $value->harga;
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
                $sementara = Sementara::where('kode', $input['kode'])->get();

                if ($sementara != null) {
                    $hasil = $this->simpanTransaksiCreate($input, $sementara);
                    if ($hasil == '') {
                        return response()->json([
                                'data' => 'Sukses menyimpan pembelian barang'
                            ]);
                    } else {
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

    protected function simpanTransaksiCreate($input, $sementara) {
        DB::beginTransaction();
        try {
            $pembelian = new Pembelian;
            $pembelian->kode = $input['kode'];
            $pembelian->user_id = Auth::user()->id;

            $arr_tgl = explode ("/", $input['tgl'], 3);
            $datatgl = Carbon::now();
            $datatgl = $datatgl->setDate((int)$arr_tgl[2],(int)$arr_tgl[0],(int)$arr_tgl[1]);
            $pembelian->tgl_pembelian = $datatgl;
            $pembelian->total_bayar = $input['total'] ;
            $pembelian->supplier_id = $input['idsupplier'];

            $pembelian->save();

            foreach ($sementara as $key => $value) {
                $pembeliandetail = new Detailpembelian;
                $pembeliandetail->pembelian_id = $pembelian->id;//mengambil id pembelian
                $pembeliandetail->barang_id = $value->barang_id;
                $pembeliandetail->harga = $value->harga;
                $pembeliandetail->qty = $value->jumlah;
                $pembeliandetail->total = $value->jumlah * $value->harga;
                $pembeliandetail->save();

                $barang = $pembeliandetail->barang;//proses foregnkey eloquent
                $stok_sebelumnya = $barang->stok_gudang;
                
                $dataubah = [
                    'stok_gudang' => $barang->stok_gudang + $value->jumlah,
                    'updated_at' => date('Y/m/d H:i:s')
                ];

                DB::table('barang')
                    ->where('id', $barang->id)
                    ->update($dataubah);

                $history = new History;
                $history->nama = 'pembelian';
                $history->kode = $pembelian->kode;
                $history->tgl = $pembelian->tgl_pembelian;
                $history->barang_id = $barang->id;
                $history->stok = $stok_sebelumnya;
                $history->masuk = $value->jumlah;
                $history->keluar = 0;
                $history->saldo = $stok_sebelumnya + $value->jumlah;
                $history->user_id = $pembelian->user_id;
                $history->keterangan = 'Pembelian Barang, No. Bukti : '.$pembelian->kode;
                $history->save();
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
        $pembelian = Pembelian::find($id);
        if ($pembelian == null) {
            return redirect('/pembelian');
        }

        return view('master.pembelian.show', compact('pembelian'));
    }

    public function getdetailbeli(Request $request) {
        if (!$request->ajax()) {
            return null;
        }

        $input = $request->all();

        $kode = $input['kode'];
        // dd($kode);
        $pembelian = Pembelian::where('kode', $kode)->first();
        
        $cacah = 0;
        $data = [];

        if ($pembelian != null) {
            $pembeliandetail = $pembelian->pembeliandetail;
            foreach ($pembeliandetail as $i => $d) {
                $barang = $d->barang;//pemanggilan foregnkey deklarasi method
                $data[$cacah] = [$barang->kode, $barang->nama_barang, $barang->kategori->nama, $d->harga, $d->qty, $d->qty * $d->harga];
                $cacah++;

            }

        }

        return response()->json([
            'data' => $data
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
        $pembelian = Pembelian::find($id);
        if ($pembelian == null) {
            return redirect('/listpembelian');
        }

        return view('master.pembelian.edit_pembelian', compact('pembelian'));
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
                $sementara = Sementara::where('kode', $input['kode'])->get();
                $pembelian = Pembelian::where('kode', $input['kode'])->first();

                if ($sementara != null && $pembelian != null) {
                    $hasil = $this->simpanTransaksiUpdate($input, $sementara, $pembelian);
                    if ($hasil == '') {
                        return response()->json([
                                'data' => 'Sukses koreksi pembelian barang'
                            ]);
                    } else {
                            return response()->json([
                                'data' => ['Gagal koreksi transaksi pembelian! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                    }
                    
                } else {
                    return response()->json([
                        'data' => ['Gagal  koreksi transaksi pembelian! Data transaksi tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $sementara, $pembelian) {
        DB::beginTransaction();
        
        try {

            $dataubahtotalbayar = [
                'total_bayar' => $input['total']
            ];
            
            DB::table('pembelian')
                ->where('id', $pembelian->id)
                ->update($dataubahtotalbayar);

            $pembeliandetail = $pembelian->pembeliandetail;

            foreach ($pembeliandetail as $key => $value) {
                $barang = $value->barang;//proses mulai method foregn key
                $dataubah = [
                    'stok_gudang' => $barang->stok_gudang - $value->qty // 10 - 15
                ];
                DB::table('barang')
                    ->where('id', $barang->id)
                    ->update($dataubah);

                $value->delete();
                $historylama = History::where(['barang_id' => $barang->id, 'kode' => $pembelian->kode, 'nama' => 'pembelian'])->first();
                if ($historylama != null) {
                    $historylama->delete();
                }

                //mengembalikan stok value di barang dan menghapus history pembelian -5
            }

            foreach ($sementara as $key => $value) {
                $pembeliandetail = new Detailpembelian;
                $pembeliandetail->pembelian_id = $pembelian->id;
                $pembeliandetail->barang_id = $value->barang_id;
                $pembeliandetail->harga = $value->harga;
                $pembeliandetail->qty = $value->jumlah;
                $pembeliandetail->total = $value->jumlah * $value->harga;
                $pembeliandetail->save();
                

                $barang = $pembeliandetail->barang; //proses call function method foreign key

                $stok_sebelumnya = $barang->stok_gudang; //stok gudang -5
                
                $dataubah = [
                    'stok_gudang' => $barang->stok_gudang + $value->jumlah, //-5 + 15
                    'updated_at' => date('Y/m/d H:i:s')
                ];

                DB::table('barang')
                    ->where('id', $barang->id)
                    ->update($dataubah);


                $history = new History;
                $history->nama = 'pembelian';
                $history->kode = $pembelian->kode;
                $history->tgl = $pembelian->tgl_pembelian;
                $history->barang_id = $barang->id;
                $history->stok = $stok_sebelumnya; //-5
                $history->masuk = $value->jumlah;
                $history->keluar = 0;
                $history->saldo = $stok_sebelumnya + $value->jumlah;
                $history->user_id = $pembelian->user_id;
                $history->keterangan = 'Pembelian Barang, No. Bukti : '.$pembelian->kode;
                $history->save();
                
            }

            $arr_tgl = explode ("/", $input['tgl'], 3);
            $tgl = Carbon::createFromDate((int)$arr_tgl[2],(int)$arr_tgl[0],(int)$arr_tgl[1]);

            $ubahbeli = [
                'tgl_pembelian' => $tgl->format('Y/m/d'),
                'updated_at' => date('Y/m/d H:i:s')
            ];

            DB::table('pembelian')
                    ->where('id', $pembelian->id)
                    ->update($ubahbeli);

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
        $pembelian = Pembelian::find($id);

        $hasil = $this->simpanTransaksiDelete($pembelian);
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

    protected function simpanTransaksiDelete($pembelian)
    {
       // dd($pembelian);
        DB::beginTransaction();

        try {
            $pembeliandetail = $pembelian->pembeliandetail;

            foreach ($pembeliandetail as $key => $value) {
                $barang = $value->barang;

                $historylama = History::where(['barang_id' => $barang->id, 'kode' => $pembelian->kode, 'nama' => 'pembelian'])->first();

                if ($historylama != null) {
                    $historylama->delete();
                }
            }

            $pembelian->delete();
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

    public function getpembelianautocode() {
        $pembelian = DB::table('pembelian')
                ->where('kode', 'like', 'PE-%')
                ->select('kode')
                ->orderBy('kode', 'desc')
                ->first();

        if ($pembelian == null) {
            return response()->json('PE-00000001'); 
        } else {
            $kembali = str_replace('PE-', '', $pembelian->kode);
            $kembali = (int)$kembali;

            $kembali = Utility::sisipkanNol(++$kembali, 8);

            return response()->json('PE-'.$kembali); 
        }
    }

    public function barangpembelian() {
        $barang = Barang::all();
        $cacah = 0;
        $data = [];

        foreach ($barang as $i => $d) {
            $data[$cacah] = [
                $d->kode, 
                $d->nama_barang, 
                $d->kategori->nama,
                $d->harga_beli,
                $d->stok_gudang,
                $d->id
            ];

            $cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
