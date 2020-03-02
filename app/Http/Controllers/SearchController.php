<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//--- Model ---//
use App\Barang;
use App\Penjualan;

class SearchController extends Controller
{

    public function findBarang(Request $request, $kolom, $keyword) {
    	if (!$request->ajax()) {
    		return null;
    	}

    	if ($kolom == 'kode') {
			$barang = DB::table('barang')
                    ->select('kode', 'nama_barang', 'harga_beli', 'stok')
					->where('kode', $keyword)
					->where('status','gudang')
                    ->first();

	        if ($barang !== null) {
	            return response()->json([
	                'nama' => $barang->nama_barang,
	                'kode' => $barang->kode,
					'harga_beli' => $barang->harga_beli,
					'stok' => $barang->stok,
	            ]);
	        }	
    	} elseif ($kolom == 'id') {
    		$barang = Barang::find($keyword);

	        if ($barang !== null) {
	            return response()->json([
	                'nama' => $barang->nama_barang,
	                'kode' => $barang->kode,
					'harga_beli' => $barang->harga_beli,
					'stok' => $barang->stok,
	            ]);
	        }	
    	}

    	return null;
	}
	
	public function findBarangtoko(Request $request, $kolom, $keyword) {
    	if (!$request->ajax()) {
    		return null;
    	}

    	if ($kolom == 'kode') {
			$barang = DB::table('barang')
					->join('kategori', 'barang.kategori_id', '=', 'kategori.id')
					->select('kode', 'nama_barang', 'harga_jual', 'stok','kategori.nama')
					->where('kode', $keyword)
					->where('status','toko')
                    ->first();

	        if ($barang !== null) {
	            return response()->json([
	                'nama' => $barang->nama_barang,
					'kode' => $barang->kode,
					'stok' => $barang->stok,
	                'harga_jual' => $barang->harga_jual,
					'stok' => $barang->stok,
					'kategori' => $barang->nama,
	            ]);
	        }	
    	} elseif ($kolom == 'id') {
    		$barang = Barang::find($keyword);

	        if ($barang !== null) {
	            return response()->json([
	                'nama' => $barang->nama_barang,
					'kode' => $barang->kode,
					'stok' => $barang->stok,
	                'harga_jual' => $barang->harga_jual,
					'stok' => $barang->stok,
					'kategori' => $barang->kategori->nama,
	            ]);
	        }	
    	}

    	return null;
	}
	
	public function menguntungkan() {


        $barangPenjualan = DB::table('barang')
                    ->join('detail_penjualan', 'detail_penjualan.barang_id', '=', 'barang.id')
                    ->select(DB::raw('count(barang.id) as jumlahjual, barang.id'))
                    ->groupBy('barang.id')
                    ->orderBy('jumlahjual', 'desc')
                    ->get();

        $cacah = 0;
        $data = [];

        foreach ($barangPenjualan as $i => $d) {
            $barang = Barang::find($d->id);
            $data[$cacah] = [
                $barang->kode, 
                $barang->nama_barang, 
                $d->jumlahjual
            ];

            $cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
	}
	
	public function daftarhabis() {

        $barang = Barang::where('stok', '<=', 0)->where('status','toko')->get();

        $cacah = 0;
        $data = [];

        foreach ($barang as $i => $d) {
            $data[$cacah] = [
                $d->kode, 
                $d->nama_barang, 
                $d->kategori->nama
            ];

            $cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
	}

	//belum difungsikan
	public function getkembalian() {

		$tanggal = date('Y-m-d');
		$penjualan = DB::table('penjualan')
		->select('kembalian')
		->where('tgl_penjualan', $tanggal)
		->first();

		$uangku = $penjualan->kembalian;

		return response()->json($uangku); 

	}
	
	public function getSelectBarang(Request $request) {
        if (!$request->ajax()) {
            return null;
        }

        $barang = Barang::select(['id', 'kode', 'nama_barang','status'])->get();

        $data = [];
        $cacah = 0;
        foreach ($barang as $i => $d) {
                $data[$cacah] = [
                    $d->kode.'  |  '.$d->nama_barang. ' | '.$d->status, 
                    $d->id
                ];

                $cacah++; 
               
        }

        return response()->json([
            'data' => $data
        ]);
    }

}
