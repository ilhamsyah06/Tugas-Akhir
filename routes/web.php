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

Route::group(['middleware' => ['auth', 'checkLevel:admin,kasir']], function () {
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
    Route::post('barang/barangtoko','BarangController@barangtoko')->name('barangtoko');

    //----// Route Barang Gudang 
    Route::resource('baranggudang','BaranggudangController');
    Route::get('api/baranggudang','BaranggudangController@apiBarangGudang')->name('api.baranggudang');

    //Route Uang Modal Kasir
    Route::resource('modalkasir','UangmodalkasirController');
    Route::get('api/modalkasir','UangmodalkasirController@apiModalkasir')->name('api.modalkasir');

    //Route Absen
    Route::post('/absenhariini','AbsenController@absenmasuk')->name('absenhariini');
    Route::get('absen','AbsenController@laporan')->name('absen');

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
    Route::get('totalbarang', 'PenjualanController@totalbarang');
    Route::get('strukjual/{kode}', 'PenjualanController@strukjual');
    //---------------------------------------------------------------//
    Route::get('listpenjualan','PenjualanController@listpenjualan');
    Route::get('datapenjualan','PenjualanController@datapenjualan');
    Route::get('getdetailpenjualan', 'PenjualanController@getdetailpenjualan');
    Route::post('siapkankoreksipenjualan', 'PenjualanController@siapkanKoreksi');
    
    //Route dashboard menguntungkan 
    Route::get('menguntungkan', 'SearchController@menguntungkan')->name('menguntungkan');
    Route::get('daftarhabis', 'SearchController@daftarhabis')->name('daftarhabis');
    Route::get('getkembalian', 'SearchController@getkembalian');//belum difungsikan

    //Route gaji
    Route::resource('gaji','GajiController');
    Route::get('userapi', 'GajiController@userapi')->name('userapi');

    //Route Nominal Gaji
    Route::resource('nominal','NominalController');
    Route::get('nominalapi','NominalController@nominalapi')->name('nominalapi');

    //Route Laporan penjualan
    Route::get('laporanpenjualan','LaporanpenjualanController@index')->name('laporanpenjualan');

    //Route Laporan Histori
    Route::get('laporanhistory','LaporanhistoryController@index')->name('laporanhistory');
    Route::get('histories', 'LaporanHistoryController@datahistories');
    Route::get('get_select_barang', 'SearchController@getSelectBarang');
    Route::post('history', 'LaporanHistoryController@previewcetak');


});