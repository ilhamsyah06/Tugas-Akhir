<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barang;
use App\Kategori;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;

class LaporanbarangController extends Controller
{
    public function index()
    {
        return view('laporan.barang.index');
    }

    public function datalaporanbarang(Request $request) {
    	if (!$request->ajax()) {
    		return null;
    	}

    	$jenis = $request->all()['jenis'];

    	if ($jenis == null || trim($jenis) == '') {
    		$barang = Barang::all();
    	} else {
    		$barang = Barang::where('kategori_id', $jenis)->get();
    	}

    	$data = [];
        $cacah = 0;

        foreach ($barang as $i => $d) {
        	$data[$cacah] = [
        		$d->kode, 
        		$d->nama_barang, 
        		$d->kategori->nama,
                $d->stok_toko,
                $d->stok_gudang
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
            return redirect('/laporanbarang');
        } else {
            $jenis = null;
            if ($input['jenis'] == null || trim($input['jenis']) == '') {
                $barang = Barang::all();
            } else {
                $jenis = Kategori::find($input['jenis']);
                $barang = $jenis->barang;
            }

            if (!$barang->isEmpty()) {

                $totalstoktoko = 0;
                $totalstokgudang = 0;

                foreach ($barang as $key => $value) {
                    $totalstoktoko += $value->stok_toko;
                    $totalstokgudang += $value->stok_gudang;

                }

                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('laporan.barang.print', 
                    [
                        'barang' => $barang,
                        'jenis' => ($jenis != null ? ', Jenis Barang : '.$jenis->nama : ''),
                        'totalstoktoko' => $totalstoktoko,
                        'totalstokgudang' => $totalstokgudang,
                    ]
                );
                $pdf->setPaper('a4', 'landscape')->setWarnings(false);
                return $pdf->stream();
            } else {
                return redirect('/laporanbarang');
            }
        }
    
}
}
