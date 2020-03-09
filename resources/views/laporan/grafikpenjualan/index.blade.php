@extends('layouts.master')

@section('title','Grafik Penjualan')

@section('content')
<div class="container animated zoomIn">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel panel-heading" style="background-color:#26ff60; color:#00611a;"><i class="fa fa-bar-chart"></i> Grafik Pendapatan Penjualan</div>
            <div class="panel panel-body">
                {!! $chart->render() !!}
            </div>
        </div>
    </div>
    <div class="row animated zoomIn">
        <div class="panel panel-default">
            <div class="panel panel-heading" style="background-color:#f55b5b; color:#4d0000;"><i class="fa fa-bar-chart"></i> Grafik Jumlah Transaksi</div>
            <div class="panel panel-body">
                {!! $chartjumlah->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection