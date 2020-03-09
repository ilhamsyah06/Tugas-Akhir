@extends('layouts.master')

@section('title','Grafik Pembelian')

@section('content')
<div class="container">
    <div class="row animated zoomIn">
        <div class="panel panel-default">
            <div class="panel panel-heading" style="background-color:#65bbfc; color:#002642;"><i class="fa fa-bar-chart"></i> Grafik Pengeluaran Pembelian</div>
            <div class="panel panel-body">
                {!! $chartpembelian->render() !!}
            </div>
        </div>
    </div>
    <div class="row animated zoomIn">
        <div class="panel panel-default">
            <div class="panel panel-heading" style="background-color:#ffa66b; color:#3b1700;"><i class="fa fa-bar-chart"></i> Grafik Jumlah Transaksi</div>
            <div class="panel panel-body">
                {!! $chartjumlah->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection