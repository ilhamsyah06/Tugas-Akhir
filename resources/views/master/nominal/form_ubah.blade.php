<div class="modal fade" id="modalUbah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i> Ubah Nominal Gaji :</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idubah">
                <div class="form-group">
                    {!! Form::label('nominalubah', 'Nominal Gaji') !!}
                    {!! Form::text('nominalubah', null, ['class' => 'form-control inputanangka', 'id'=>'nominalubah','placeholder' => 'Masukan Nominal Gaji']) !!}
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
