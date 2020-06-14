<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;
use Exception;
use App\User;
use App\Barang;
use DB;
use Carbon\Carbon;
use App\Historipengiriman;


class HistoripengirimanController extends Controller
{
    public function datalaporanpengiriman(Request $request)
    {
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
            $pengiriman = Historipengiriman::all();
        } else {
            $arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

            $pengiriman = Historipengiriman::whereBetween('tgl_pengiriman',[$from, $to], 'and')->get();
            
        }     

        foreach ($pengiriman as $i => $d) {
            $data[$cacah] = [
                $d->barang->kode, 
                $d->barang->nama_barang,
                $d->stok_dikirim, 
                $d->tgl_pengiriman,
                $d->id,
            ];

            $cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function preview(Request $request) {
    	
        $input = $request->all();

        if (!isset($input['_token'])) {
            return redirect('/stokopname');
        } else {
            if (!isset($input['lap'])) {
                return redirect('/stokopname');
            } 

            if ($input['lap'] != 'semua') {
                return redirect('/stokopname');
            }

            $start = $input['start'];
            $end = $input['end'];

            $data = [];
            $cacah = 0;

            $periode = null;
            if ($start == '' || $end == '') {
                    $pengiriman = Historipengiriman::all();
            } else {
                $periode = 'Periode : '.$start.' s/d '.$end;
                $arr_tgl_dari = explode ("/", $start, 3);
                $arr_tgl_sampai= explode ("/", $end, 3);

                $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
                $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

                $pengiriman = Historipengiriman::whereBetween('tgl_pengiriman',[$from, $to], 'and')->get();
       
            } 

            if (!$pengiriman->isEmpty()) {

                $pdf = App::make('dompdf.wrapper');
                if ($input['lap'] == 'semua') {
                    $pdf->loadView('master.opname.print-check', 
                        [
                            'pengiriman' => $pengiriman,
                            'periode' => $periode,
                        ]
                    );
                    // $pdf->setPaper('a4')->setWarnings(false);
                }
                $pdf->setPaper('a4', 'potrait')->setWarnings(false);
                return $pdf->stream();
            } else {
                return redirect('/stokopname');
            }
        }
    
}

public function simpandatastok($id) {
    $history = Historipengiriman::find($id);
    $barangCari = Barang::where('id', $history->barang_id)->first();

    $hasil = $this->simpanTransaksiDelete($history, $barangCari);
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

protected function simpanTransaksiDelete($history, $barangCari)
{
    DB::beginTransaction();

    try {

        $stoktoko = $barangCari->stok_toko;
        $stoktokobaru = $stoktoko + $history->stok_dikirim;

        $dataubah = [
            'stok_toko' => $stoktokobaru,
            'updated_at' => date('Y/m/d H:i:s')
        ];

        DB::table('barang')->where('id', $barangCari->id)->update($dataubah); 

        $history->delete();
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
