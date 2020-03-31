<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">HAPUS DATA !!</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idHapus">
                <p style="font-weight: bold;">Apakah anda yakin akan menghapus Data ini?<br/>Jika menghapus, kemungkinan data lainnya akan berubah. <br/>Jika
                    anda tidak memahaminya, sebaiknya hubungi Administrator Sistem Anda!</p>
                {{--<p>One fine body&hellip;</p>--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
                {{--<button type="button" class="btn btn-outline">Save changes</button>--}}
                {!! link_to('#', $title='Saya Yakin Menghapus Data', $attributes=['id'=>'yakinhapus', 'class'=>'btn btn-danger']) !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!------------------------- MERETUR / KEMBALI BARANG SEMUA ----------------------->

<div class="modal fade" id="modalReturSemua" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> <!-- modal retur semua -->
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Retur Semua jumlah Barang !!</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="retursemua">
                <p style="font-weight: bold;">Apakah anda yakin meretur jumlah barang semua?</p>
                {{--<p>One fine body&hellip;</p>--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Batalkan</button>
                {{--<button type="button" class="btn btn-outline">Save changes</button>--}}
                {!! link_to('#', $title='Saya Yakin Meretur semua', $attributes=['id'=>'yakinretursemua', 'class'=>'btn btn-danger']) !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalKembaliSemua" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> <!-- modal kembali semua -->
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Kembalikan Semua jumlah Barang !!</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="kembalisemua">
                <p style="font-weight: bold;">Apakah anda yakin Mengembalikan jumlah barang semua?</p>
                {{--<p>One fine body&hellip;</p>--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Batalkan</button>
                {{--<button type="button" class="btn btn-outline">Save changes</button>--}}
                {!! link_to('#', $title='Saya Yakin Kembalikan Semua', $attributes=['id'=>'yakinkembalisemua', 'class'=>'btn btn-danger']) !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!--------------------- MERETUR / KEMBALI BARANG SATU --------------------> 

<div class="modal fade" id="modalReturSatu" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;"> <!--retur & kembali semua-->
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Retur 1 Barang !!</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="retursatu">
                <p style="font-weight: bold;">Apakah anda yakin akan meretur barang ini sejumlah 1?</p>
                {{--<p>One fine body&hellip;</p>--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Batalkan</button>
                {{--<button type="button" class="btn btn-outline">Save changes</button>--}}
                {!! link_to('#', $title='Saya Yakin Meretur Data', $attributes=['id'=>'yakinretursatu', 'class'=>'btn btn-danger']) !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modalKembalisatu" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Kembalikan 1 Barang !!</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="kembalisatu">
                <p style="font-weight: bold;">Apakah anda yakin akan mengembalikan barang ini sejumlah 1?</p>
                {{--<p>One fine body&hellip;</p>--}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Batalkan</button>
                {{--<button type="button" class="btn btn-outline">Save changes</button>--}}
                {!! link_to('#', $title='Saya Yakin Kembali Data', $attributes=['id'=>'yakinkembalisatu', 'class'=>'btn btn-danger']) !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->