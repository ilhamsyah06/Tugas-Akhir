@extends('layouts.master')

@section('title','Nominal Gaji')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h4><i class="fa fa-plus"></i> Nominal Gaji</h4>
            </div>
            <div class="panel panel-body">
                <div class="form-group">
                    <label for="nominal">Nominal Gaji :</label>
                    <input type="text" name="nominal" id="nominal" class="form-control" placeholder="Masukan Nominal Gaji">
                </div>
                <div class="form-group">
                    <a href="#" class="btn btn-primary pull-right" name="simpan" id="simpan"><i class="fa fa-plus"></i> Tambah</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>List Nominal Gaji</h4>
            </div>
            <div class="panel-body">
                <table id="nominal" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection