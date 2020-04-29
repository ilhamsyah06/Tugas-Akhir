<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;
use Exception;
use App\Uang_modal_kasir;
use DB;
use Carbon\Carbon;

class LaporanmodalkasirController extends Controller
{
    public function index()
    {
        return view('laporan.modal_kasir.index');
    }

    public function datamodalkasir(Request $request)
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
            $modalkasir = Uang_modal_kasir::all();
        } else {
            $arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

            $modalkasir = Uang_modal_kasir::whereBetween('tanggal',[$from, $to], 'and')->get();
            
        }     

        foreach ($modalkasir as $i => $d) {
            $data[$cacah] = [
                $d->created_at->format('d-m-Y H:i:s'),
                $d->user->name, 
                $d->uang_awal,
                $d->uang_akhir,
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
            return redirect('/laporanmodalkasir');
        } else {
            if (!isset($input['lap'])) {
                return redirect('/laporanmodalkasir');
            } 

            if ($input['lap'] != 'semua') {
                return redirect('/laporanmodalkasir');
            }

            $start = $input['start'];
            $end = $input['end'];

            $data = [];
            $cacah = 0;

            $periode = null;
            if ($start == '' || $end == '') {
                    $modalkasir = Uang_modal_kasir::all();
            } else {
                $periode = 'Periode : '.$start.' s/d '.$end;
                $arr_tgl_dari = explode ("/", $start, 3);
                $arr_tgl_sampai= explode ("/", $end, 3);

                $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
                $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

                $modalkasir = Uang_modal_kasir::whereBetween('tanggal',[$from, $to], 'and')->get();
       
            } 

            if (!$modalkasir->isEmpty()) {
                $total = 0;
                foreach ($modalkasir as $key => $value) {
                    $total += $value->uang_akhir;
                }

                $pdf = App::make('dompdf.wrapper');
                if ($input['lap'] == 'semua') {
                    $pdf->loadView('laporan.modal_kasir.print', 
                        [
                            'modalkasir' => $modalkasir,
                            'periode' => $periode,
                            'total'=>$total,
                        ]
                    );
                    // $pdf->setPaper('a4')->setWarnings(false);
                }
                $pdf->setPaper('a4', 'landscape')->setWarnings(false);
                return $pdf->stream();
            } else {
                return redirect('/laporanmodalkasir');
            }
        }
    
}
}
