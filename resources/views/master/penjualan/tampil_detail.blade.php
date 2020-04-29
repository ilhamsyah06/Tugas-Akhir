@extends('layouts.master')

@section('title','Detail Penjualan')

@section('content')
<div class="container">
<section class="content">
    <!-- title row -->
    <div class="panel panel-info">
        <h3 class="panel panel-heading">
            <i class="fa fa-shopping-cart"></i> Detail Belanja
        </h3>
        <div class="panel panel-body">
            <!-- info row -->
            <div class="row">
                <!-- /.col -->
                <div class="col-sm-4 ml-2">
                    {!! Form::hidden('kode', $penjualan->no_invoice, ['id'=>'kode', 'class' => 'form-control'])!!}
                    {!! Form::hidden('idpenjualan', $penjualan->id, ['id'=>'idpenjualan', 'class' => 'form-control'])!!}
                    <b>Kasir : {{ $penjualan->user->name}}</b><br>
                    <b>Tanggal :</b> {{ date('d-m-Y', strtotime($penjualan->tgl_penjualan)) }} <br>
                    <b>Jam :</b> {{ date('H:i:s', strtotime($penjualan->created_at)) }} <br>
                    <b>Alamat :</b> Jl. Ronggolawe Ds. Bogem Kec. Gurah Kediri
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <b>Invoice: <span class="label label-success"> {{ $penjualan->no_invoice }} </span></b><br>
                    <br>
                    <b>Order ID:</b> {{ $penjualan->id }} <br>
                    <b>Customer:</b> <span class="label label-info">Umum</span><br>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <br>
            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <b class="pull-right">Jumlah Barang: {{ $countbarang }} Barang</b> <br>
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilder">
                        <thead>
                            <tr>
                                <th class="col-md-2">Kode</th>
                                <th class="col-md-2">Nama</th>
                                <th class="col-md-1">Jenis</th>
                                <th class="col-md-2">Diskon</th>
                                <th class="col-md-2">Harga</th>
                                <th class="col-md-1">QTY</th>
                                <th class="col-md-2">Sub Total</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="7" style="text-align:right; font-size:22px; color: #000000;">Rp 0</th>
                            </tr>
                        </tfoot>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
@if ($retur != null)
<div class="row">
    <div class="col-md-12">
        <h4 class="text-center"><span class="label label-success"><i class="fa fa-refresh"></i> Barang Yang Di Retur</span></h4>
        <table width="100%" class="table table-striped table-bordered table-hover" id="dataretur">
            <thead>
                <tr>
                    <th class="col-md-2">Kode</th>
                    <th class="col-md-2">Nama</th>
                    <th class="col-md-1">Jenis</th>
                    <th class="col-md-2">Diskon</th>
                    <th class="col-md-2">Harga</th>
                    <th class="col-md-1">QTY</th>
                    <th class="col-md-2">Sub Total</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="7" style="text-align:right; font-size:22px; color: #000000;">Rp 0</th>
                </tr>
            </tfoot>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@else
    {{ null }}
@endif
            

            <div class="row">
                <!-- accepted payments column -->
                <div class="col-xs-6">
                    <p class="lead">UANG KEMBALI :</p>
                    <p class="text-muted well well-sm no-shadow"
                        style="margin-top: 10px; font-size:50px; font-weight: bold; color:#21962d;">
                        Rp. {{ number_format($penjualan->kembalian) }}
                    </p>
                </div>
                <!-- /.col -->
                <div class="col-xs-6">
                    <p class="lead">Detail Pembayaran</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Total Bayar:</th>
                                <td>Rp. {{ number_format($penjualan->total_bayar) }}</td>
                            </tr>
                            @if ($total_bayar_retur != null)
                            <tr>
                                <th>Jumlah Uang Retur:</th>
                                <td>Rp. {{ number_format($total_bayar_retur) }}</td>
                            </tr>  
                            @else
                                {{ null }}
                            @endif
                            <tr>
                                <th>Jumlah Bayar:</th>
                                <td>Rp. {{ number_format($penjualan->jumlah_bayar) }}</td>
                            </tr>
                            <tr>
                                <th>Kembalian:</th>
                                <td>Rp. {{ number_format($penjualan->kembalian) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-footer">
                <a id="kembali" href="{{ url('listpenjualan') }}" class="btn btn-primary"><i class="fa fa-refresh"></i> Kembali</a>
                    <button id="printstruk" class="btn btn-success pull-right"><i class="fa fa-print"></i> Cetak Struk</button>
                </div>
            </div>
        </div>
    </div>

</section>
</div>
@endsection


@section('footer')
<script>
    $(document).ready(function () {
        $('#dataTableBuilder').DataTable({
            ordering: false,
            searching: false,
            paging: false,
            responsive: true,
            info: false,
            'ajax': {
                'url': '/getdetailpenjualan',
                'data': function (d) {
                    d.kode = $('#kode').val();
                }
            },
            'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-danger' +
                        '">' + data + '</span>';
                }
            }, {
                'targets': 1,
                'sClass': "col-md-2"
            }, {
                'targets': 2,
                'sClass': "col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-primary' +
                        '">' + data + '</span>';
                }
            },{
                'targets': 3,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 4,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 5,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 6,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;
                if (data.length > 0) {
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$Rp,.]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // Total over all pages

                    total = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        });

                    // // Total over this page
                    // pageTotal = api
                    //     .column( 3, { page: 'current'} )
                    //     .data()
                    //     .reduce( function (a, b) {
                    //         return intVal(a) + intVal(b);
                    //     }, 0 );

                    // Update footer
                    $(api.column(0).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp ' + number_format(total, 0, ',', '.') + ''
                    );
                } else {
                    $(api.column(0).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp 0'
                    );
                }
            },
        });

        $('#dataretur').DataTable({
            ordering: false,
            searching: false,
            paging: false,
            responsive: true,
            info: false,
            'ajax': {
                'url': '/getdetailretur',
                'data': function (d) {
                    d.idpenjualan = $('#idpenjualan').val();
                }
            },
            'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-danger' +
                        '">' + data + '</span>';
                }
            }, {
                'targets': 1,
                'sClass': "col-md-2"
            }, {
                'targets': 2,
                'sClass': "col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-primary' +
                        '">' + data + '</span>';
                }
            },{
                'targets': 3,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 4,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 5,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 6,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;
                if (data.length > 0) {
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$Rp,.]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // Total over all pages

                    total = api
                        .column(6)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        });

                    // // Total over this page
                    // pageTotal = api
                    //     .column( 3, { page: 'current'} )
                    //     .data()
                    //     .reduce( function (a, b) {
                    //         return intVal(a) + intVal(b);
                    //     }, 0 );

                    // Update footer
                    $(api.column(0).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp ' + number_format(total, 0, ',', '.') + ''
                    );
                } else {
                    $(api.column(0).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp 0'
                    );
                }
            },
        });


    });

    $('#printstruk').click(function () {
        var kode = $('#kode').val();
        $.get('/strukjual/' + kode, function (res) {
        });
    });
</script>
@endsection