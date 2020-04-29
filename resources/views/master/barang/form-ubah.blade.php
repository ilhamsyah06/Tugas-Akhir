<div class="row">
    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="kodeubahtoko" class="control-label col-sm-4">Kode Barang : </label>
            <div class="col-sm-8">
                <input type="text" nama="kodeubahtoko" id="kodeubahtoko" value="" class="form-control"
                    placeholder="Harga Beli Barang" required />
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="hargabeliubahtoko" class="control-label col-sm-4">Harga Beli : </label>
            <div class="col-sm-8">
                <input type="text" nama="hargabeliubahtoko" id="hargabeliubahtoko" value="" class="form-control inputanangka"
                    placeholder="Harga Beli Barang" required/>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="namaubahtoko" class="control-label col-sm-4">Nama Barang : </label>
            <div class="col-sm-8">
                <input type="text" nama="namaubahtoko" id="namaubahtoko" class="form-control" placeholder="Nama Barang"
                    required/>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="hargaterakhirtampil" class="control-label col-sm-4">Harga Jual Terakhir : </label>
            <div class="col-sm-8">
                <input type="text" nama="hargaterakhirtampil" id="hargaterakhirtampil" value="" class="form-control" placeholder="Harga Terakhir" readonly/>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="levelubahtoko" class="control-label col-sm-4">Jenis Barang : </label>
            <div class="col-sm-8">
                <select name="levelubahtoko" class="form-control select2" id="levelubahtoko" style="width: 100%;">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="hargajualubahtoko" class="control-label col-sm-4">Harga Jual : </label>
            <div class="col-sm-8">
                <input type="text" nama="hargajualubahtoko" id="hargajualubahtoko" value="" class="form-control inputanangka" placeholder="Harga Jual Barang"
                    required/>
                    <input type="hidden" id="hargaterakhir">
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="stokubahtoko" class="control-label col-sm-4">Stok Toko: </label>
            <div class="col-sm-8">
                <input type="text" nama="stokubahtoko" id="stokubahtoko" value="" class="form-control inputanangka"
                    placeholder="contoh: 75" readonly/>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="profitubahtoko" class="control-label col-sm-4">Profit : </label>
            <div class="col-sm-8">
                <input type="text" nama="profitubahtoko" id="profitubahtoko" value="" class="form-control inputanangka" placeholder="Keuntungan..." style="background-color: #bfffb5;" readonly/>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="stokubahgudang" class="control-label col-sm-4">Stok Gudang: </label>
            <div class="col-sm-8">
                <input type="text" nama="stokubahgudang" id="stokubahgudang" value="" class="form-control inputanangka" readonly/>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="tanggalubahtoko" class="control-label col-sm-4">Tanggal : </label>
            <div class="col-sm-8">
                <input type="date" nama="tanggalubahtoko" id="tanggalubahtoko" value="" class="form-control inputanangka" readonly/>
            </div>
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-12">
        <p class="text-danger"><span class="text-danger">* </span> Catatan : </p>
        <p><span class="text-primary">Profit : </span> Keuntungan dari harga jual dikurangi harga beli.</p>
        <p><span class="text-primary">Harga Beli : </span> Harga per-satuan dari supplier.</p>
        <p><span class="text-primary">Harga Jual : </span> Harga yang dijual oleh pemilik toko.</p>
    </div>
</div>
