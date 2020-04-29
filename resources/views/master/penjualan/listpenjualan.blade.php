@extends('layouts.master')

@section('title','List Penjualan')
    
@section('content')
<section class="content">
    <div class="row">
          <div class="col-xs-12">
              <div class="panel panel-default">
                  <div class="panel panel-heading with-border">
                      <h3 class="panel-title"><i class="fa fa-list"></i> List Penjualan Barang</h3>
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

                      <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilder">
                          <thead>
                          <tr>
                              <th class="col-md-2">No. Bukti</th>
                              <th class="col-md-2">Tgl.</th>
                              <th class="col-md-2">Total</th>
                              <th class="col-md-1">Operator</th>
                              <th class="col-md-3">Aksi</th>
                          </tr>
                          </thead>
                          <tbody>

                          </tbody>
                      </table>

                  </div>
                  <!-- /.box-body -->
                      <div class="panel-footer">
                          <a title="Tambah Penjualan Baru" class="btn btn-primary" href="{{ url('penjualan') }}"><i class="fa fa-plus"></i> Penjualan</a>
                      </div>

              </div>
              <!-- /.box -->
          </div>
          <!-- /.col -->
      </div>
  </section>
  @include('layouts.modalhapus')
@endsection

@section('footer')
    <script>

    $(document).ready(function() {

    var route = "/cekhakakses";
    var bolehUbah;
    $.get(route, function (res) {
        bolehUbah = res;
    });

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

    $('#dataTableBuilder').DataTable({
        responsive: true,
        'ajax': {
            'url': '/datapenjualan',
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
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
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
                "sClass": "text-center col-md-3 td-aksi",
                'render': function (data, type, full, meta) {
                    var kembali = '';
                    kembali += '<a title="Lihat Transaksi" class="btn btn-info btn-flat" href="/penjualan/'+data+'"><i class="fa fa-eye fa-fw"></i> </a>';
                    kembali += '<button title="Cetak Struk Jual" class="btn btn-default btn-flat" onclick="CetakClick(this)"><i class="fa fa-print fa-fw"></i> </button>';
                        
                        if (bolehUbah == 'admin') {
                            kembali += '<a title="Koreksi Transaksi" class="btn btn-warning btn-flat" href="#" onclick="UbahClick('+data+');"><i class="fa fa-pencil-square-o fa-fw"></i> </a>';
                            kembali += '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash fa-fw"></i> </button>';
                        }
                       
                    return kembali;

                }
            }
        ],
        'rowCallback': function (row, data, dataIndex) {
             if (bolehUbah == 'admin') {
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[4]);
                }
            $(row).find('button[class="btn btn-default btn-flat"]').prop('value', data[0]);

        }
    });

    $('#start').on('change', function (e) {
        reloadTable();
    });
    $('#end').on('change', function (e) {
        reloadTable();
    });

        });

        function CetakClick(id) {
    $.get('/strukjual/'+id.value, function(res) {
    });
}

function reloadTable() {
    var table = $('#dataTableBuilder').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

function UbahClick(id) {
        var route = "/siapkankoreksipenjualan";
        var token = $('#token').val();

        $.ajax({
            url: route,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': token},
            dataType: 'json',
            data: {
                id: id,
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
            success: function () {
                window.location.href = '/penjualan/' + id + '/edit';
            }
        });
}

function HapusClick(btn) {
    $('#idHapus').val(btn.value);
}

$('#yakinhapus').click(function () {
    var token = $('#token').val();
    var id = $('#idHapus').val();
    var route = "/penjualan/" + id;

    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'DELETE',
        dataType: 'json',
        error: function (res) {
            var errors = res.responseJSON;
            var pesan = '';
            $.each(errors, function (index, value) {
                pesan += value + "\n";
            });

            alert(pesan);
        },
        success: function () {
            reloadTable();
            alert('Sukses Menghapus Transaksi Penjualan');
            $('#modalHapus').modal('toggle');
        }
    });
});
    </script>
@endsection