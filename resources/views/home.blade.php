@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

@php
$tanggal = date('Y-m-d');
  $totalkategori = DB::table('kategori')->count();//menghitung jumlah data yang ada tabel kategori
  $totaluser = DB::table('users')->count();
  $totalsupplier = DB::table('supplier')->count();
  $totalbarangtoko = DB::table('barang')->where('status','toko')->count();
  $totalbaranggudang = DB::table('barang')->where('status','gudang')->count();
  $totalpembelian = DB::table('pembelian')->count();
  $totalpenjualan = DB::table('penjualan')->count();
  $totalpembelianhariini = DB::table('pembelian')->where('tgl_pembelian', $tanggal)->count();
  $totalpenjualanharini = DB::table('penjualan')->where('tgl_penjualan', $tanggal)->count();
  $total_bayar = DB::table('penjualan')->where('tgl_penjualan', $tanggal)->sum('total_bayar');
  $total_bayar_keseluruhan = DB::table('penjualan')->sum('total_bayar');
  $total_beli = DB::table('pembelian')->where('tgl_pembelian', $tanggal)->sum('total_bayar');
  $total_beli_keseluruhan = DB::table('pembelian')->sum('total_bayar');

@endphp

@if ($count === 1 ) 

@else  
 <script>
 swal({
      type: 'info',
      title: 'Silahkan Absen Untuk Hari Ini !',
      text: 'Silahkan Pergi Ke Menu Detail Profil Untuk Absen.',
      timer: 3000
  }).catch(function(timeout) { });
 </script>
@endif
<div class="row">
  <div class="col-md-3">
    <div class="card-counter pendapatjualharini">
      <i class="fa fa-money"></i>
    <span class="count-numbers">Rp.<span class="counter">{{ number_format($total_bayar) }}</span>,-</span>
      <span class="count-name">Pendapatan Jual Hari Ini</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter pendapatjualkeseluruhan">
      <i class="fa fa-money"></i>
    <span class="count-numbers">Rp.<span class="counter">{{ number_format($total_bayar_keseluruhan) }}</span>,-</span>
      <span class="count-name">Pendapatan Jual Keseluruhan</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter pendapatanbeliharini">
      <i class="fa fa-money"></i>
      <span class="count-numbers">Rp.<span class="counter">{{ number_format($total_beli) }}</span>,-</span>
      <span class="count-name">Pengeluaran Beli Hari Ini</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter pendapatanbelikeseluruhan">
      <i class="fa fa-money"></i>
      <span class="count-numbers">Rp.<span class="counter">{{ number_format($total_beli_keseluruhan) }}</span>,-</span>
      <span class="count-name">Pengeluaran Beli Keseluruhan</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter uangmodal">
      <i class="fa fa-money"></i>
      @foreach ($uangawal as $item)
      <span class="count-numbers">Rp.<span class="counter">{{ number_format($item->uang_akhir) }}</span>,-</span>
      @endforeach
      <span class="count-name">Uang Modal Kasir</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter toko">
      <i class="fa fa-database"></i>
    <span class="count-numbers counter">{{ $totalbarangtoko }}</span>
      <span class="count-name">Barang Toko</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter gudang">
      <i class="fa fa-database"></i>
      <span class="count-numbers counter">{{ $totalbaranggudang }}</span>
      <span class="count-name">Barang Gudang</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter user">
      <i class="fa fa-users"></i>
    <span class="count-numbers counter">{{ $totaluser }}</span>
      <span class="count-name">User</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter category">
      <i class="fa fa-sort-amount-desc"></i>
    <span class="count-numbers counter">{{ $totalkategori }}</span>
      <span class="count-name">Kategori Barang</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter supplier">
      <i class="fa fa-user-o"></i>
    <span class="count-numbers counter">{{ $totalsupplier }}</span>
      <span class="count-name">Supplier</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter retur">
      <i class="fa fa-refresh"></i>
    <span class="count-numbers counter">7870</span>
      <span class="count-name">Retur Penjualan Hari Ini</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter returkeseluruhan">
      <i class="fa fa-refresh"></i>
    <span class="count-numbers counter">7870</span>
      <span class="count-name">Retur Penjualan Keseluruhan</span>
    </div>
  </div>

</div>

<div class="row">
  <div class="col-md-3">
    <div class="card-counter penjualanhariini">
      <i class="fa fa-cart-plus"></i>
    <span class="count-numbers counter">{{ $totalpenjualanharini }}</span>
      <span class="count-name">Penjualan Hari Ini</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter pembelianharini">
      <i class="fa fa-cart-plus"></i>
    <span class="count-numbers counter">{{ $totalpembelianhariini }}</span>
      <span class="count-name">Pembelian Hari Ini</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter penjulankeseluruhan">
      <i class="fa fa-shopping-cart"></i>
    <span class="count-numbers counter">{{ $totalpenjualan }}</span>
      <span class="count-name">Penjualan Keseluruhan</span>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card-counter pembeiankeseluruhan">
      <i class="fa fa-shopping-cart"></i>
    <span class="count-numbers counter">{{ $totalpembelian }}</span>
      <span class="count-name">Pembelian Keselururhan</span>
    </div>
  </div>

</div>

<div class="row">
  <div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
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
@endsection

@section('footer')
<script>
  jQuery(document).ready(function($) {
      $('.counter').counterUp({
          delay: 10,
          time: 1000
      });
  });
</script>
@endsection
