@extends('layouts.master')

@section('title','Laporan Retur')
    
@section('content')
    <section class="content">
      <div class="row">
           <div class="col-xs-12">
              <div class="panel panel-default">
                  <div class="panel panel-heading">
                      <h3 class="panel-title"><i class="fa fa-list"></i> Laporan Uang Modal Kasir</h3>
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
                              {!! Form::open(['url' => '/cetakmodalkasir', 'method'=>'POST', 'id'=>'formcetak', 'class'=>'pull-left', 'style'=>"margin-right:5px;"]) !!}
                                          <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-print"></i>
                                              Preview Laporan
                                          </button>
                                          {!! Form::close() !!}
                          
                          </div>
                      </div>
                      <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilder">
                          <thead>
                          <tr>
                              <th class="col-md-2">Tanggal</th>
                              <th class="col-md-2">Operator</th>
                              <th class="col-md-1">Uang Awal</th>
                              <th class="col-md-1">Uang Akhir</th>
                          </tr>
                          </thead>
                          <tbody>

                          </tbody>
                      </table>

                  </div>
              </div>
              <!-- /.panel -->
          </div>
      </div>
      <!-- /.row -->
  </section>
@endsection
@section('footer')
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

    

    var table = $('#dataTableBuilder').DataTable({
        responsive: true,
        'ajax': {
            'url': '/datamodalkasir',
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
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            },{
                'targets':3,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            }
        ],
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

});

function reloadTable() {
    var table = $('#dataTableBuilder').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}
    </script>
@endsection