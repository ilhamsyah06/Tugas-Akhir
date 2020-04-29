@extends('layouts.master')

@section('title')
    Laporan Rugi Laba
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> Laporan Rugi Laba</h3>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-4 form-horizontal form-left">
                                <div class="form-group">
                                    {!! Form::label('start', 'Periode :', ['class' => 'control-label col-sm-3']) !!}
                                    <div class="col-sm-9 controls">
                                        <div class="input-daterange input-group">
                                            {!! Form::text('start', null, ['class'=>'form-control', 'id' => 'start', 'placeholder'=> 'DD/MM/YYYY']) !!}
                                            <span class="input-group-addon">s/d</span>
                                            {!! Form::text('end', null, ['class'=>'form-control', 'id'=>'end', 'placeholder'=> 'DD/MM/YYYY']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 form-horizontal">
                                <div class="form-group">
                                    {!! Form::label('jenis', 'Kategori Barang :', ['class' => 'control-label col-sm-5']) !!}
                                    <div class="col-sm-7">
                                        {!!  Form::select('jenis', [], null, ['class' => 'form-control select2', 'style'=>"width:100%;"])  !!}
                                    </div>
                                </div>
                            </div>
                                    <div class="col-sm-4 form-horizontal">
                                        <div class="form-group">
                                            {!! Form::open(['url' => '/datalaba', 'method'=>'POST', 'id'=>'formcetak']) !!}
                                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-print"></i>
                                                Preview Laporan
                                            </button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                            </div>  
                        </div>

                        <hr />
                        
                        <div class="row">
                            <div class="col-xs-6">
                                <p class="lead">Pendapatan :</p>

                                <div class="table-responsive">
                                    <table class="table table table-striped table-bordered table-hover">
                                        <tr>
                                            <th style="width:50%">Penjualan</th>
                                            <td style="text-align: right;" id="totalpenjualan"></td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td class="lead" style="text-align: right;" id="totalpendapatan"><strong></strong></td>
                                        </tr> 
                                    </table>
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <p class="lead">Pengeluaran :</p>

                                <div class="table-responsive">
                                    <table class="table table table-striped table-bordered table-hover">
                                        <tr>
                                            <th style="width:50%">Pembelian Barang</th>
                                            <td style="text-align: right;" id="totalpembelian"></td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td class="lead" style="text-align: right;" id="totalpengeluaran"><strong></strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-xs-6">
                            <table>
                                <tr>
                                    <th style="width:50%" class="lead"><strong>Grand Total : </strong></th>
                                    <td class="lead" style="padding-left: 10px;" id="grandtotal"><strong></strong></td>
                                </tr>
                            </table>
                            </div>
                        </div>
                    </div>
                    

                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/select2/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function() {
    var route = "/levelapi";
    var inputTipe = $('#jenis');

    var list = document.getElementById("jenis");
    while (list.hasChildNodes()) {
        list.removeChild(list.firstChild);
    }
    inputTipe.append('<option value=" ">Semua kategori Barang</option>');

    $.get(route, function (res) {
        $.each(res.data, function (index, value) {
            inputTipe.append('<option value="' + value[1] + '">' + value[0] + '</option>');
        });
    });

    $("#jenis").select2();

    var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";

    $('.input-daterange').datepicker({
        format: "dd/mm/yyyy",
        container: container,
        todayHighlight: true,
        autoclose: true,
    });

    var fd = Date.today().clearTime().moveToFirstDayOfMonth();
    // var firstday = fd.toString("MM/dd/yyyy");
    var ld = Date.today().clearTime().moveToLastDayOfMonth();
    // var lastday = ld.toString("MM/dd/yyyy");
    
    $("#start").datepicker("setDate", fd);
    $("#end").datepicker("setDate", ld);

    
    $('#start').on('change', function (e) {
        reloadRekap();
    });
    $('#end').on('change', function (e) {
        reloadRekap();
    });

    $('#jenis').on('change', function (e) {
        reloadRekap();
    });

    $('#formcetak').on('submit', function (e) {

        var start = $('#start').val();
        var end = $('#end').val();
        var jenis = $('#jenis').val();

        var form = this;

        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'start')
                .val(start)
        );

        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'end')
                .val(end)
        );

        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'jenis')
                .val(jenis)
        );
    });

    reloadRekap();
});

function reloadRekap() {
    var start = $('#start').val();
        var end = $('#end').val();
        var jenis = $('#jenis').val();
    var token = $('#token').val();

    $.ajax({
        url: '/getrekaplaba',
        type: 'GET',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        data: {
            start: start,
            end: end,
            jenis: jenis,
            _token: token
        },
        error: function (res) {
            var errors = res.responseJSON;
            var pesan = '';
            $.each(errors, function (index, value) {
                pesan += value + "\n";
            });

            alert(pesan);
        },
        success: function (res) {
            var penjualan = 0;

            if (res.penjualan != null && jQuery.trim(res.penjualan) != '') {
                penjualan = intVal(res.penjualan)
            } 

            var pembelian = 0;
            if (res.pembelian != null && jQuery.trim(res.pembelian) != '') {
                pembelian = intVal(res.pembelian)
            }

            var pendapatan = penjualan;
            var pengeluaran = pembelian;
            var grandtotal = pendapatan - pengeluaran;

            $('#totalpenjualan').html( number_format(intVal(penjualan), 0, ',', '.') );
            $('#totalpendapatan').html('<strong>' +  number_format(intVal(pendapatan), 0, ',', '.')  + '</strong>');

            $('#totalpembelian').html( number_format(intVal(pembelian), 0, ',', '.') );
            $('#totalpengeluaran').html('<strong>' +  number_format(intVal(pengeluaran), 0, ',', '.')  + '</strong>');

            $('#grandtotal').html('<strong>' + number_format(intVal(grandtotal), 0, ',', '.') + '</strong>');
        }
    });
}
</script>
@endsection

