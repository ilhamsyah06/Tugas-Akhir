<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

use App\Opname;
use App\Barang;
use App\Hilang;
use App\History;

class OpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.opname.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function create()
     {
         # code...
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
                $hasil = $this->simpanTransaksiCreate($input);
                if ($hasil == '') {
                    return response()->json([
                        'data' => 'Sukses Menyimpan'
                    ]);
                } else {
                    return response()->json([
                        'data' => ['Gagal menyimpan data! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                    ], 422);
                }

            }
        }
    }

    protected function simpanTransaksiCreate($input) {
        DB::beginTransaction();

        try {
        	$barang = Barang::where('kode', $input['barang'])->first();

        	$opname = new Opname;
        	$opname->kode = $input['kode'];
        	$arr_tgl = explode ("/", $input['tgl'], 3);
            $dataTgl = Carbon::now();
            $dataTgl->setDate((int)$arr_tgl[2],(int)$arr_tgl[0],(int)$arr_tgl[1]);
            $opname->tgl = $dataTgl;
        	$opname->user_id = Auth::user()->id;
        	$opname->barang_id = $barang->id;
        	$opname->stok = $input['stok_komputer'];
        	$opname->stok_nyata = $input['stok_nyata'];
        	$opname->selisih = $input['selisih'];
        	$opname->keterangan = $input['keterangan'];

        	$opname->save();

        	$selisih = $input['selisih'];

        	$stok_sebelumnya = $barang->stok_toko;

        	$dataubah = [
            	'stok_toko' => $input['stok_nyata'],
                'updated_at' => date('Y/m/d H:i:s')
            ];

            DB::table('barang')
                ->where('id', $barang->id)
                ->update($dataubah);

            $history = new History;
            $history->nama = 'opname';
            $history->kode = $opname->kode;
        	$history->tgl = $opname->tgl;
        	$history->barang_id = $barang->id;
        	$history->stok = $stok_sebelumnya;

        	if ($selisih < 0) {
        		$history->masuk = 0;
        		$history->keluar = $selisih * -1;
        	} else {
        		$history->masuk = $selisih;
        		$history->keluar = 0;        		
        	}
        
        	$history->saldo = $input['stok_nyata'];
        	$history->user_id = $opname->user_id;
        	$history->keterangan = 'Stok Opname, No. Bukti : '.$opname->kode;
        	$history->save();
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
        //
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
        //
    }
}
