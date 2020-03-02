<div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Ubah Nama Jenis Barang</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idubah">
                <div class="form-group">
                    {!! Form::label('namaubah', 'Nama Jenis Barang') !!}
                    {!! Form::text('namaubah', null, ['class' => 'form-control', 'id'=>'namaubah','placeholder' => 'Masukan Nama Jenis Barang']) !!}
                </div>

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary btn-flat" id="simpanubah"><i class="fa fa-save"></i> Simpan Perubahan</a>
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@include('layouts.modalhapus')
