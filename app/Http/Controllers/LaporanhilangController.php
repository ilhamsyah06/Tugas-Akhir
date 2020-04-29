<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Hilang;
use App\Opname;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;

class LaporanhilangController extends Controller
{
    public function index()
    {
        return view('laporan.hilang.index');
    }

    public function datahilang(Request $request)
    {
        if (!$request->ajax()) {
    		return null;
    	}

        $input = $request->all();

    	$barang = $input['barang'];
        $start = $input['start'];
        $end = $input['end'];

    	if ($start == '' || $end == '') {
            if ($barang == null || trim($barang) == '') {
                $hilang = DB::table('hilang')->select('id')->get();
            } else {
                $hilang = DB::table('hilang')->where('barang_id', $barang)->select('id')->get();
            }
    	} else {
    		$arr_tgl_dari = explode ("/", $start, 3);
            $arr_tgl_sampai= explode ("/", $end, 3);

            $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0];
            $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0];

            if ($barang == null || trim($barang) == '') {
            	$hilang = DB::table('hilang')
                    ->join('opname', 'opname.id', '=', 'hilang.id')
                    ->select('hilang.id')
                    ->whereBetween('opname.tgl',[$from, $to], 'and')
                    ->get();
            } else {
            	$hilang = DB::table('hilang')
                    ->join('opname', 'opname.id', '=', 'hilang.id')
                    ->select('hilang.id')
                    ->where('hilang.barang_id', $barang)
                    ->whereBetween('opname.tgl',[$from, $to], 'and')
                    ->get();
            }
    	}

    	$data = [];
        $cacah = 0;

        // dd($hilang);

        foreach ($hilang as $i => $d) {
        	// dd($d);
        	$hil = Hilang::find($d->id);

        	// dd($hil->opname);

        	$data[$cacah] = [
        		$hil->opname->tgl->format('d-m-Y'), 
        		$hil->barang->kode.' | '. $hil->barang->nama_barang, 
        		$hil->jumlah,
        		$hil->opname->user->name,
                $hil->opname->kode
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
            return redirect('/laporanhilang');
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
                    $hilang = DB::table('hilang')->select('id')->get();
                } else {
                    $periode = 'Barang : '.$barang->kode.'  -  '.$barang->nama_barang;
                    $hilang = $barang->hilang;
                }
            } else {
                $periode = 'Periode : '.$input['start'].' s/d '.$input['end'];

                $arr_tgl_dari = explode ("/", $start, 3);
                $arr_tgl_sampai= explode ("/", $end, 3);

                $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0];
                $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0];

                if ($barang == null) {
                    $hilang = DB::table('hilang')
                        ->join('opname', 'opname.id', '=', 'hilang.id')
                        ->select('hilang.id')
                        ->whereBetween('opname.tgl',[$from, $to], 'and')
                        ->get();
                } else {
                    $hilang = DB::table('hilang')
                        ->join('opname', 'opname.id', '=', 'hilang.id')
                        ->select('hilang.id')
                        ->where('hilang.barang_id', $barang->id)
                        ->whereBetween('opname.tgl',[$from, $to], 'and')
                        ->get();
                    $periode .= ', Barang : '.$barang->kode.'  -  '.$barang->nama_barang;
                }
            } 

            // dd($hilang);

            $data = [];

            $total = 0;

            foreach ($hilang as $key => $value) {
                $entHilang = Hilang::find($value->id);

                $total += $entHilang->jumlah;

                $data[$key] = $entHilang;
            }

            if (sizeof($data) > 0) {
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('laporan.hilang.print', 
                    [
                        'hilang' => $data,
                        'periode' => $periode,
                        'total' => $total
                    ]
                );
                $pdf->setPaper('a4')->setWarnings(false);
                return $pdf->stream();
            } else {
                return redirect('/laporanhilang');
            }
        }
    
}
}
