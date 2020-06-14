@extends('layouts.master')

@section('title','List Retur')

@section('content')
<section class="content">
    <div class="row">
          <div class="col-xs-12">
              <div class="panel panel-default">
                  <div class="panel panel-heading with-border">
                      <h3 class="panel-title"><i class="fa fa-list"></i> List Retur Barang</h3>
                  </div>

                  <div class="panel-body">
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

                      <table width="100%" class="table table-striped table-bordered table-hover" id="dataretur">
                          <thead>
                          <tr>
                              <th class="col-md-2">No. Bukti</th>
                              <th class="col-md-2">Tanggal</th>
                              <th class="col-md-2">No. Invoice</th>
                              <th class="col-md-1">Operator</th>
                              <th class="col-md-2">Aksi</th>
                          </tr>
                          </thead>
                          <tbody>

                          </tbody>
                      </table>

                  </div>
                  <!-- /.box-body -->
                      <div class="panel-footer">
                          <a title="Tambah Retur Baru" class="btn btn-primary" href="{{ url('retur') }}"><i class="fa fa-refresh"></i> Retur</a>
                      </div>

              </div>
              <!-- /.box -->
          </div>
          <!-- /.col -->
      </div>
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

    var todayDate = new Date();
    
    $("#start").datepicker("setDate", todayDate);
    $("#end").datepicker("setDate", todayDate);

    $('#dataretur').DataTable({
        responsive: true,
        'ajax': {
            'url': '/dataretur',
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
                'sClass': "text-center col-md-2"
            },{
                'targets':3,
                'sClass': "text-center col-md-1"
            },{
                'targets': 4,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-2 td-aksi",
                'render': function (data, type, full, meta) {
                    var kembali = '';
                    kembali += '<a title="Lihat Transaksi" class="btn btn-info btn-flat" href="/retur/'+data+'"><i class="fa fa-eye fa-fw"></i> </a>';
                    return kembali;

                }
            }
        ],
        'rowCallback': function (row, data, dataIndex) {
            $(row).find('button[class="btn btn-info btn-flat"]').prop('value', data[4]);

        }
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

    </script>
@endsection