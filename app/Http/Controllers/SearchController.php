<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//--- Model ---//
use App\Barang;
use App\Penjualan;
use App\Detailpenjualan;
use Carbon\Carbon;
use App\Gaji;
use Auth;
use App\SementaraRetur;

class SearchController extends Controller
{
	public function cekhakakses()
	{
		 return response()->json(Auth::user()->level);
	}

    public function findBarang(Request $request, $kolom, $keyword) {
    	if (!$request->ajax()) {
    		return null;
    	}

    	if ($kolom == 'kode') {
			$barang = DB::table('barang')
                    ->select('kode', 'nama_barang', 'harga_beli', 'stok')
					->where('kode', $keyword)
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
					->select('kode', 'nama_barang', 'harga_jual', 'stok_toko','kategori.nama')
					->where('kode', $keyword)
                    ->first();

	        if ($barang !== null) {
	            return response()->json([
	                'nama' => $barang->nama_barang,
					'kode' => $barang->kode,
	                'harga_jual' => $barang->harga_jual,
					'stok' => $barang->stok_toko,
					'kategori' => $barang->nama,
	            ]);
	        }	
    	} elseif ($kolom == 'id') {
    		$barang = Barang::find($keyword);
	        if ($barang !== null) {
	            return response()->json([
	                'nama' => $barang->nama_barang,
					'kode' => $barang->kode,
	                'harga_jual' => $barang->harga_jual,
					'stok' => $barang->stok_toko,
					'kategori' => $barang->kategori->nama,
	            ]);
	        }	
    	}

    	return null;
	}
	
	public function menguntungkan() {


        $barangPenjualan = DB::table('barang')
                    ->join('detail_penjualan', 'detail_penjualan.barang_id', '=', 'barang.id')
                    ->select(DB::raw('sum(detail_penjualan.qty) as jumlahjual, barang.id'))
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

        $barang = Barang::where('stok_toko', '<=', 0)->get();

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

		$tanggal = date('Y-m-d H:i:s');
		$tglsekarang = date('Y-m-d');
	//	$penjualan = DB::table('penjualan')
		//->select('kembalian')
	//	->where('tgl_penjualan', $tanggal)
	//	->first();
	//	$barang = Barang::where('kode', '=', 'BA00001', 'and')->where('status', '=', 'gudang')->first();

		//$uangku = $penjualan->kembalian;

		//$uangkasir = DB::table('uang_modal_kasir')->select('uang_akhir')->where('tanggal', $tanggal)->first();
		//$hasilakhir = $uangkasir->uang_akhir;

		$penjualan = Detailpenjualan::whereDate('created_at', '=', Carbon::today()->toDateString())->get();

		$sekarang = Carbon::now()->addMonth(1)->format('m');    
		$gaji = Gaji::where('user_id', 1)->where('tgl_gajian', '>=', Carbon::now()->startOfMonth())->get();
		
		$users = DB::table('gaji')
        ->whereMonth('created_at', $tanggal)
		->get();
		
		$orders = Penjualan::select(
			DB::raw('sum(total_bayar) as total'),
			DB::raw('MONTH(tgl_penjualan) as bulan'),
            DB::raw("DATE_FORMAT(tgl_penjualan,'%M %Y') as bulanstring")
  )
  ->groupBy('bulanstring','bulan')
  ->orderBy('bulan','asc')
  ->get();

  $sementararetur = SementaraRetur::where('noretur', 'RE005')->get();


  $succes = 'success';

		return response()->json($sementararetur); 

	}
	
	public function getSelectBarang(Request $request) {
        if (!$request->ajax()) {
            return null;
        }

        $barang = Barang::select(['id', 'kode', 'nama_barang'])->get();

        $data = [];
        $cacah = 0;
        foreach ($barang as $i => $d) {
                $data[$cacah] = [
                    $d->kode.'  |  '.$d->nama_barang, 
                    $d->id
                ];

                $cacah++; 
               
        }

        return response()->json([
            'data' => $data
        ]);
    }

}
