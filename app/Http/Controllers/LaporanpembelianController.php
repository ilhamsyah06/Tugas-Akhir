<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;
use Exception;
use App\Pembelian;

class LaporanpembelianController extends Controller
{
    public function index()
    {
        return view('laporan.pembelian.index');
    }

    public function datalaporanpembelian(Request $request)
    {
            if (!$request->ajax()) {
                return response()->json([
                    'data' => []
                ]);
            }
    
            $input = $request->all();
    
            // dd($input);
    
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
    
                // dd($from);
    
                $pembelian = Pembelian::whereBetween('tgl_pembelian',[$from, $to], 'and')->get();
            } 
    
            foreach ($pembelian as $i => $d) {
                $data[$cacah] = [
                    $d->kode, 
                    $d->supplier->nama_supplier,
                    $d->tgl_pembelian->format('d-m-Y'), 
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

    public function preview(Request $request) {
    	
        $input = $request->all();

        // dd($input);

        if (!isset($input['_token'])) {
            return redirect('/laporanpembelian');
        } else {
            if (!isset($input['lap'])) {
                return redirect('/laporanpembelian');
            } 

            if ($input['lap'] != 'semua' && $input['lap'] != 'detail') {
                return redirect('/laporanpembelian');
            }

            $start = $input['start'];
            $end = $input['end'];

            $data = [];
            $cacah = 0;

            $periode = null;
            if ($start == '' || $end == '') {
                $periode = 'Per Tanggal : '.date('d/m/Y');
                $pembelian = Pembelian::all();
            } else {
                $periode = 'Periode: '.$start.' s/d '.$end;
                $arr_tgl_dari = explode ("/", $start, 3);
                $arr_tgl_sampai= explode ("/", $end, 3);

                $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0];
                $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0];

                // dd($from);

                $pembelian = Pembelian::whereBetween('tgl_pembelian',[$from, $to], 'and')->get();
            } 


            if (!$pembelian->isEmpty()) {
                $total = 0;
                foreach ($pembelian as $key => $value) {
                    $total += $value->total_bayar;
                }

                // dd($periode);

                $pdf = App::make('dompdf.wrapper');
                if ($input['lap'] == 'semua') {
                    $pdf->loadView('laporan.pembelian.print', 
                        [
                            'pembelian' => $pembelian,
                            'periode' => $periode,
                            'total'=>$total
                        ]
                    );
                } else {
                    $pdf->loadView('laporan.pembelian.printdetail', 
                        [
                            'pembelian' => $pembelian,
                            'periode' => $periode,
                            'total'=>$total
                        ]
                    );
                    
                }
                $pdf->setPaper('a4')->setWarnings(false);
                return $pdf->stream();
            } else {
                return redirect('/laporanpembelian');
            }
        }
    
}
}
