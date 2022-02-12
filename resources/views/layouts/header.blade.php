<ul class="nav navbar-nav">
  @if (Auth::user()->level === 'kasir')
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-dollar"></i> Penjualan <span class="caret"></span></a>
    <ul class="dropdown-menu">
    <li class="{{ Request::path() === 'penjualan' ? 'active' : '' }}"><a href="{{ url('penjualan') }}"><i class="fa fa-money"></i> Penjualan</a></li>
    <li class="{{ Request::path() === 'listpenjualan' ? 'active' : '' }}"><a href="{{ url('listpenjualan') }}"><i class="fa fa-list-ul"></i> List Penjualan</a></li>
    </ul>
  </li>
  <!--<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-database"></i> Master <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li class="{{ Request::path() === 'barang' ? 'active' : '' }}"><a href="{{ url('barang') }}"><i class="fa fa-database"></i> Barang</a></li>
      <li class="{{ Request::path() === 'kategori' ? 'active' : '' }}"><a href="{{ url('kategori')}}"><i class="fa fa-list-alt"></i> Kategori Barang</a></li>
    </ul>
  </li>-->
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-refresh"></i> Retur<span class="caret"></span></a>
    <ul class="dropdown-menu">
    <li class="{{ Request::path() === 'retur' ? 'active' : '' }}" ><a href="{{ url('retur') }}"><i class="fa fa-refresh"></i> Retur</a></li>
      <li class="{{ Request::path() === 'listretur' ? 'active' : '' }}"><a href="{{ url('listretur') }}"><i class="fa fa-list"></i> List Retur</a></li>
    </ul>
  </li>
  <li class="{{ Request::path() === 'stokopname' ? 'active' : '' }}"><a href="{{ url('stokopname')}}"><i class="fa fa-list-alt"></i> Stok Opname</a></li>

  <li class="{{ Request::path() === 'modalkasir' ? 'active' : '' }}"><a href="{{ url('modalkasir') }}"><i class="fa fa-money"></i> Uang Kasir</a></li>
  @endif
  @if(Auth::user()->level === 'gudang')
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-handshake-o"></i> Pembelian <span class="caret"></span></a>
    <ul class="dropdown-menu">
    <li class="{{ Request::path() === 'pembelian' ? 'active' : '' }}"><a href="{{ url('pembelian') }}"><i class="fa fa-send"></i> Pembelian</a></li>
    <li class="{{ Request::path() === 'listpembelian' ? 'active' : '' }}"><a href="{{ url('listpembelian') }}"><i class="fa fa-list-ul"></i> List Pembelian</a></li>
    </ul>
  </li>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-database"></i> Master <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li class="{{ Request::path() === 'barang' ? 'active' : '' }}"><a href="{{ url('barang') }}"><i class="fa fa-database"></i> Barang</a></li>
      <li class="{{ Request::path() === 'kategori' ? 'active' : '' }}"><a href="{{ url('kategori')}}"><i class="fa fa-list-alt"></i> Kategori Barang</a></li>
    </ul>
  </li>
  <li class="{{ Request::path() === 'supplier' ? 'active' : '' }}"><a href="{{ url('supplier') }}"><i class="fa fa-users"></i> Supplier</a></li>
  <li class="{{ Request::path() === 'barcode' ? 'active' : '' }}"><a href="{{ url('barcode') }}"><i class="fa fa-barcode"></i> Cetak Barcode Barang</a></li>
  @endif
  @if(Auth::user()->level === 'admin')
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-dollar"></i> Penjualan <span class="caret"></span></a>
    <ul class="dropdown-menu">
    <li class="{{ Request::path() === 'listpenjualan' ? 'active' : '' }}"><a href="{{ url('listpenjualan') }}"><i class="fa fa-list-ul"></i> List Penjualan</a></li>
    </ul>
  </li>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-handshake-o"></i> Pembelian <span class="caret"></span></a>
    <ul class="dropdown-menu">
    <li class="{{ Request::path() === 'listpembelian' ? 'active' : '' }}"><a href="{{ url('listpembelian') }}"><i class="fa fa-list-ul"></i> List Pembelian</a></li>
    </ul>
  </li>
  <li class="{{ Request::path() === 'modalkasir' ? 'active' : '' }}"><a href="{{ url('modalkasir') }}"><i class="fa fa-money"></i> Uang Kasir</a></li>
  <li class="{{ Request::path() === 'user' ? 'active' : '' }}"><a href="{{ url('user')}}"><i class="fa fa-user-circle"></i> User</a></li>
  <li class="{{ Request::path() === 'supplier' ? 'active' : '' }}"><a href="{{ url('supplier') }}"><i class="fa fa-users"></i> Supplier</a></li>
  <li class="{{ Request::path() === 'manajemen' ? 'active' : '' }}"><a href="{{ url('manajemen') }}"><i class="fa fa-file-o"></i> Kelola Data</a></li>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-file"></i> Laporan <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li class="{{ Request::path() === 'laporanhistory' ? 'active' : '' }}"><a href="{{ url('laporanhistory') }}"><i class="fa fa-history"></i> Laporan Histori Stok</a></li>
      <li class="{{ Request::path() === 'laporanbarang' ? 'active' : '' }}"><a href="{{ url('laporanbarang')}}"><i class="fa fa-cubes"></i> Laporan Cetak Barang</a></li>
    <!--  <li class="{{ Request::path() === 'laporanlaba' ? 'active' : '' }}"><a href="{{ url('laporanlaba')}}"><i class="fa fa-file-text-o"></i> Laporan Rugi Laba</a></li>-->
      <li class="{{ Request::path() === 'laporanopname' ? 'active' : '' }}"><a href="{{ url('laporanopname')}}"><i class="fa fa-list-alt"></i> Laporan Stok Opname</a></li>
    <!--  <li class="{{ Request::path() === 'laporanhilang' ? 'active' : '' }}"><a href="{{ url('laporanhilang')}}"><i class="fa fa-list-alt"></i> Laporan Barang Hilang</a></li>-->
      <li class="{{ Request::path() === 'laporanpenjualan' ? 'active' : '' }}"><a href="{{ url('laporanpenjualan')}}"><i class="fa fa-file-text-o"></i> Laporan Penjualan</a></li>
      <li class="{{ Request::path() === 'chartpenjualan' ? 'active' : '' }}"><a href="{{ url('chartpenjualan')}}"><i class="fa fa-bar-chart"></i> Laporan Grafik Penjualan</a></li>
      <li class="{{ Request::path() === 'laporanpembelian' ? 'active' : '' }}"><a href="{{ url('laporanpembelian')}}"><i class="fa fa-file-text-o"></i> Laporan Pembelian</a></li>
      <li class="{{ Request::path() === 'chartpembelian' ? 'active' : '' }}"><a href="{{ url('chartpembelian')}}"><i class="fa fa-bar-chart"></i> Laporan Grafik Pembelian</a></li>
      <li class="{{ Request::path() === 'laporanretur' ? 'active' : '' }}"><a href="{{ url('laporanretur') }}"><i class="fa fa-file-text-o"></i> Laporan Retur</a></li>
      <!--<li class="{{ Request::path() === 'laporanabsen' ? 'active' : '' }}"><a href="{{ url('laporanabsen') }}"><i class="fa fa-file-text-o"></i> Laporan Absen</a></li>-->
      <li class="{{ Request::path() === 'laporanmodalkasir' ? 'active' : '' }}"><a href="{{ url('laporanmodalkasir') }}"><i class="fa fa-file-text-o"></i> Laporan Uang Modal Kasir</a></li>
    </ul>
  </li>
  @endif



<!--  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-money"></i> Gaji <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li class="{{ Request::path() === 'gaji' ? 'active' : '' }}"><a href="{{ url('gaji') }}"><i class="fa fa-dollar"></i> Gaji</a></li>
      <li class="{{ Request::path() === 'nominal' ? 'active' : '' }}"><a href="{{ url('nominal') }}"><i class="fa fa-plus"></i> Nominal Gaji</a></li>
    </ul>
  </li>-->

</ul>
<ul class="nav navbar-nav navbar-right animated zoomIn" style="margin-right:5px;">
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
      aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span></a>
    <ul class="dropdown-menu">
      <li class="{{ Request::path() === 'detailuser' ? 'active' : '' }}"><a href="{{ url('detailuser')}}"><i class="fa fa-user"></i> Detail Profil</a></li>
    <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
    </ul>
  </li>
</ul>