<div class="modal fade" id="modalCariSupplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-user"></i> Cari Data Supplier</h4>
            </div>
            <div class="modal-body">
                <table width="100%" class="table table-striped table-bordered table-hover"
                    id="dataTableBuilderCariSupplier">
                    <thead>
                        <tr>
                            <th class="col-md-2">Nama</th>
                            <th class="col-md-3">Email</th>
                            <th class="col-md-2">Nomor HP</th>
                            <th class="col-md-2">Alamat</th>
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


<div class="modal fade" id="modalCariBarang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Cari Data Barang</h4>
            </div>
            <div class="modal-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="tabelcari">
                    <thead>
                        <tr>
                            <th class="col-md-2">Kode</th>
                            <th class="col-md-3">Nama</th>
                            <th class="col-md-2">Jenis</th>
                            <th class="col-md-2">Harga Beli</th>
                            <th class="col-md-1">Stok Gudang</th>
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
                <h4 class="modal-title" id="myModalLabel">Ubah Data Detail Pembelian</h4>
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
                                            <label for="harga_beliubah">Harga beli :</label>
                                            {!! Form::text('harga_beliubah', null, ['id'=>'harga_beliubah', 'class' =>
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