<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Charts;
use App\Penjualan;
use DB;
use Calendar;

class ChartpenjualanController extends Controller
{
    public function chartpenjualan()
    {
        
        $penjualan = Penjualan::select(
			DB::raw('sum(total_bayar) as total'),
			DB::raw('MONTH(tgl_penjualan) as bulan'),
            DB::raw("DATE_FORMAT(tgl_penjualan,'%M %Y') as bulanstring")
                )
            ->groupBy('bulanstring','bulan')
            ->orderBy('bulan','asc')
            ->get();

    $chart = Charts::database($penjualan, 'area', 'highcharts')
            ->title("Laporan Grafik Pendapatan Penjualan Per-Bulan")
            ->elementLabel("Total Penjualan")
            ->dimensions(300, 500)
            ->groupBy('bulanstring')
            ->responsive(true)
            ->values($penjualan->pluck('total'));
    
    $jumlahpenjualan = Penjualan::where(DB::raw("(DATE_FORMAT(tgl_penjualan,'%Y'))"),date('Y'))->get();
    $chartjumlah = Charts::database($jumlahpenjualan, 'bar', 'highcharts')
			      ->title("Laporan Jumlah Transaksi Per-Bulan")
			      ->elementLabel("Jumlah Transaksi Penjualan")
			      ->dimensions(1000, 500)
			      ->responsive(true)
                  ->groupByMonth(date('Y'), true);
                  
                  $events = [];
                  $data = Penjualan::select(
                    DB::raw('sum(total_bayar) as total'),
                    DB::raw('tgl_penjualan')
          )
          ->groupBy('tgl_penjualan')
          ->get();                  if($data->count()) {
                      foreach ($data as $key => $value) {
                          $events[] = Calendar::event(
                              'Rp.'.number_format($value->total),
                              true,
                              new \DateTime($value->tgl_penjualan),
                              new \DateTime($value->tgl_penjualan.' +1 day')
                          );
                      }
                  }
                  $calendar = Calendar::addEvents($events);

    return view('laporan.grafikpenjualan.index',compact('chart', 'chartjumlah','calendar'));
    }
}
