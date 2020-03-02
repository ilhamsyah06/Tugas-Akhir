@php
    $date = date('Y-m-d');
@endphp
<div class="row">
    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="kodegudang" class="control-label col-sm-4">Kode Barang : </label>
            <div class="col-sm-8 controls">
                <div class="input-group">
                    <input type="text" name="kodegudang" id="kodegudang" value="" class="form-control" placeholder="Kode Barang"
                        required autofocus />
                    <span class="input-group-btn">
                        <a class="btn btn-flat btn-success" id="autokodegudang">
                            <i class="fa fa-magic"> Auto</i>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="harga_beligudang" class="control-label col-sm-4">Harga Beli : </label>
            <div class="col-sm-8">
                <input type="text" name="harga_beligudang" id="harga_beligudang" value="" class="form-control"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"
                    placeholder="Harga Beli Barang" required />
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="namagudang" class="control-label col-sm-4">Nama Barang : </label>
            <div class="col-sm-8">
                <input type="text" name="namagudang" id="namagudang" value="" class="form-control" placeholder="Nama Barang"
                    required />
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="harga_jualgudang" class="control-label col-sm-4">Harga Jual : </label>
            <div class="col-sm-8">
                <input type="text" name="harga_jualgudang" id="harga_jualgudang" value="" class="form-control" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" placeholder="Harga Jual Barang"
                    required />
            </div>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="levelgudang" class="control-label col-sm-4">Jenis Barang : </label>
            <div class="col-sm-8">
                    <select name="levelgudang" class="form-control select2" id="levelgudang" style="width: 100%;">
                        <option value=" "></option>
                    </select>
            </div>
        </div>
    </div>

    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="profitgudang" class="control-label col-sm-4">Profit : </label>
            <div class="col-sm-8">
                <input type="text" name="profitgudang" id="profitgudang" value="" class="form-control inputanangka" placeholder="Keuntungan..." readonly/>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 form-horizontal">
        <div class="form-group">
            <label for="tanggalgudang" class="control-label col-sm-4">Tanggal Input: </label>
            <div class="col-sm-8">
            <input type="date" nama="tanggalgudang" id="tanggalgudang" value="{{ $date }}" class="form-control inputanangka" />
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
