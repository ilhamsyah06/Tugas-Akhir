@extends('layouts.master')

@section('title','Barcode')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Cetak Barcode</h4>
            </div>
            <div class="panel-body">
                <form action="{{ url('viewbarcode')}}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="kode">Kode : </label>
                        <div class="input-group">
                            <input type="text" name="kode" id="kode" value="" class="form-control"
                                placeholder="Kode/Barcode Barang" />
                            <span class="input-group-btn">
                                <a class="btn btn-success" type="button" data-toggle="modal" data-target="#modalCari"
                                    id="caribarang" onClick="CariClick(this);">
                                    <i class="fa fa-search"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="qty">Jumlah : </label>
                        <input type="number" class="form-control" name="qty" id="qty" required />
                    </div>
                    <input type="hidden" id="harga" name="harga">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-barcode"></i> Tampilkan
                            Barcode</i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('master.barcode.modal')
@endsection

@section('footer')
<script>
    $('#dataTableBuilderCari').DataTable({
        responsive: true,
        'ajax': {
            'url': '/caribarangbarcode',
        },
        'columnDefs': [{
            'targets': 0,
            'sClass': "text-center col-lg-2",
            render: function (data, type, row, meta) {
                return '<span style="font-size: 12px;" class="label label-danger' + '">' + data +
                    '</span>';
            }
        }, {
            'targets': 1,
            'sClass': "col-lg-3"
        }, {
            'targets': 2,
            'sClass': "col-lg-2",
            render: function (data, type, row, meta) {
                return '<span style="font-size: 12px;" class="label label-primary' + '">' + data +
                    '</span>';
            }
        }, {
            'targets': 3,
            'sClass': "text-right col-lg-1",
            'render': function (data, type, full, meta) {
                return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

            }
        }, {
            'targets': 4,
            'searchable': false,
            "orderable": false,
            "orderData": false,
            "orderDataType": false,
            "orderSequence": false,
            "sClass": "text-center col-lg-2 td-aksi",
            'render': function (data, type, full, meta) {
                var kembali = '';
                kembali +=
                    '<button title="Pilih Data" class="btn btn-success btn-flat" onclick="PilihClick(this);"><i class="fa fa-hand-pointer-o fa-fw"></i> </button>';


                return kembali;

            }
        }],
        'rowCallback': function (row, data, dataIndex) {
            $(row).find('button[class="btn btn-success btn-flat"]').prop('value', data[4]);
        }
    });

    function PilihClick(btn) {
        route = "/barang/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#kode').val(res.kode);
            $('#harga').val(res.hargajual);
            $('#modalCari').modal('toggle');
        });
    }

    function CariClick(btn) {
        reloadTableCari();
    }

    function reloadTableCari() {
        var table = $('#dataTableBuilderCari').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }
</script>
@endsection