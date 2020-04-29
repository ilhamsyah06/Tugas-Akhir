@extends('layouts.master')

@section('title')
    Laporan Daftar Barang Hilang
@endsection

@section('content')
    <section class="content">
        <div class="row">
                <div class="col-xs-12">
            
                <div class="panel panel-default">
                    <div class="panel-heading with-border">
                        <h3 class="panel-title"><i class="fa fa-list"></i> Laporan Daftar Barang Hilang</h3>
                    </div>
                    

                    <div class="panel-body pad table-responsive">
                        <div class="row">
                            <div class="col-sm-12 form-horizontal">
                                <div class="form-group">
                                    {!! Form::label('start', 'Periode', ['class' => 'control-label col-sm-1']) !!}
                                    <div class="col-sm-11 controls">
                                        <div class="input-daterange input-group">
                                            {!! Form::text('start', null, ['class'=>'form-control', 'id' => 'start', 'placeholder'=> 'DD/MM/YYYY']) !!}
                                            <span class="input-group-addon">s/d</span>
                                            {!! Form::text('end', null, ['class'=>'form-control', 'id'=>'end', 'placeholder'=> 'DD/MM/YYYY']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-sm-6 form-horizontal">
                                <div class="form-group">
                                    {!! Form::label('barang', 'Berdasarkan Barang : ', ['class' => 'control-label col-sm-4']) !!}
                                    <div class="col-sm-8">
                                        {!!  Form::select('barang', [], null, ['class' => 'form-control select2', 'style'=>"width:100%;"])  !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 form-horizontal">
                                <div class="form-group">
                                    {!! Form::open(['url' => '/cetakhilang', 'method'=>'POST', 'id'=>'formcetak']) !!}
                                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-print"></i>
                                        Preview Laporan
                                    </button>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>

                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilder">
                            <thead>
                            <tr>
                                <th class="col-md-1">Tanggal</th>
                                <th class="col-md-5">Data Barang</th>
                                <th class="col-md-1">Jumlah</th>
                                <th class="col-md-3">Operator</th>
                                <th class="col-md-2">No. Bukti</th>
                            </tr>

                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                    

                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col -->
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

    var route = "/get_select_barang";
    var inputTipe = $('#barang');

    var list = document.getElementById("barang");
    while (list.hasChildNodes()) {
        list.removeChild(list.firstChild);
    }
    inputTipe.append('<option value=" ">Semua Barang</option>');

    $.get(route, function (res) {
        $.each(res.data, function (index, value) {
            inputTipe.append('<option value="' + value[1] + '">' + value[0] + '</option>');
        });
    });

    $("#barang").select2();


    $('#dataTableBuilder').DataTable({
        responsive: true,
        'ajax': {
            'url': '/datahilang',
            'data': function (d) {
                d.barang = $('#barang').val();
                d.start = $('#start').val();
                d.end = $('#end').val();
            }
        },

        'columnDefs': [
            {
                'targets':0,
                'sClass': "text-center col-md-1"
            },{
                'targets':1,
                'sClass': "col-md-5"
            },{
                'targets':2,
                'sClass': "col-md-1 text-right",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            },{
                'targets':3,
                'sClass': "col-md-3"
            },{
                'targets':4,
                'sClass': "text-center col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-danger">' + data + '</span>';
                }
            }
        ]
    });

    $('#barang').on('change', function (e) {
        reloadTable();
    });

    $('#start').on('change', function (e) {
        reloadTable();
    });
    $('#end').on('change', function (e) {
        reloadTable();
    });
});

function reloadTable() {
    var table = $('#dataTableBuilder').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

$('#formcetak').on('submit', function (e) {
    var table = $('#dataTableBuilder').DataTable();
        if ( ! table.data().count() ) {
            alert( 'Tidak ada data yang akan dicetak !!' );
            return false;
        }

        var barang = $('#barang').val();
        var start = $('#start').val();
        var end = $('#end').val();

        var form = this;

        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'barang')
                .val(barang)
        );

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
    });
</script>
@endsection