@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

@php
  $tanggal = date('Y-m-d');
  $totalkategori = DB::table('kategori')->count();//menghitung jumlah data yang ada tabel kategori
  $totaluser = DB::table('users')->count();
  $totalsupplier = DB::table('supplier')->count();
  $totalbarang = DB::table('barang')->count();
  $totalpembelian = DB::table('pembelian')->count();
  $totalpenjualan = DB::table('penjualan')->count();
  $totalpembelianhariini = DB::table('pembelian')->where('tgl_pembelian', $tanggal)->count();
  $totalpenjualanharini = DB::table('penjualan')->where('tgl_penjualan', $tanggal)->count();
  $total_bayar = DB::table('penjualan')->where('tgl_penjualan', $tanggal)->sum('total_bayar');
  $total_bayar_keseluruhan = DB::table('penjualan')->sum('total_bayar');
  $total_beli = DB::table('pembelian')->where('tgl_pembelian', $tanggal)->sum('total_bayar');
  $total_beli_keseluruhan = DB::table('pembelian')->sum('total_bayar');
  $totalreturhariini = DB::table('retur')->where('tgl_retur', $tanggal)->count();
  $totalreturkeseluruhan = DB::table('retur')->count();
@endphp

  @if (Auth::user()->level === 'admin')
  <div class="col-md-3">
    <div class="card-counter primary animated flipInX">
      <i class="fa fa-money"></i>
      <span class="count-numbers">Rp.<span>{{ number_format($total_bayar) }}</span>,-</span>
      <span class="count-name">Pendapatan Jual Hari Ini</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter success animated flipInX">
      <i class="fa fa-money"></i>
      <span class="count-numbers">Rp.<span>{{ number_format($total_bayar_keseluruhan) }}</span>,-</span>
      <span class="count-name">Pendapatan Jual Keseluruhan</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter warning animated flipInX">
      <i class="fa fa-money"></i>
      <span class="count-numbers">Rp.<span >{{ number_format($total_beli) }}</span>,-</span>
      <span class="count-name">Pengeluaran Beli Hari Ini</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter danger animated flipInX">
      <i class="fa fa-money"></i>
      <span class="count-numbers">Rp.<span >{{ number_format($total_beli_keseluruhan) }}</span>,-</span>
      <span class="count-name">Pengeluaran Beli Keseluruhan</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter primary animated flipInX">
      <i class="fa fa-users"></i>
    <span class="count-numbers">{{ $totaluser }}</span>
      <span class="count-name">User</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter success animated flipInX">
      <i class="fa fa-user-o"></i>
    <span class="count-numbers">{{ $totalsupplier }}</span>
      <span class="count-name">Supplier</span>
    </div>
  </div>
  @endif

  @if (Auth::user()->level === 'kasir')
  <div class="row">
    <div class="col-md-3">
      <div class="card-counter primary animated flipInX">
        <i class="fa fa-cart-plus"></i>
      <span class="count-numbers">{{ $totalpenjualanharini }}</span>
        <span class="count-name">Penjualan Hari Ini</span>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card-counter warning animated flipInX">
        <i class="fa fa-shopping-cart"></i>
      <span class="count-numbers">{{ $totalpenjualan }}</span>
        <span class="count-name">Penjualan Keseluruhan</span>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card-counter success animated flipInX">
        <i class="fa fa-refresh"></i>
      <span class="count-numbers">{{ $totalreturkeseluruhan }}</span>
        <span class="count-name">Retur Penjualan Hari Ini</span>
      </div>
    </div>
  
    <div class="col-md-3">
      <div class="card-counter danger animated flipInX">
        <i class="fa fa-refresh"></i>
      <span class="count-numbers">{{ $totalreturkeseluruhan }}</span>
        <span class="count-name">Retur Penjualan Keseluruhan</span>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card-counter primary animated flipInX">
        <i class="fa fa-money"></i>
        @foreach ($uangawal as $item)
        <span class="count-numbers">Rp.<span>{{ number_format($item->uang_akhir) }}</span>,-</span>
        @endforeach
        <span class="count-name">Uang Modal Kasir</span>
      </div>
    </div>
  </div>
    <br>
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h4><i class="fa fa-list-alt"></i> Barang Terlaris</h4>
            </div>
            <div class="panel-body">
                <table id="dataTableMenguntungkan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
      <div class="panel panel-default">
          <div class="panel-heading">
              <h4><i class="fa fa-list-alt"></i> Stok Barang Habis</h4>
          </div>
          <div class="panel-body">
              <table id="dataTableHabis" class="table table-bordered table-striped">
                  <thead>
                      <tr>
                          <th>Kode</th>
                          <th>Nama</th>
                          <th>Kategori</th>
                      </tr>
                  </thead>
                  <tbody>
    
                  </tbody>
              </table>
          </div>
      </div>
    </div>
    </div>
  @endif

@if (Auth::user()->level === 'gudang')
<div class="col-md-3">
  <div class="card-counter primary animated flipInX">
    <i class="fa fa-database"></i>
  <span class="count-numbers">{{ $totalbarang }}</span>
    <span class="count-name">Barang</span>
  </div>
</div>

<div class="col-md-3">
  <div class="card-counter success animated flipInX">
    <i class="fa fa-sort-amount-desc"></i>
  <span class="count-numbers">{{ $totalkategori }}</span>
    <span class="count-name">Kategori Barang</span>
  </div>
</div>

<div class="col-md-3">
  <div class="card-counter warning animated flipInX">
    <i class="fa fa-user-o"></i>
  <span class="count-numbers">{{ $totalsupplier }}</span>
    <span class="count-name">Supplier</span>
  </div>
</div>

<div class="col-md-3">
  <div class="card-counter danger animated flipInX">
    <i class="fa fa-cart-plus"></i>
  <span class="count-numbers">{{ $totalpembelianhariini }}</span>
    <span class="count-name">Pembelian Hari Ini</span>
  </div>
</div>

<div class="col-md-3">
  <div class="card-counter primary animated flipInX">
    <i class="fa fa-shopping-cart"></i>
  <span class="count-numbers">{{ $totalpembelian }}</span>
    <span class="count-name">Pembelian Keseluruhan</span>
  </div>
</div>
@endif

@endsection
