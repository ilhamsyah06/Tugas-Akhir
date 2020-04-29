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

class LaporanlabaController extends Controller
{
    public function index()
    {
        return view('laporan.rugilaba.index');
    }

    public function getRekapLaba(Request $request) {
    	if (!$request->ajax()) {
            return null;
        }

        $input = $request->all();

        $data = [];

        $start = $input['start'];
        $end = $input['end'];
        $jenis = ($input['jenis'] != null && trim($input['jenis']) != '') ? Kategori::find($input['jenis']) : null;


        if ($start == '' || $end == '') {
            if ($jenis == null || trim($jenis) == '') {
                        $penjualan = DB::table('detail_penjualan')
                            ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                            ->select(DB::raw('SUM(detail_penjualan.harga * detail_penjualan.qty) as total'))
                            ->first();

                    $pembelian = DB::table('detail_pembelian')
                            ->join('pembelian', 'pembelian.id', '=', 'detail_pembelian.pembelian_id')
                            ->select(DB::raw('SUM(detail_pembelian.harga * detail_pembelian.qty) as total'))
                            ->first();
                    } else {
                        $penjualan = DB::table('detail_penjualan')
                        ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                        ->select(DB::raw('SUM(detail_penjualan.harga * detail_penjualan.qty) as total'))
                        ->first();

                $pembelian = DB::table('detail_pembelian')
                        ->join('pembelian', 'pembelian.id', '=', 'detail_pembelian.pembelian_id')
                        ->select(DB::raw('SUM(detail_pembelian.harga * detail_pembelian.qty) as total'))
                        ->first();
                    }
            
        } else {
            $arr_tgl_dari = explode ("/", $start, 3);
                    $arr_tgl_sampai= explode ("/", $end, 3);

                    $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
                    $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

                    if ($jenis == null || trim($jenis) == '') {
                        $penjualan = DB::table('detail_penjualan')
                            ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                            ->whereBetween('penjualan.tgl_penjualan',[$from, $to], 'and')
                            ->select(DB::raw('SUM(detail_penjualan.harga * detail_penjualan.qty) as total'))
                            ->first();

                        $pembelian = DB::table('detail_pembelian')
                            ->join('pembelian', 'pembelian.id', '=', 'detail_pembelian.pembelian_id')
                            ->whereBetween('pembelian.tgl_pembelian',[$from, $to], 'and')
                            ->select(DB::raw('SUM(detail_pembelian.harga * detail_pembelian.qty) as total'))
                            ->first();
                    } else {
                        $penjualan = DB::table('detail_penjualan')
                            ->join('barang', 'barang.id', '=', 'detail_penjualan.barang_id')
                            ->join('kategori', 'kategori.id', '=', 'barang.kategori_id')
                            ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                            ->where('kategori.id', $jenis->id)
                            ->whereBetween('penjualan.tgl_penjualan',[$from, $to], 'and')
                            ->select(DB::raw('SUM(detail_penjualan.harga * detail_penjualan.qty) as total'))
                            ->first();

                        $pembelian = DB::table('detail_pembelian')
                            ->join('barang', 'barang.id', '=', 'detail_pembelian.barang_id')
                            ->join('kategori', 'kategori.id', '=', 'barang.kategori_id')
                            ->join('pembelian', 'pembelian.id', '=', 'detail_pembelian.pembelian_id')
                            ->where('kategori.id', $jenis->id)
                            ->whereBetween('pembelian.tgl_pembelian',[$from, $to], 'and')
                            ->select(DB::raw('SUM(detail_pembelian.harga * detail_pembelian.qty) as total'))
                            ->first();
                    }
        } 

        if ($penjualan !== null) {
            $data['penjualan'] = $penjualan->total;
        }

        if ($pembelian !== null) {
            $data['pembelian'] = $pembelian->total;
        }

        // dd($data);

        return response()->json($data);

    }

    public function preview(Request $request) {
    	
        $input = $request->all();

        // dd($input);

        if (!isset($input['_token'])) {
            return redirect('/laporan/laba');
        } else {
            $start = $input['start'];
            $end = $input['end'];
            $jenis = ($input['jenis'] != null && trim($input['jenis']) != '') ? Kategori::find($input['jenis']) : null;

            $data = [];
            $cacah = 0;

            $periode = null;
            if ($start == '' || $end == '') {
                if ($jenis == null || trim($jenis) == '') {
                    $penjualan = DB::table('detail_penjualan')
                    ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                    ->select(DB::raw('SUM(detail_penjualan.harga * detail_penjualan.qty) as total'))
                    ->first();

            $pembelian = DB::table('detail_pembelian')
                    ->join('pembelian', 'pembelian.id', '=', 'detail_pembelian.pembelian_id')
                    ->select(DB::raw('SUM(detail_pembelian.harga * detail_pembelian.qty) as total'))
                    ->first();
                } else {
                    $periode = 'Kategori Barang : '.$jenis->nama;
                    $penjualan = DB::table('detail_penjualan')
                    ->join('barang', 'barang.id', '=', 'detail_penjualan.barang_id')
                    ->join('kategori', 'kategori.id', '=', 'barang.kategori_id')
                    ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                    ->where('kategori.id', $jenis->id)
                    ->whereBetween('penjualan.tgl_penjualan',[$from, $to], 'and')
                    ->select(DB::raw('SUM(detail_penjualan.harga * detail_penjualan.qty) as total'))
                    ->first();

                $pembelian = DB::table('detail_pembelian')
                    ->join('barang', 'barang.id', '=', 'detail_pembelian.barang_id')
                    ->join('kategori', 'kategori.id', '=', 'barang.kategori_id')
                    ->join('pembelian', 'pembelian.id', '=', 'detail_pembelian.pembelian_id')
                    ->where('kategori.id', $jenis->id)
                    ->whereBetween('pembelian.tgl_pembelian',[$from, $to], 'and')
                    ->select(DB::raw('SUM(detail_pembelian.harga * detail_pembelian.qty) as total'))
                    ->first();
                }
                
               
            } else {
                $periode = 'Periode : '.$start.' s/d '.$end;
                $arr_tgl_dari = explode ("/", $start, 3);
                $arr_tgl_sampai= explode ("/", $end, 3);

                $from = $arr_tgl_dari[2].'/'.$arr_tgl_dari[1].'/'.$arr_tgl_dari[0].' 00:00:00';
                $to = $arr_tgl_sampai[2].'/'.$arr_tgl_sampai[1].'/'.$arr_tgl_sampai[0].' 23:59:59';

                if ($jenis == null || trim($jenis) == '') {
                    $penjualan = DB::table('detail_penjualan')
                        ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                        ->whereBetween('penjualan.tgl_penjualan',[$from, $to], 'and')
                        ->select(DB::raw('SUM(detail_penjualan.harga * detail_penjualan.qty) as total'))
                        ->first();

                

                $pembelian = DB::table('detail_pembelian')
                        ->join('pembelian', 'pembelian.id', '=', 'detail_pembelian.pembelian_id')
                        ->whereBetween('pembelian.tgl_pembelian',[$from, $to], 'and')
                        ->select(DB::raw('SUM(detail_pembelian.harga * detail_pembelian.qty) as total'))
                        ->first();
                } else {
                    $penjualan = DB::table('detail_penjualan')
                    ->join('barang', 'barang.id', '=', 'detail_penjualan.barang_id')
                    ->join('kategori', 'kategori.id', '=', 'barang.kategori_id')
                    ->join('penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
                    ->where('kategori.id', $jenis->id)
                    ->whereBetween('penjualan.tgl_penjualan',[$from, $to], 'and')
                    ->select(DB::raw('SUM(detail_penjualan.harga * detail_penjualan.qty) as total'))
                    ->first();

                $pembelian = DB::table('detail_pembelian')
                    ->join('barang', 'barang.id', '=', 'detail_pembelian.barang_id')
                    ->join('kategori', 'kategori.id', '=', 'barang.kategori_id')
                    ->join('pembelian', 'pembelian.id', '=', 'detail_pembelian.pembelian_id')
                    ->where('kategori.id', $jenis->id)
                    ->whereBetween('pembelian.tgl_pembelian',[$from, $to], 'and')
                    ->select(DB::raw('SUM(detail_pembelian.harga * detail_pembelian.qty) as total'))
                    ->first();

                     $periode .= ', Kategori : '.$jenis->nama;
                }

                

                
            } 

            $totpenjualan = 0;
            if ($penjualan != null) {
                $totpenjualan = $penjualan->total;
            }($pembelian != null) ? $totpembelian = $pembelian->total : $totpembelian = 0;

            $totpendapatan = $totpenjualan;
            $totpengeluaran = $totpembelian;
            $totgrandtotal = $totpendapatan - $totpengeluaran;

                $pdf = App::make('dompdf.wrapper');
                
                    $pdf->loadView('laporan.rugilaba.print', 
                        [
                            'penjualan' => $totpenjualan,
                            'pembelian' => $totpembelian,
                            'pendapatan' => $totpendapatan,
                            'pengeluaran' => $totpengeluaran,
                            'grandtotal' => $totgrandtotal,
                            'periode' => $periode,
                        ]
                    );
               
                $pdf->setPaper('a4')->setWarnings(false);
                return $pdf->stream();
            
        }
    
}
}
