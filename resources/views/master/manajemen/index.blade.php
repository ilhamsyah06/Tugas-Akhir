@extends('layouts.master')

@section('title','Kelola Data')
@php
    $bulan = date('Y-m');
@endphp
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="text-center"> <i class="fa fa-trash"></i> MANAJEMEN DATA POS ANDIKA</h2>
    </div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong>{{ $message }}</strong>
</div>
@endif
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-trash"></i> Filter Hapus Data Penjualan Perbulan</h3>
            </div>
            <div class="panel-body">
                <form action="{{ url('hapusdatapenjualan') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Klik Icon Tanggal Form Filter</label>
                        <input class="form-control" type="month" value="{{ $bulan }}" name="tanggal">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger" onclick="javascript: return confirm('Anda yakin hapus ?')"><i class="fa fa-trash"></i> Hapus Data Penjualan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-trash"></i> Filter Hapus Data Pembelian Perbulan</h3>
            </div>
            <div class="panel-body">
                <form action="{{ url('hapusdatapembelian') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Klik Icon Tanggal Form Filter</label>
                        <input class="form-control" type="month" value="{{ $bulan }}" name="tanggal">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger" onclick="javascript: return confirm('Anda yakin hapus ?')"><i class="fa fa-trash"></i> Hapus Data Pembelian</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-trash"></i> Filter Hapus Data History Stok Perbulan</h3>
            </div>
            <div class="panel-body">
                <form action="{{ url('hapusdatahistory') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Klik Icon Tanggal Form Filter</label>
                        <input class="form-control" type="month" value="{{ $bulan }}" name="tanggal">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger" onclick="javascript: return confirm('Anda yakin hapus ?')"><i class="fa fa-trash"></i> Hapus Data History Stok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-defult">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-trash"></i> Filter Hapus Data Uang Modal Kasir Perbulan</h3>
            </div>
            <div class="panel-body">
                <form action="{{ url('hapusdatamodalkasir') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="">Klik Icon Tanggal Form Filter</label>
                        <input class="form-control" type="month" value="{{ $bulan }}" name="tanggal">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger" onclick="javascript: return confirm('Anda yakin hapus ?')"><i class="fa fa-trash"></i> Hapus Data Uang Modal Kasir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    @endsection