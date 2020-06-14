@extends('layouts.master')

@section('title','Retur Barang')

@section('content')
@if ($message = Session::get('warning'))
<div class="alert alert-danger alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button> 
  <strong><i class="fa fa-warning"></i> {{ $message }}</strong>
</div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h3 class="panel-title"><i class="fa fa-refresh"></i> Retur Barang</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="invoice"><i class="fa fa-search"></i> Cari No Invoice</label>
                    <div class="input-group">
                        {!! Form::hidden('invoiceasli', null, ['id'=>'invoiceasli', 'class' => 'form-control',
                        'id'=>'invoiceasli']) !!}
                        <input type="hidden" id="idpenjualan">
                        <input type="text" name="invoice" id="invoice" value="" class="form-control"
                            placeholder="Cari No. Invoice" autofocus="on" autocomplete="off" />
                        <span class="input-group-btn">
                            <a class="btn btn-info" type="button" data-toggle="modal" data-target="#modalCariInvoice"
                                id="cariinvoice" onClick="CariClick(this);">
                                <i class="fa fa-search"></i>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for=""><i class="fa fa-calendar"></i> Tanggal Retur :</label>
                    <input type="text" name="tgl" id="tgl" value="" class="form-control inputantgl"
                        placeholder="MM/DD/YYYY" readonly />
                </div>
                <div class="form-group">
                    <a href="#" id="returtambah" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Retur Barang</a>
                </div>
            </div>
        </div>
    </div>
        <div class="col-sm-6 col-md-6">
            <div class="alert-message alert-message-warning">
                <h4><i class="fa fa-warning"></i> Syarat Melakukan Retur Barang</h4>
                <p>1. Jumlah Pembelian Harus Lebih Dari 1.<br>2. Retur Hanya Bisa Dilakukan Di Hari Transaksi.<br>3. Hanya Dapat Meretur 1 Jenis Barang Saja. <br> 4. Barang yang di kembalikan harus keadaan sempurna (Bukti Transaksi, Label Barcode).</p>
            </div>
        </div>
</div>
@include('master.retur.modal')
@endsection

@section('footer')
<script>
    $(document).ready(function () {

        var t = $('#dataTableBuilderCari').DataTable({
            responsive: true,
            scrollX: true,
            'ajax': {
                'url': '/apipenjualan',
            },
            'columnDefs': [{
                'targets': 0,
                'sClass': "col-md-1 ext-center"
            }, {
                'targets': 1,
                'sClass': "col-md-2 text-center"
            }, {
                'targets': 2,
                'sClass': "col-md-1 text-center"
            }, {
                'targets': 3,
                'sClass': "col-md-1 text-center",
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            }, {
                'targets': 4,
                'sClass': "col-md-1 text-center",
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            }, {
                'targets': 5,
                'sClass': "col-md-1 text-center",
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            }, {
                'targets': 6,
                'sClass': "col-md-1 text-center"
            }, {
                'targets': 7,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "col-md-2 text-center td-aksi",
                'render': function (data, type, full, meta) {
                    var kembali = '';
                    kembali +=
                        '<button title="Pilih Data" class="btn btn-success btn-flat" onclick="PilihClick(this);"><i class="fa fa-hand-pointer-o fa-fw"></i> </button>';


                    return kembali;

                }
            }],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-success btn-flat"]').prop('value', data[7]);
            }

        });

        t.on('order.dt search.dt', function () {
            t.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

    });



    function PilihClick(btn) {
        route = "/retur/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#invoice').val(res.invoice);
            $('#idpenjualan').val(res.id);
            $('#modalCariInvoice').modal('toggle');
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

    //---datepicker javascript----//
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    $('.inputantgl').datepicker({
        format: "dd/mm/yyyy",
        container: container,
        todayHighlight: true,
        autoclose: true,
    });
    $(".inputantgl").datepicker("setDate", new Date());
    //--- datepicker javascript----//

    $('#returtambah').click(function () {
        var route = "/siapkanretur";
        var token = $('#token').val();

        var id = $('#idpenjualan').val();
        if (jQuery.trim(id) == '' || id == undefined) {
            alert('Kode Invoice Kosong');
            $('#idpenjualan').focus();
            return;
        }

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
                window.location.href = '/tambahretur/' + id + '/returadd';
            }
        });
});
</script>
@endsection