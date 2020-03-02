<div class="row">
    <div class="col-sm-12 form-horizontal form-left">
        <input type="hidden" name="barangasli" id="barangasli" value="" class="form-control" />
        <div class="form-group">
            <label for="barang" class="control-label col-sm-3">Kode : </label>
            <div class="col-sm-9 controls">
                <div class="input-group">
                    <input type="text" name="barang" id="barang" value="" class="form-control" placeholder="Kode/Barcode Barang" />
                    <span class="input-group-btn">
                        <a class="btn btn-info" type="button" data-toggle="modal" data-target="#modalCariBarang" id="caribarang" onClick="CariClick(this);">
                            <i class="fa fa-search"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 form-horizontal">
        <div class="form-group">
            <label for="nama" class="control-label col-sm-3">Nama</label>
            <div class="col-sm-9">
                <input type="text" name="nama" id="nama" class="form-control" value="" disabled placeholder="Nama Barang" />
            </div>
        </div>
    </div>

    <div class="col-sm-12 form-horizontal form-left">
        <div class="form-group">
            <label for="harga_beli" class="control-label col-sm-3">Harga</label>
            <div class="col-sm-9">
                <input type="text" name="harga_beli" value="" id="harga_beli" class="form-control inputanangka" placeholder="Harga Beli" required />
            </div>
        </div>
    </div>
    <div class="col-sm-12 form-horizontal form-left">
        <div class="form-group">
            <label for="qty" class="control-label col-sm-3">QTY</label>
            <div class="col-sm-9">
                <input type="text" name="qty" value="" class="form-control inputanangka" id="qty" placeholder="QTY" />
            </div>
        </div>
    </div>

    <div class="col-sm-12 form-horizontal form-left">
        <div class="form-group">
            <label for="total" class="control-label col-sm-3">Total</label>
            <div class="col-sm-9">
                <input type="text" name="total" value="" class="form-control" id="total" placeholder="Sub Total" disabled />
            </div>
        </div>
    </div>
</div>