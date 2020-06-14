<div class="modal fade" id="modalCari" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cubes"></i> Cari Data Barang</h4>
            </div>
            <div class="modal-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilderCari">
                            <thead>
                            <tr>
                                <th class="col-md-2">Kode</th>
                                <th class="col-md-2">Nama</th>
                                <th class="col-md-3">Jenis</th>
                                <th class="col-md-2">H. Jual</th>
                                <th class="col-md-1">Stok</th>
                                <th class="col-md-2">Pilih</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modalyakin" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Simpan Stok !</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idkirim">
                <p style="font-weight: bold;">Apakah Anda Yakin Akan Menyimpan Data Stok Ini?<br/>Sebelum Meng-konfirmasi, Pastikan Mencetak Data Laporan Pengiriman Terlebih Dahulu. (Agar Mempermudah Dalam Merekap Data Pengiriman)<br/>
                {{--<p>One fine body&hellip;</p>--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
                {{--<button type="button" class="btn btn-outline">Save changes</button>--}}
                {!! link_to('#', $title='Saya Yakin Menyimpan Data Stok Toko', $attributes=['id'=>'yakinkirim', 'class'=>'btn btn-danger']) !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
