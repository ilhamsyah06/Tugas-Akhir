@extends('layouts.master')

@section('title','Detail Pembelian')
    
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-primary">
            <div class="panel panel-heading">
                <h3 class="panel-title">Detail Pembelian Barang</h3>
            </div>
            <div class="panel-body">
            {!!  Form::hidden('kode', $pembelian->kode, ['id'=>'kode', 'class' => 'form-control'])  !!}
                <div class="form-group">
                    {!! Form::label('kode', 'No. Bukti : ') !!} {{ $pembelian->kode }}
                </div>
                <div class="form-group">
                    {!! Form::label('tgl', 'Tgl. Transaksi : ' ) !!} {{ $pembelian->tgl_pembelian->format('d-m-Y') }}
                </div>
                <div class="form-group">
                    {!! Form::label('user', 'Operator : ') !!} {{ $pembelian->user->name }}
                </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilder">
                        <thead>
                        <tr>
                            <th class="col-md-2">Kode</th>
                            <th class="col-md-2">Nama</th>
                            <th class="col-md-2">Jenis</th>
                            <th class="col-md-2">Harga</th>
                            <th class="col-md-1">QTY</th>
                            <th class="col-md-2">Sub Total</th>
                        </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align:right; font-size:22px; color: #9d1500;">Rp 0</th>
                            </tr>
                            </tfoot>
                        <tbody>

                        </tbody>
                    </table>
                
                
            </div>
            <div class="panel-footer">
            <a href="{{ url('listpembelian') }}" class="btn btn-primary btn-flat"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection

@section('footer')
    <script>
        $(document).ready(function() {
    $('#dataTableBuilder').DataTable({
        ordering: false,
        searching:false,
        paging: false,
        responsive: true,
        info:false,
        'ajax': {
            'url': '/getdetailbeli',
            'data': function (d) {
                d.kode = $('#kode').val();
            }
        },
        'columnDefs': [
            {
                'targets':0,
                'sClass': "text-center col-md-2"
            },{
                'targets':1,
                'sClass': "col-md-2"
            },{
                'targets':2,
                'sClass': "col-md-2"
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
            },{
                'targets':5,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            }
        ],
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;
            if (data.length > 0) {
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\$Rp,.]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages

                total = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    } );

                // // Total over this page
                // pageTotal = api
                //     .column( 3, { page: 'current'} )
                //     .data()
                //     .reduce( function (a, b) {
                //         return intVal(a) + intVal(b);
                //     }, 0 );

                // Update footer
                $( api.column( 0 ).footer() ).html(
                    //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                    'Rp '+ number_format(total, 0, ',', '.')  +''
                );
            } else {
                $( api.column(0).footer() ).html(
                   //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                   'Rp 0'
                );
            }
        },
    });

});
    </script>
@endsection