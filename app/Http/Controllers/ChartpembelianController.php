<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pembelian;
use Charts;
use DB;

class ChartpembelianController extends Controller
{
    public function chartpembelian()
    {
        $pembelian = Pembelian::select(
            DB::raw('sum(total_bayar) as total'),
            DB::raw('MONTH(tgl_pembelian) as bulan'),
            DB::raw("DATE_FORMAT(tgl_pembelian,'%M %Y') as bulanstring")
                )
            ->groupBy('bulanstring','bulan')
            ->orderBy('bulan','asc')
            ->get();
    
        $chartpembelian = Charts::database($pembelian, 'area', 'highcharts')
            ->title("Laporan Grafik Pengeluaran Pembelian Per-Bulan")
            ->elementLabel("Total Penjualan")
            ->dimensions(300, 500)
            ->groupBy('bulanstring')
            ->responsive(true)
            ->values($pembelian->pluck('total'));
    
        $jumlahpemblian = Pembelian::where(DB::raw("(DATE_FORMAT(tgl_pembelian,'%Y'))"),date('Y'))->get();
        $chartjumlah = Charts::database($jumlahpemblian, 'bar', 'highcharts')
            ->title("Laporan Jumlah Transaksi Per-Bulan")
            ->elementLabel("Jumlah Transaksi Pembelian")
            ->dimensions(1000, 500)
            ->responsive(true)
            ->groupByMonth(date('Y'), true);
    
    
        return view('laporan.grafikpembelian.index', compact('chartpembelian','chartjumlah'));
    }

}
