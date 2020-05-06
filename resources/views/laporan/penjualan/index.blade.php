@extends('layouts.master')

@section('title','Laporan Penjualan')
    
@php
$tanggal = date('Y-m-d');
   // $profithariini = DB::table('detail_penjualan')->join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')->where('tgl_penjualan', $tanggal)->sum('profit');
   // $profitkeseluruhan = DB::table('detail_penjualan')->join('barang', 'detail_penjualan.barang_id', '=', 'barang.id')->sum('profit');
@endphp
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card-counter success animated flipInX">
              <i class="fa fa-money"></i>
            <span class="count-numbers">Rp.<span class="counter">{{ number_format($profithariini) }}</span>,-</span>
              <span class="count-name">Profit Hari Ini</span>
            </div>
          </div>
        
          <div class="col-md-3">
            <div class="card-counter primary animated flipInX">
              <i class="fa fa-money"></i>
            <span class="count-numbers">Rp.<span class="counter">{{ number_format($profitkeseleuruhan) }} </span>,-</span>
              <span class="count-name">Profit Keseluruhan</span>
            </div>
          </div>
    </div>
    <br>

    <section class="content">
      <div class="row">
           <div class="col-xs-12">
              <div class="panel panel-default">
                  <div class="panel panel-heading">
                      <h3 class="panel-title"><i class="fa fa-list"></i> Cetak Transaksi Penjualan Barang</h3>
                  </div>

                  <div class="panel-body">
                      <div class="row">
                          <div class="col-sm-8 form-horizontal form-left">
                              <div class="form-group">
                                  {!! Form::label('start', 'Periode :', ['class' => 'control-label col-sm-2']) !!}
                                  <div class="col-sm-10 controls">
                                      <div class="input-daterange input-group">
                                          {!! Form::text('start', null, ['class'=>'form-control', 'id' => 'start', 'placeholder'=> 'DD/MM/YYYY']) !!}
                                          <span class="input-group-addon">s/d</span>
                                          {!! Form::text('end', null, ['class'=>'form-control', 'id'=>'end', 'placeholder'=> 'DD/MM/YYYY']) !!}
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="col-sm-4 form-horizontal">
                              {!! Form::open(['url' => '/cetakpenjualan', 'method'=>'POST', 'id'=>'formcetak', 'class'=>'pull-left', 'style'=>"margin-right:5px;"]) !!}
                                          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-print"></i>
                                              Preview Laporan
                                          </button>
                                          {!! Form::close() !!}

                              {!! Form::open(['url' => '/cetakpenjualan', 'method'=>'POST', 'id'=>'formcetakdetail', 'class'=>'pull-left', 'style'=>"margin-right:5px;"]) !!}
                                          <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-print"></i>
                                              Preview Laporan Detail
                                          </button>
                                          {!! Form::close() !!}
                          
                          </div>
                          <br><br>
                          <div class="col-sm-4 form-horizontal">
                           <!-- {!! Form::open(['url' => '/cetakterlaris', 'method'=>'POST', 'id'=>'formcetakdetail', 'class'=>'pull-left', 'style'=>"margin-right:5px;"]) !!}
                                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-print"></i>
                                            Barang Terlaris
                                        </button>
                                        {!! Form::close() !!}-->
                        
                        </div>
                          
                      </div>


                      
                      <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilder">
                          <thead>
                          <tr>
                              <th class="col-md-2">Tgl. Transaksi</th>
                              <th class="col-md-2">No. Bukti</th>
                              <th class="col-md-1">Kasir/User</th>
                              <th class="col-md-1">Total Bayar</th>
                              <th class="col-md-1">Laba/Profit</th>
                          </tr>
                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                              <th colspan="3" style="background: #c9c9c9 ;">Grand Total :</th>
                              <th class="col-md-1"></th>
                              <th class="col-md-1"></th>
                          </tfoot>
                      </table>

                  </div>
              </div>
              <!-- /.panel -->
              <div class="panel panel-default">
                <div class="panel panel-heading">
                    <h4><i class="fa fa-list-alt"></i> Barang Terlaris</h4>
                </div>
                <div class="panel-body">
                    <table id="databarangterlaris" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="col-md-2">Kode</th>
                                <th class="col-md-2">Nama</th>
                                <th class="col-md-1">Kategori</th>
                                <th class="col-md-1">Terjual</th>
                                <th class="col-md-1">Harga Beli</th>
                                <th class="col-md-1">Harga Jual</th>
                                <th class="col-md-1">Total</th>
                            </tr>
                        </thead>
                        <tbody>
        
                        </tbody>
                        <tfoot>
                            <th colspan="4" style="background: #c9c9c9 ;">Grand Total :</th>
                            <th class="col-md-1"></th>
                            <th class="col-md-1"></th>
                            <th class="col-md-1"></th>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
      </div>
      <!-- /.row -->
  </section>
  
@endsection
@section('footer')
    <script>
      $(document).ready(function() {

        $('#databarangterlaris').DataTable({
        responsive: true,
        scrollX: true,
        'ajax': {
            'url': '/terlaris',
        },
        'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-md-2"
            },{
                'targets': 1,
                'sClass': "col-md-2"
            },
            {
                'targets': 2,
                'sClass': "col-md-1"
            },
            {
                'targets':3,
                'sClass': "col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.') + ' item';
                }
            },
            {
                'targets':4,
                'sClass': "col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            },
            {
                'targets':5,
                'sClass': "col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets':6,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }
        ],
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;
            if (data.length > 0) {
                total = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        // console.log(a);
                        return intVal(a) + intVal(b);
                    } );
                total1 = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        // console.log(a);
                        return intVal(a) + intVal(b);
                    } );
                    total2 = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        // console.log(a);
                        return intVal(a) + intVal(b);
                    } );


                // Update footer
                $( api.column( 4 ).footer() ).html(
                    //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                    number_format(total2, 0, ',', '.')
                );
                $( api.column( 5 ).footer() ).html(
                    //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                    number_format(total, 0, ',', '.')
                );
                $( api.column( 6 ).footer() ).html(
                    //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                    number_format(total1, 0, ',', '.')
                );

            } else {
                $( api.column(4).footer() ).html(
                   //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                   '0'
                );
                $( api.column(5).footer() ).html(
                   //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                   '0'
                );
                $( api.column(6).footer() ).html(
                   //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                   '0'
                );
            }
        },
    });

    

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

    

    var table = $('#dataTableBuilder').DataTable({
        responsive: true,
        'ajax': {
            'url': '/datalaporanpenjualan',
            'data': function (d) {
                d.start = $('#start').val();
                d.end = $('#end').val();
            }
        },

        'columnDefs': [
            {
                'targets':0,
                'sClass': "text-center col-md-2"
            },{
                'targets':1,
                'sClass': "text-center col-md-2"
            },{
                'targets':2,
                'sClass': "text-center col-md-1"
            },{
                'targets':3,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            },{
                'targets':4,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            }
        ],
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;
            if (data.length > 0) {
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        // console.log(a);
                        return intVal(a) + intVal(b);
                    } );
                total1 = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        // console.log(a);
                        return intVal(a) + intVal(b);
                    } );


                // Update footer

                $( api.column( 3 ).footer() ).html(
                    //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                    number_format(total, 0, ',', '.')
                );
                $( api.column( 4 ).footer() ).html(
                    //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                    number_format(total1, 0, ',', '.')
                );

            } else {
                $( api.column(3).footer() ).html(
                   //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                   '0'
                );
                $( api.column(4).footer() ).html(
                   //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                   '0'
                );
            }
        },
    });
    $('#start').on('change', function (e) {
        reloadTable();
    });
    $('#end').on('change', function (e) {
        reloadTable();
    });

    $('#formcetak').on('submit', function (e) {
        if ( ! table.data().count() ) {
            alert( 'Tidak ada data yang akan dicetak !!' );
            return false;
        }

        var start = $('#start').val();
        var end = $('#end').val();

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
                .attr('name', 'lap')
                .val('semua')
        );

        
    });

    $('#formcetakdetail').on('submit', function (e) {
        if ( ! table.data().count() ) {
            alert( 'Tidak ada data yang akan dicetak !!' );
            return false;
        }

        var start = $('#start').val();
        var end = $('#end').val();
        
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
                .attr('name', 'lap')
                .val('detail')
        );
    });
});

function reloadTable() {
    var table = $('#dataTableBuilder').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}
    </script>
@endsection