<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;
use Exception;
use App\Penjualan;
use App\User;
use DB;


class LaporanpenjualanController extends Controller
{
    public function index()
    {
        return view('laporan.penjualan.index');
    }

    public function datalaporanpenjualan(Request $request)
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
            $penjualan = Penjualan::all();
        } else {
            $arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

            $penjualan = Penjualan::whereBetween('tgl_penjualan',[$from, $to], 'and')->get();
            
        }     

        foreach ($penjualan as $i => $d) {
            $data[$cacah] = [
                $d->created_at->format('d-m-Y H:i:s'), 
                $d->no_invoice,
                $d->user->name, 
                $d->total_bayar,
                $d->keuntungan(),
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
            return redirect('/laporanpenjualan');
        } else {
            if (!isset($input['lap'])) {
                return redirect('/laporanpenjualan');
            } 

            if ($input['lap'] != 'semua' && $input['lap'] != 'detail') {
                return redirect('/laporanpenjualan');
            }

            $start = $input['start'];
            $end = $input['end'];

            $data = [];
            $cacah = 0;

            $periode = null;
            if ($start == '' || $end == '') {
                    $penjualan = Penjualan::all();
            } else {
                $periode = 'Periode : '.$start.' s/d '.$end;
                $arr_tgl_dari = explode ("/", $start, 3);
                $arr_tgl_sampai= explode ("/", $end, 3);

                $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
                $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

                $penjualan = Penjualan::whereBetween('tgl_penjualan',[$from, $to], 'and')->get();
       
            } 

            if (!$penjualan->isEmpty()) {
                $total = 0;
                $keuntungan = 0;
                foreach ($penjualan as $key => $value) {
                    $total += $value->total_bayar;
                    $keuntungan += $value->keuntungan();
                }

                $pdf = App::make('dompdf.wrapper');
                if ($input['lap'] == 'semua') {
                    $pdf->loadView('laporan.penjualan.print', 
                        [
                            'penjualan' => $penjualan,
                            'periode' => $periode,
                            'total'=>$total,
                            'keuntungan'=>$keuntungan,
                        ]
                    );
                    // $pdf->setPaper('a4')->setWarnings(false);
                } else {
                    $pdf->loadView('laporan.penjualan.printdetail', 
                        [
                            'penjualan' => $penjualan,
                            'periode' => $periode,
                            'total'=>$total,
                            'keuntungan'=>$keuntungan,
                        ]
                    );
                    
                }
                $pdf->setPaper('a4', 'landscape')->setWarnings(false);
                return $pdf->stream();
            } else {
                return redirect('/laporanpenjualan');
            }
        }
    
}
}
