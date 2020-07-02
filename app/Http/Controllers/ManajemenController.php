<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Penjualan;
use App\Pembelian;
use App\History;
use App\Uang_modal_kasir;
use DB;

class ManajemenController extends Controller
{
    public function index()
    {
        return view('master.manajemen.index');
    }

    public function perbulanpenjualan(Request $request)
    {
        $jumlahpenjualan = Penjualan::where(DB::raw("(DATE_FORMAT(tgl_penjualan,'%Y-%m'))"), $request->tanggal)->delete();
        return redirect('/manajemen')->with(['success' => 'Data Penjualan "'.$request->tanggal.'" Berhasil Dihapus.']);
    }

    public function perbulanpembelian(Request $request)
    {
        $jumlahpembelian = Pembelian::where(DB::raw("(DATE_FORMAT(tgl_pembelian,'%Y-%m'))"), $request->tanggal)->delete();
        return redirect('/manajemen')->with(['success' => 'Data Pembelian "'.$request->tanggal.'" Berhasil Dihapus.']);
    }

    
    public function perbulanhistory(Request $request)
    {
        $jumlahhistory = History::where(DB::raw("(DATE_FORMAT(tgl,'%Y-%m'))"), $request->tanggal)->delete();
        return redirect('/manajemen')->with(['success' => 'Data History Stok "'.$request->tanggal.'" Berhasil Dihapus.']);
    }

    public function perbulanmodalkasir(Request $request)
    {
        $jumlahhistory = Uang_modal_kasir::where(DB::raw("(DATE_FORMAT(tanggal,'%Y-%m'))"), $request->tanggal)->delete();
        return redirect('/manajemen')->with(['success' => 'Data Uang Modal Kasir "'.$request->tanggal.'" Berhasil Dihapus.']);
    }
}
