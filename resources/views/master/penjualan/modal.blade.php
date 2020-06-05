<div class="modal fade" id="modalCariBarang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Cari Data Barang</h4>
            </div>
            <div class="modal-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="tabelcaritoko">
                    <thead>
                        <tr>
                            <th class="col-md-2">Kode</th>
                            <th class="col-md-3">Nama</th>
                            <th class="col-md-2">Jenis</th>
                            <th class="col-md-2">Harga jual</th>
                            <th class="col-md-1">Stok Toko</th>
                            <th class="col-md-2">Pilih</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i>
                    Batal</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Ubah Data Detail Penjualan</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idubah">
                <form role="form">
                    <fieldset>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label for="barangubah">Kode :</label>
                                            {!! Form::text('barangubah', null, ['class' => 'form-control',
                                            'id'=>'barangubah', 'disabled', 'placeholder' => 'Kode Barang']) !!}
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="namaubah">Nama :</label>
                                            {!! Form::text('namaubah', null, ['class' => 'form-control',
                                            'id'=>'namaubah', 'disabled', 'placeholder' => 'Nama Barang']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label for="harga_beliubah">Harga Jual :</label>
                                            {!! Form::text('harga_jualubah', null, ['id'=>'harga_jualubah', 'class' =>
                                            'form-control inputanangka']) !!}
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="qtyubah">Qty :</label>
                                            {!! Form::text('qtyubah', null, ['class' => 'form-control inputanangka', 'id'=>'qtyubah', 'placeholder' => 'QTY']) !!}        
                                        </div>
                                        <div class="col-sm-5">
                                            <label for="totalubah">Sub Total :</label>
                                                {!! Form::text('totalubah', null, ['id'=>'totalubah', 'class' => 'form-control', 'disabled', 'placeholder' => 'Sub Total']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label for="harga_beliubah">Diskon Item Rp. :</label>
                                        {!! Form::text('diskonubah', null, ['id'=>'diskonubah', 'class' =>'form-control inputanangka']) !!}
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="stokubah">Stok :</label>
                                        {!! Form::text('stokubah', null, ['id'=>'stokubah', 'class' =>'form-control inputanangka', 'readonly']) !!}
                                    </div>
                                </div>
                                </div>
                            </div>
                    </fieldset>
                </form>

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary btn-flat" id="simpanubah"><i class="fa fa-save"></i> Simpan
                    Perubahan</a>
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i>
                    Batal</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div>


<!-- Modal Struk -->
<div class="modal fade" id="modalStruk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-print"></i> Print Struk Penjualan</h4>
            </div>
            <div class="modal-body">
                <p style="font-weight: bold;">Print Struk Penjualan ?</p>
            <div class="modal-footer">
              <!--  <button class="print-button"><span class="print-icon"></span></button> -->
                <a href="#" class="btn btn-primary btn-flat" id="printstruk"><i class="fa fa-save"></i> Print Struk (F2)</a>
                <a href="#" class="btn btn-warning btn-flat" id="batalstruk"><i class="fa fa-close"></i> Tidak</a>
                <!-- <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Tidak</button> -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
</div>

<!-- Modal 1 -->
<div class="modal fade" id="modalBantu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation"> Bantuan Transaksi Penjualan</i></h4>
        </div>
        <div class="modal-body">
          <h4 style="font-style: bold;"><i class="fa fa-keyboard-o"></i> Shortcut Keyboard :</h5>
            <h5 style="font-style: bold;">SPACE : Tekan <code>SPACE</code> Untuk Menambah Barang Di Keranjang.</h5>
            <h5 style="font-style: bold;">ENTER : Tekan <code>ENTER</code> Untuk Menyimpan Transaksi.</h5>
            <h5 style="font-style: bold;">F7 : Tekan <code>F7</code> Untuk Fokus Ke Jumlah Bayar.</h5>
            <h5 style="font-style: bold;">CTRL : Tekan <code>CTRL</code> Untuk Menekan Tombol Cari Barang.</h5>
            <h5 style="font-style: bold;">F5 : Tekan <code>F5</code> Untuk Me-refresh Keranjang.</h5>
            <h5 style="font-style: bold;">F8 : Tekan <code>F8</code> Untuk Fokus Ke Diskon.</h5>
            <h5 style="font-style: bold;">F1 : Tekan <code>F1</code> Untuk Fokus Ke Kode Barang.</h5>
            <h5 style="font-style: bold;">F4 : Tekan <code>F4</code> Untuk Fokus Ke Qty Barang.</h5>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" id="deleteNo" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
        </div>
      </div>
    </div>
  </div>
    

