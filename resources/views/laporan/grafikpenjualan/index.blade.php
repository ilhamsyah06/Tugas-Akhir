@extends('layouts.master')

@section('title','Grafik Penjualan')

@section('style')
<link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}"/>
@endsection

@section('content')
<div class="container animated zoomIn">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel panel-heading" style="background-color:#4287f5; color:#ffffff;"><i class="fa fa-calendar"></i> Kalender Penjualan</div>
            <div class="panel panel-body">
                {!! $calendar->calendar() !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel panel-heading" style="background-color:#26ff60; color:#ffffff;"><i class="fa fa-bar-chart"></i> Grafik Pendapatan Penjualan</div>
            <div class="panel panel-body">
                {!! $chart->render() !!}
            </div>
        </div>
    </div>
    <div class="row animated zoomIn">
        <div class="panel panel-default">
            <div class="panel panel-heading" style="background-color:#f55b5b; color:#ffffff;"><i class="fa fa-bar-chart"></i> Grafik Jumlah Transaksi</div>
            <div class="panel panel-body">
                {!! $chartjumlah->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/highcharts.js') }}" charset="utf-8"></script>
{!! $calendar->script() !!}
@endsection