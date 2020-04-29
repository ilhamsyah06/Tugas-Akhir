@extends('layouts.master')

@section('title')
    Laporan Daftar Barang
@endsection

@section('content')
    <section class="content">
        <div class="row">
            
                <div class="col-xs-3">
                    <div class="panel panel-default">
                        <div class="panel-heading with-border">
                            <h3 class="panel-title"><i class="fa fa-filter"></i> Plih Kategori Barang</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                    <label for="jenis">Kategori Barang : </label>
                                    
                                      {!!  Form::select('jenis', [], null, ['id'=>'jenis','class' => 'form-control select2', 'style'=>"width:100%;"])  !!} 
                                    
                                </div>
                        </div> 

                        <div class="panel-footer">
                            {!! Form::open(['url' => '/cetakbarang', 'method'=>'POST', 'id'=>'formcetak']) !!}
                                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-print"></i>
                                        Preview Laporan
                                    </button>
                                    {!! Form::close() !!}
                        </div>  
                    </div>
                </div>
                <div class="col-xs-9">
            
                <div class="panel panel-default">
                    <div class="panel-heading with-border">
                        <h3 class="panel-title"><i class="fa fa-cubes"></i> Laporan Daftar Barang</h3>
                    </div>
                    

                    <div class="panel-body pad table-responsive">
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilder">
                            <thead>
                            <tr>
                                <th class="col-md-2">Kode</th>
                                <th class="col-md-2">Nama</th>
                                <th class="col-md-2">Jenis</th>
                                <th class="col-md-2">Stok Toko</th>
                                <th class="col-md-2">Stok Gudang</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                    

                </div>
                <!-- /.box -->
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
    var route = "/levelapi";
    var inputTipe = $('#jenis');

    var list = document.getElementById("jenis");
    while (list.hasChildNodes()) {
        list.removeChild(list.firstChild);
    }
    inputTipe.append('<option value=" ">Semua Jenis Barang</option>');

    $.get(route, function (res) {
        $.each(res.data, function (index, value) {
            inputTipe.append('<option value="' + value[1] + '">' + value[0] + '</option>');
        });
    });

    $("#jenis").select2();


    $('#dataTableBuilder').DataTable({
        responsive: true,
        'ajax': {
            'url': '/datalaporanbarang',
            'data': function (d) {
                d.jenis = $('#jenis').val();
            }
        },

        'columnDefs': [
            {
                'targets':0,
                'sClass': "text-center col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-danger">' + data + '</span>';
                }
            },{
                'targets':1,
                'sClass': "col-md-2"
            },{
                'targets':2,
                'sClass': "col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-primary">' + data + '</span>';
                }
            },{
                'targets':3,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            },{
                'targets':4,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            }
        ]
    });

    $('#jenis').on('change', function (e) {
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

        var jenis = $('#jenis').val();

        var form = this;

        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'jenis')
                .val(jenis)
        );
    });
</script>
@endsection

