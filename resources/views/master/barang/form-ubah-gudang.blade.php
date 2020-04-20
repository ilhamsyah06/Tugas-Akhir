<div class="row">
    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="kodeubahgudang" class="control-label col-sm-4">Kode Barang : </label>
            <div class="col-sm-8">
                <input type="text" name="kodeubahgudang" id="kodeubahgudang" value="" class="form-control"
                    placeholder="Harga Beli Barang" required />
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="hargabeliubahgudang" class="control-label col-sm-4">Harga Beli : </label>
            <div class="col-sm-8">
                <input type="text" name="hargabeliubahgudang" id="hargabeliubahgudang" value="" class="form-control inputanangka"
                    placeholder="Harga Beli Barang" required/>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="namaubahgudang" class="control-label col-sm-4">Nama Barang : </label>
            <div class="col-sm-8">
                <input type="text" name="namaubahgudang" id="namaubahgudang" class="form-control" placeholder="Nama Barang"
                    required/>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="hargajualubahgudang" class="control-label col-sm-4">Harga Jual : </label>
            <div class="col-sm-8">
                <input type="text" name="hargajualubahgudang" id="hargajualubahgudang" value="" class="form-control inputanangka" placeholder="Harga Jual Barang"
                    required/>
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="levelubahgudang" class="control-label col-sm-4">Jenis Barang : </label>
            <div class="col-sm-8">
                <select name="levelubahgudang" class="form-control select2" id="levelubahgudang" style="width: 100%;">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="hargajualterakhir" class="control-label col-sm-4">Hrg Jual Trakhir :</label>
            <div class="col-sm-8">
                <input type="text" name="hargajualterakhir" id="hargajualterakhir" value="" class="form-control inputanangka" placeholder="" readonly/>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="stokgudang" class="control-label col-sm-4">Stok Gudang : </label>
            <div class="col-sm-8">
                <input type="text" name="stokgudang" id="stokgudang" value="" class="form-control inputanangka" placeholder="StokGudang" readonly/>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="profitubahgudang" class="control-label col-sm-4">Profit : </label>
            <div class="col-sm-8">
                <input type="text" name="profitubahgudang" id="profitubahgudang" value="" class="form-control inputanangka" placeholder="Keuntungan..." readonly/>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="stokubahtoko" class="control-label col-sm-5">Kirim Stok Ke Toko : </label>
            <div class="col-sm-7">
                <input type="text" name="stokubahgudang" id="stokubahgudang" value="" class="form-control inputanangka"
                    placeholder="contoh: 75" required  />
            </div>
        </div>
    </div>
    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="tanggalubahtoko" class="control-label col-sm-4">Tanggal : </label>
            <div class="col-sm-8">
                <input type="date" name="tanggalubahgudang" id="tanggalubahgudang" value="" class="form-control inputanangka" />
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
