@extends('layouts.master')

@section('title','Gaji')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h4><i class="fa fa-plus"></i> Tambah Gaji Karyawan</h4>
            </div>
            <div class="panel panel-body">
                <div class="form-group">
                    <label for="user">Pilih Karyawan :</label>
                    <select name="user" class="form-control select2" id="user" style="width: 100%;">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlahharikerja">Jumlah Hari Kerja :</label>
                    <input type="number" id="jumlahharikerja" name="jumlahharikerja" class="form-control" placeholder="Masukan Jumlah Hari Kerja">
                </div>
                <div class="form-group">
                    <label for="nominal">Pilih Nominal :</label>
                    <select name="nominal" class="form-control select2" id="nominal" style="width: 100%;">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="totalgaji">Total Gaji :</label>
                    <input type="text" class="form-control" id="totalgaji" name="totalgaji" readonly>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal Dibayar :</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal">
                </div>
                <div class="form-group">
                    <a href="#" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list-alt"></i> List Gaji Karyawan</h4>
            </div>
            <div class="panel-body">
                <table id="dataTablegajikaryawan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Jumlah Hari</th>
                            <th>Nominal</th>
                            <th>Total Gaji</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/select2/select2.full.min.js') }}"></script>

<script>

    //---- combobox pilih karyawan --//
        var routeuser = "/userapi";
        var routenominal = "/nominalapi";
        var inputuser = $('#user');
        var inputnominal = $('#nominal');

        var listuser = document.getElementById("user");
        while (listuser.hasChildNodes()) {
            listuser.removeChild(listuser.firstChild);
        }
        inputuser.append('<option value=" "> -- Pilih Karyawan -- </option>');

        var listnominal = document.getElementById("nominal");
        while (listnominal.hasChildNodes()) {
            listnominal.removeChild(listnominal.firstChild);
        }
        inputnominal.append('<option value=" "> -- Pilih Nominal Gaji -- </option>');

        $.get(routeuser, function (res) {
            // console.log(res);
            $.each(res.data, function (index, value) {
                inputuser.append('<option value="' + value[1] + '">' + value[0] + '</option>');
            });
        });

        $.get(routenominal, function (res) {
            // console.log(res);
            $.each(res.data, function (index, value) {
                inputnominal.append('<option value="' + value[1] + '">' + value[0] + '</option>');
            });
        });

        $('#user').select2();
        $('#nominal').select2();
    //---- combobox pilih karyawan --//

</script>
@endsection