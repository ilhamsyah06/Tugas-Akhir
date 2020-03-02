<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\History;
use App\Barang;
use Illuminate\Support\Facades\App;
use PDF;

class LaporanhistoryController extends Controller
{
    public function index()
    {
        return view('laporan.history.index');
    }

    public function datahistories(Request $request) {
    	if (!$request->ajax()) {
    		return null;
    	}

        $input = $request->all();

    	$barang = $input['barang'];
        $start = $input['start'];
        $end = $input['end'];

    	if ($start == '' || $end == '') {
            if ($barang == null || trim($barang) == '') {
                $history = History::all();
            } else {
                $history = History::where('barang_id', $barang)->get();
            }
    	} else {
    		$arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

            if ($barang == null || trim($barang) == '') {

                $history = History::whereBetween('tgl',[$from, $to], 'and')->get();
            } else {
                $history = History::where('barang_id', $barang)->whereBetween('tgl',[$from, $to], 'and')->get();
            }
    	}

    	$data = [];
        $cacah = 0;

        foreach ($history as $i => $d) {
        	$data[$cacah] = [
        		$d->tgl->format('d/m/Y H:i:s'), 
        		$d->barang->kode.' | '. $d->barang->nama_barang, 
        		$d->stok,
                $d->masuk,
                $d->keluar,
                $d->saldo,
        		$d->user->name,
                $d->keterangan
        	];

        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function previewcetak(Request $request) {
        
        $input = $request->all();

        if (!isset($input['_token'])) {
            return redirect('/laporanhistory');
        } else {
            $start = $input['start'];
            $end = $input['end'];
            $inbarang = $input['barang'];

            $barang = null;
            if ($inbarang != null || trim($inbarang) != '') {
                $barang = Barang::find($inbarang);
            }

            $periode = null;
            if ($start == '' || $end == '') {
                if ($barang == null) {
                    $history = History::all();
                } else {
                    $periode = 'Barang : '.$barang->kode.'  -  '.$barang->nama_barang;
                    $history = $barang->history;
                }
            } else {
                $periode = 'Periode : '.$input['start'].' s/d '.$input['end'];

                $arr_tgl_dari = explode ("/", $start, 3);
                $arr_tgl_sampai= explode ("/", $end, 3);

                $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0];
                $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0];

                if ($barang == null) {
                    $history = History::whereBetween('tgl',[$from, $to], 'and')->get();
                } else {
                    $history = History::where('barang_id', $barang->id)->whereBetween('tgl',[$from, $to], 'and')->get();
                    $periode .= ', Barang : '.$barang->kode.'  -  '.$barang->nama_barang;
                }
            } 

            if (!$history->isEmpty()) {
                $pdf = \App::make('dompdf.wrapper');
                $pdf->loadView('laporan.history.tampil_cetak', 
                    [
                        'history' => $history,
                        'periode' => $periode
                    ]
                );
                $pdf->setPaper('a4', 'landscape')->setWarnings(false);
                return $pdf->stream();
            } else {
                return redirect('/laporanhistory');
            }
        }
    
}
}
