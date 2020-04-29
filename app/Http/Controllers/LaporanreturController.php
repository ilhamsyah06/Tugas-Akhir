<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;
use Exception;
use App\Retur;

class LaporanreturController extends Controller
{
    public function index()
    {
        return view('laporan.retur.index');
    }

    public function datalaporanretur(Request $request)
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
            $retur = Retur::all();
        } else {
            $arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

            $retur = Retur::whereBetween('tgl_retur',[$from, $to], 'and')->get();
            
        }     

        foreach ($retur as $i => $d) {
            $data[$cacah] = [
                $d->created_at->format('d-m-Y H:i:s'),
                $d->no_retur, 
                $d->user->name,
                $d->total(),
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
            return redirect('/laporanretur');
        } else {
            if (!isset($input['lap'])) {
                return redirect('/laporanretur');
            } 

            if ($input['lap'] != 'semua' && $input['lap'] != 'detail') {
                return redirect('/laporanretur');
            }

            $start = $input['start'];
            $end = $input['end'];

            $data = [];
            $cacah = 0;

            $periode = null;
            if ($start == '' || $end == '') {
                    $retur = Retur::all();
            } else {
                $periode = 'Periode : '.$start.' s/d '.$end;
                $arr_tgl_dari = explode ("/", $start, 3);
                $arr_tgl_sampai= explode ("/", $end, 3);

                $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
                $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

                $retur = Retur::whereBetween('tgl_retur',[$from, $to], 'and')->get();
       
            } 

            if (!$retur->isEmpty()) {
                $total = 0;
                foreach ($retur as $key => $value) {
                    $total += $value->total();
                }

                $pdf = App::make('dompdf.wrapper');
                if ($input['lap'] == 'semua') {
                    $pdf->loadView('laporan.retur.print', 
                        [
                            'retur' => $retur,
                            'periode' => $periode,
                            'total'=>$total
                        ]
                    );
                    // $pdf->setPaper('a4')->setWarnings(false);
                } else {
                    $pdf->loadView('laporan.retur.printdetail', 
                        [
                            'retur' => $retur,
                            'periode' => $periode,
                            'total'=>$total
                        ]
                    );
                    
                }
                $pdf->setPaper('a4', 'landscape')->setWarnings(false);
                return $pdf->stream();
            } else {
                return redirect('/laporanmodalkasir');
            }
        }
    
}
}
