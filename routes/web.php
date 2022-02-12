<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'DashboardController@index')->middleware('auth');
Route::get('/home', 'DashboardController@index')->middleware('auth');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');


//--------------------------------------------------------------------------------------//

Route::group(['middleware' => ['auth', 'checkLevel:admin,gudang,kasir']], function () {
    //Route Kategori Barang
    Route::resource('kategori', 'KategoriController');
    Route::get('api/kategori', 'KategoriController@apiKategori')->name('api.kategori');
    Route::get('levelapi', 'KategoriController@levelapi')->name('levelapi');

    //Route Supplier
    Route::resource('supplier', 'SupplierController');
    Route::get('api/supplier', 'SupplierController@apiSupplier')->name('api.supplier');
    Route::get('carisupplier','SupplierController@carisupplier')->name('carisupplier');

    //Route User
    Route::resource('user', 'UserController');
    Route::get('api/user', 'UserController@apiUser')->name('api.user');
    Route::get('detailuser', 'UserController@detailuser')->name('detailuser');

    //Route Barcode
    Route::get('barcode','BarcodeController@index')->name('barcode');
    Route::post('viewbarcode','BarcodeController@viewbarcode')->name('viewbarcode');
    Route::get('caribarangbarcode','BarcodeController@caribarangbarcode')->name('caribarangbarcode');

    // Route Barang
    Route::resource('barang','BarangController');
    Route::get('api/barang','BarangController@apiBarang')->name('api.barang');
    Route::get('barangautokode', 'BarangController@getAutoKode');
    Route::get('barangtokohabis','BarangController@barangtokohabis')->name('barangtokohabis');
    Route::post('kirimstoktoko','BarangController@kirimstoktoko');

    //Stok Opaname
    Route::resource('stokopname', 'OpnameController',['except' => ['create', 'show', 'edit', 'update', 'destroy']]);

    //----// Route Barang Gudang 
    Route::resource('baranggudang','BaranggudangController');
    Route::get('api/baranggudang','BaranggudangController@apiBarangGudang')->name('api.baranggudang');

    //Route Uang Modal Kasir
    Route::resource('modalkasir','UangmodalkasirController');
    Route::get('api/modalkasir','UangmodalkasirController@apiModalkasir')->name('api.modalkasir');

    //Route Absen
    Route::post('/absenhariini','AbsenController@absenmasuk')->name('absenhariini');
    Route::get('absen','AbsenController@laporan')->name('absen');

    //Route Cek Hak Akses
    Route::get('cekhakakses', 'SearchController@cekhakakses');

    //Route Pembelian
    Route::resource('pembelian','PembelianController');
    Route::get('barangpembelian', 'PembelianController@barangpembelian');
    Route::get('getpembelianautocode','PembelianController@getpembelianautocode')->name('getpembelianautocode');
    Route::get('pembelians', 'PembelianController@pembelians');
    Route::get('listpembelian','PembelianController@listpembelian')->name('listpembelian');
    Route::get('getdetailbeli', 'PembelianController@getdetailbeli');
    Route::post('siapkankoreksipembelian', 'PembelianController@siapkanKoreksi');

    //Route Tabel Sementara pembelian
    Route::get('getsementara', 'SementaraController@getSementara');
    Route::post('sementara', 'SementaraController@store');
    Route::get('sementara/{id}/edit', 'SementaraController@edit');
    Route::put('sementara/{id}', 'SementaraController@update');
    Route::put('sementaratoko/{id}', 'SementaraController@updatetoko');
    Route::delete('sementara/{id}', 'SementaraController@destroy');

    //Route Mencari Barang Pembelian secara ajax
    Route::get('findbarang/{kolom}/{keyword}', 'SearchController@findBarang');
    Route::get('findbarangtoko/{kolom}/{keyword}', 'SearchController@findBarangtoko');

    //Route Penjualan
    Route::resource('penjualan','PenjualanController');
    Route::get('getsementarapenjualan', 'SementaraController@getSementarapenjualan');
    Route::get('getpenjualanautocode', 'PenjualanController@getpenjualanautocode');
    Route::get('barangpenjualan', 'PenjualanController@barangpenjualan');
    Route::post('sementarajual', 'SementaraController@storeJual');
    Route::get('sementarakoreksi/{id}/edit', 'SementaraController@editkoreksi');
    Route::get('totalbarang', 'PenjualanController@totalbarang');
    Route::get('strukjual/{kode}', 'PenjualanController@strukjual');
    //---------------------------------------------------------------//
    Route::get('listpenjualan','PenjualanController@listpenjualan');
    Route::get('datapenjualan','PenjualanController@datapenjualan');
    Route::get('getdetailpenjualan', 'PenjualanController@getdetailpenjualan');
    Route::get('getdetailretur', 'PenjualanController@getdetailretur');
    Route::post('siapkankoreksipenjualan', 'PenjualanController@siapkanKoreksi');

    //Routeprint
    Route::post('printbarcode', function(){
        return view('master.barang.page-cetak-barcode');
    });

    //RoutePengrimanStok
    Route::get('datalaporanpengiriman', 'HistoripengirimanController@datalaporanpengiriman');
    Route::post('cetakpengiriman', 'HistoripengirimanController@preview');
    Route::delete('simpandatastok/{id}', 'HistoripengirimanController@simpandatastok');
    
    //Route dashboard menguntungkan 
    Route::get('menguntungkan', 'SearchController@menguntungkan')->name('menguntungkan');
    Route::get('terlaris', 'SearchController@terlaris');
    Route::get('daftarhabis', 'SearchController@daftarhabis')->name('daftarhabis');
    Route::get('getkembalian', 'SearchController@getkembalian');//belum difungsikan

    //Route gaji
    Route::resource('gaji','GajiController');
    Route::get('gajiapi','GajiController@gajiapi')->name('gajiapi');
    Route::get('userapi', 'GajiController@userapi')->name('userapi');
    Route::get('gajiautokode', 'GajiController@getAutoKode');

    //Route Nominal Gaji
    Route::resource('nominal','NominalController');
    Route::get('nominalapi','NominalController@nominalapi')->name('nominalapi');

    //Route Laporan penjualan
    Route::get('laporanpenjualan','LaporanpenjualanController@index')->name('laporanpenjualan');
    Route::get('datalaporanpenjualan', 'LaporanpenjualanController@datalaporanpenjualan');
    Route::post('cetakpenjualan', 'LaporanpenjualanController@preview');
    Route::post('cetakterlaris', 'LaporanpenjualanController@previewterlaris');

    // keuangan rekap
    Route::get('laporanlaba', 'LaporanlabaController@index');
    Route::post('datalaba', 'LaporanlabaController@preview');
    Route::get('getrekaplaba', 'LaporanlabaController@getRekapLaba');

    //Route Laporan Retur
    Route::get('laporanretur','LaporanreturController@index');
    Route::get('datalaporanretur', 'LaporanreturController@datalaporanretur');
    Route::post('cetakretur', 'LaporanreturController@preview');

    //Route Laporan Barang
    Route::get('laporanbarang', 'LaporanbarangController@index');
    Route::get('datalaporanbarang', 'LaporanbarangController@datalaporanbarang');
    Route::post('cetakbarang', 'LaporanbarangController@preview');

    //Route Laporan Pembelian
    Route::get('laporanpembelian','LaporanpembelianController@index')->name('laporanpembelian');
    Route::get('datalaporanpembelian','LaporanpembelianController@datalaporanpembelian');
    Route::post('cetakpembelian', 'LaporanpembelianController@preview');

    //Route Laporan Uang Modal Kasir
    Route::get('laporanmodalkasir','LaporanmodalkasirController@index');
    Route::get('datamodalkasir','LaporanmodalkasirController@datamodalkasir');
    Route::post('cetakmodalkasir', 'LaporanmodalkasirController@preview');

    //Route Laporan Histori
    Route::get('laporanhistory','LaporanhistoryController@index')->name('laporanhistory');
    Route::get('histories', 'LaporanHistoryController@datahistories');
    Route::get('get_select_barang', 'SearchController@getSelectBarang');
    Route::post('history', 'LaporanHistoryController@previewcetak');

    // Laporan Stok opname
    Route::get('laporanopname', 'LaporanopnameController@index');
    Route::get('laporandataopname', 'LaporanopnameController@opnames');
    Route::post('cetakopname', 'LaporanopnameController@preview');

    //Route Laporan Absen
    Route::get('laporanabsen','LaporanabsenController@index');
    Route::get('get_select_user','LaporanabsenController@getselectuser');
    Route::get('datauser', 'LaporanabsenController@datauser');

    //Laporan Chart Penjualan
    Route::get('chartpenjualan','ChartpenjualanController@chartpenjualan');

    //laporan Chart Pembelian
    Route::get('chartpembelian','ChartpembelianController@chartpembelian');

    //Route Retur
    Route::resource('retur','ReturController');
    Route::get('returkode', 'ReturController@getAutoKode');
    Route::get('apipenjualan', 'ReturController@ApiPenjualan');
    Route::get('tambahretur/{id}/returadd', 'ReturController@tambahretur');
    Route::post('siapkanretur', 'ReturController@siapkanretur');
    Route::get('getsementararetur','ReturController@getsementararetur');
    Route::get('listretur','ReturController@listretur');
    Route::get('dataretur','ReturController@dataretur');
    //-----------------------------------------------------------//
    Route::post('retursemua','ReturController@retursemua');// kirim semua jumlah barang ke keranjang retur
    Route::post('kembalisemua','ReturController@kembalisemua');// kirim semua jumlah barang ke keranjang penjualan
    //-----------------------------------------------------------//
    Route::post('retursatu','ReturController@retursatu');//kirim satu ke keranjang retur
    Route::post('kembalisatu','ReturController@kembalisatu');//kirim satu ke keranjang penjualan

    //Route Menajemen Data
    Route::get('manajemen','ManajemenController@index')->name('manajemen');
    Route::post('hapusdatapenjualan','ManajemenController@perbulanpenjualan');
    Route::post('hapusdatapembelian','ManajemenController@perbulanpembelian');
    Route::post('hapusdatahistory','ManajemenController@perbulanhistory');
    Route::post('hapusdatamodalkasir','ManajemenController@perbulanmodalkasir');

});