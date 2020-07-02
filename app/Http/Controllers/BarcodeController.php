<?php

namespace App\Http\Controllers;
use PDF;
use App\Barang;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function index()
    {
        return view('master.barcode.index');
    }
    
    public function viewbarcode(Request $request)
    {
        $no = 1; 
        $qty = request('qty');
        $kode = request('kode');
        $harga = request('harga');
        return view('master.barcode.view', ['harga' => $harga, 'qty' => $qty, 'kode' => $kode , 'no' => $no]);
    }

    public function caribarangbarcode()
    {
        $barang = Barang::all();
        $cacah = 0;
        $data = [];

        foreach ($barang as $i => $d) {
            $data[$cacah] = [
                $d->kode, 
                $d->nama_barang, 
                $d->kategori->nama,
                $d->harga_jual,
                $d->id
            ];

            $cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }
}
