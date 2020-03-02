<div class="modal fade" id="modalLihat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Detail Supplier</h4>
            </div>
            <div class="modal-body">
                @include('master.supplier.form-lihat')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Ubah Supplier</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idubah">
                @include('master.supplier.form-ubah')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
                {!! link_to('#', $title='Simpan Data', $attributes=['id'=>'simpanubah', 'class'=>'btn btn-primary']) !!}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@include('layouts.modalhapus')
