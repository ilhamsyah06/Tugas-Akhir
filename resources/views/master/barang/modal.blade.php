<div class="modal fade" id="modalTambah" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cubes"></i> Tambah Barang</h4>
            </div>
            <div class="modal-body">
                @include('master.barang.form')
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary btn-flat" id="simpantambah"><i class="fa fa-save"></i> Simpan</a>
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- <div class="modal fade" id="modalTambahtoko" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cubes"></i> Tambah Barang Toko</h4>
            </div>
            <div class="modal-body">
                @include('master.barang.form-tambah-toko')
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary btn-flat" id="simpantambahtoko"><i class="fa fa-save"></i> Simpan</a>
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
            </div>
        </div>
    </div>
</div>-->

<div class="modal fade" id="modalLihat" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cube"></i> Detail Barang</h4>
            </div>
            <div class="modal-body">
                @include('master.barang.form-lihat')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalLihatStok" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cubes"></i> Daftar Barang Stok Gudang Habis</h4>
            </div>
            <div class="modal-body">
                @include('master.barang.form-lihat-stok')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalUbah" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cube"></i> Ubah Barang</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idubah">
                @include('master.barang.form-ubah')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Batal</button>
                {!! link_to('#', $title='Simpan Data', $attributes=['id'=>'simpanubah', 'class'=>'btn btn-primary']) !!}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@include('layouts.modalhapus')


<div class="modal fade" id="modalKirim" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-send"></i> Kirim Stok Toko</h4>
            </div>
            <div class="modal-body">
                @include('master.barang.form-kirim-stok')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close"></i> Tutup</button>
                <a href="#" class="btn btn-primary btn-flat" id="simpankirim"><i class="fa fa-send"></i> Kirim</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalprintbarcode" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cube"></i> Print Barcode</h4>
            </div>
            <div class="modal-body">
                <form action="{{ url('printbarcode')}}" method="post">
                    {{ csrf_field() }}
                <input type="hidden" id="kodeprint" name="kode">
                <input type="hidden" id="qtyprint" name="qty">
                <input type="hidden" id="hargaprint" name="harga">
                <h3>Apakah Ingin Mencetak Barcode ?</h3>
                <button type="submit" class="btn btn-default btn-lg" ><i class="fa fa-barcode"></i> Print Barcode</button>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Tidak</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

