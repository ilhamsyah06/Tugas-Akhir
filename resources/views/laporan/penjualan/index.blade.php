@extends('layouts.master')

@section('title','Laporan Penjualan')
    
@php
$tanggal = date('Y-m-d');
    $profithariini = DB::table('detail_penjualan')->join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')->where('tgl_penjualan', $tanggal)->sum('profit');
    $profitkeseluruhan = DB::table('detail_penjualan')->join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')->sum('profit');
@endphp
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card-counter pendapatjualharini">
              <i class="fa fa-money"></i>
            <span class="count-numbers">Rp.<span class="counter">{{ number_format($profithariini) }}</span>,-</span>
              <span class="count-name">Profit Hari Ini</span>
            </div>
          </div>
        
          <div class="col-md-3">
            <div class="card-counter pendapatjualkeseluruhan">
              <i class="fa fa-money"></i>
            <span class="count-numbers">Rp.<span class="counter">{{ number_format($profitkeseluruhan) }} </span>,-</span>
              <span class="count-name">Profit Keseluruhan</span>
            </div>
          </div>
    </div>
@endsection