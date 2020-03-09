<div class="modal fade" id="modalUbah" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i> Ubah Data Gaji :</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idubah">
                <div class="form-group">
                    <div class="form-group">
                        <label for="nomorgajiubah">Nomor Gaji :</label>
                        <input type="text" class="form-control" id="nomorgajiubah" name="nomorgajiubah" readonly>
                    </div>
                    <div class="form-group">
                        <label for="userubah">Pilih Karyawan :</label>
                        <select name="userubah" class="form-control select2" id="userubah" style="width: 100%;">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nominalubah">Pilih Nominal :</label>
                        <select name="nominalubah" class="form-control select2" id="nominalubah" style="width: 100%;">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlahharikerjaubah">Jumlah Hari Kerja :</label>
                        <input type="text" id="jumlahharikerjaubah" name="jumlahharikerjaubah" class="form-control" placeholder="Masukan Jumlah Hari Kerja">
                    </div>
                    <div class="form-group">
                        <label for="totalgajiubah">Total Gaji :</label>
                        <input type="text" class="form-control" id="totalgajiubah" name="totalgajiubah" readonly>
                    </div>
                    <div class="form-group">
                        <label for="tanggalubah">Tanggal Dibayar :</label>
                        <input type="date" class="form-control" id="tanggalubah" name="tanggalubah">
                    </div>
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