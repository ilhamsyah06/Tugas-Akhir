@extends('layouts.master')

@section('title','Tambah Retur')

@php
    $date = date('Y-m-d');
@endphp

@section('content')
<div class="row">
    <input type="hidden" id="idpenjualan" value="{{$retur->id}}">
    <!--------->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h3 class="panel-title"><i class="fa fa-cart-plus"></i> Retur Barang</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="kasir" class="control-label col-sm-4"><i class="fa fa-user"></i> Kasir :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="kasir" id="kasir" value="{{ $retur->user->name}}"
                                    class="form-control" disabled />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="tgl" class="control-label col-sm-4"><i class="fa fa-calendar"></i> Tanggal Retur:
                            </label>
                            <div class="col-sm-8">
                                <input type="date" name="tgl" id="tgl"
                                    value="{{ $date }}"
                                    class="form-control inputantgl" placeholder="MM/DD/YYYY" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="noretur" class="control-label col-sm-4"><i class="fa fa-user"></i> Nomor Retur :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="noretur" id="noretur" class="form-control" disabled />
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="invoice" class="control-label col-sm-4"><i class="fa fa-address-card-o"></i> No.
                                Invoice : </label>
                            <div class="col-sm-8">
                                <input type="text" name="invoice" id="invoice" value="{{$retur->no_invoice}}"
                                    class="form-control" disabled />
                            </div>
                        </div>
                    </div>
                </div>

                <table width="100%" class="table table-striped table-bordered table-hover" id="tabelpenjualan">
                    <thead>
                        <tr>
                            <th class="text-center col-md-2">Kode</th>
                            <th class="text-center col-md-2">Nama</th>
                            <th class="text-center col-md-1">QTY</th>
                            <th class="text-center col-md-2">Diskon Rp.</th>
                            <th class="text-center col-md-2">Harga</th>
                            <th class="text-center col-md-2">SubTotal</th>
                            <th class="text-center col-md-2">Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align:right; padding:20px; font-size:22px; color: #000000;">Rp.0
                            </th>
                        </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
                <input type="hidden" id="totalbayar" name="totalbayar">
                <input type="hidden" id="jumlahbayar" name="jumlahbayar" value="{{ $retur->jumlah_bayar }}">
                <hr>
                <h4 class="text-center"><span class="label label-success"><i class="fa fa-refresh"></i> Keranjang Barang Yang Di Retur</span></h4>
                <table width="100%" class="table table-striped table-bordered table-hover" id="tabelretur">
                    <thead>
                        <tr>
                            <th class="text-center col-md-2">Kode</th>
                            <th class="text-center col-md-2">Nama</th>
                            <th class="text-center col-md-1">QTY</th>
                            <th class="text-center col-md-2">Diskon Rp.</th>
                            <th class="text-center col-md-2">Harga</th>
                            <th class="text-center col-md-2">SubTotal</th>
                            <th class="text-center col-md-2">Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align:right; padding:20px; font-size:22px; color: #000000;">Rp.0
                            </th>
                        </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
                <input type="hidden" id="totalbayarretur" name="totalbayarretur">
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modalBantu"><i class="fa fa-exclamation"></i> Bantuan</button>
                            <a href="{{ url('listretur') }}" class="btn btn-danger"><i class="fa fa-list-alt"></i>
                                List Retur</a>
                            <div class="btn-group pull-right row">
                                <div class="col-md-3"><a class="btn btn-default" type="button" href="{{ url('retur') }}"><i class="fa fa-close"></i> Batalkan</a></div>
                                <div class="col-md-1"></div>
                                <div class="col-md-3"><a class="btn btn-primary" id="simpan" type="button"><i class="fa fa-send"></i> Selesai dan Simpan
                                        (ENTER)</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('layouts.modalhapus')
@endsection

@section('footer')
<script>
    $(document).ready(function () {

        var table = $('#tabelpenjualan').DataTable({
            ordering: false,
            searching: false,
            paging: false,
            responsive: true,
            info: false,
            'ajax': {
                'url': '/getsementarapenjualan',
                'data': function (d) {
                    d.kode = $('#invoice').val();
                }
            },
            'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-md-2",
            }, {
                'targets': 1,
                'sClass': "col-md-2",
            }, {
                'targets': 2,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 3,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 4,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    // return number_format(intVal(data), 0, ',', '.');
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 5,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    // return number_format(intVal(data), 0, ',', '.');
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 6,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-3 td-aksi",
                'render': function (data, type, full, meta) {
                    var kembali =
                        '<button title="Retur 1 Barang" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalReturSatu" onclick="retursatu(this);"><i class="fa fa-arrow-down"></i> 1</button>';
                    kembali +=
                        '<button title="Retur Jumlah Semua Barang" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalReturSemua" onclick="retursemua(this);"><i class="fa fa-arrow-down"></i> All</button>';

                    return kembali;
                }
            }],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[6]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[6]);
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;
                if (data.length > 0) {
                    total = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            // console.log(a);
                            return intVal(a) + intVal(b);
                        });
                    diskon = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            // console.log(a);
                            return intVal(a) + intVal(b);
                        });
                    // Update footer
                    $(api.column(1).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp.' + number_format(total, 0, ',', '.') + ''
                    );
                    $('#totalbayar').val(total);
                } else {
                    $(api.column(1).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp 0'
                    );
                }
            },
        });

        //-------tabel retur-----//
        var table = $('#tabelretur').DataTable({
            ordering: false,
            searching: false,
            paging: false,
            responsive: true,
            info: false,
            'ajax': {
                'url': '/getsementararetur',
                'data': function (d) {
                    d.kode = $('#invoice').val();
                }
            },
            'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-md-2",
            }, {
                'targets': 1,
                'sClass': "col-md-2",
            }, {
                'targets': 2,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 3,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 4,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    // return number_format(intVal(data), 0, ',', '.');
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 5,
                'sClass': "text-right col-md-2",
                'render': function (data, type, full, meta) {
                    // return number_format(intVal(data), 0, ',', '.');
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 6,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-3 td-aksi",
                'render': function (data, type, full, meta) {
                    var kembali =
                        '<button title="Kembalikan 1 barang" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalKembalisatu" onclick="kembalisatu(this);"><i class="fa fa-arrow-up fa-fw"></i> 1</button>';
                    kembali +=
                        '<button title="Kembalikan Semua Jumlah Barang" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalKembaliSemua" onclick="kembalisemua(this);"><i class="fa fa-arrow-up fa-fw"></i> All</button>';

                    return kembali;
                }
            }],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[6]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[6]);
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;
                if (data.length > 0) {
                    totalretur = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            // console.log(a);
                            return intVal(a) + intVal(b);
                        });
                    // Update footer
                    $(api.column(1).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp.' + number_format(totalretur, 0, ',', '.') + ''
                    );
                    // Update footer
                    $(api.column(1).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp.' +
                        number_format(totalretur, 0, ',', '.') + ''
                    );
                    $('totalbayarretur').val(totalretur);
                } else {
                    $(api.column(1).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp.0'
                    );
                }
            },
        });
        kodeOtomatis();
    });

    function reloadTable() {
        var table = $('#tabelpenjualan').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }

    function reloadTableRetur() {
        var table = $('#tabelretur').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }

    function kodeOtomatis() {
        route = "/returkode";
        $.get(route, function (res) {
            $('#noretur').val(res);
            //reloadTable();
        });
    }

    function retursemua(btn) { // function tombol retur semua
        $('#retursemua').val(btn.value);
    }

    $('#yakinretursemua').click(function () { // function retur semua
        var token = $('#token').val();
        var id = $('#retursemua').val();
        var noretur = $('#noretur').val();
        var route = "/retursemua";

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                noretur: noretur,
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
                reloadTable();
                reloadTableRetur();
                $('#modalReturSemua').modal('toggle');
            }
        });
    });

    function kembalisemua(btn) { // function tombol mengambalikan semua
        $('#kembalisemua').val(btn.value)
    }

    $('#yakinkembalisemua').click(function () { // function tombol mengambalikan semua
        var token = $('#token').val();
        var id = $('#kembalisemua').val();
        var route = "/kembalisemua";

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'POST',
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
                reloadTableRetur();
                reloadTable();
                $('#modalKembaliSemua').modal('toggle');
            }
        });
    });


    function retursatu(btn) { //function tombol retur barang satu
        $('#retursatu').val(btn.value)
    }

    $('#yakinretursatu').click(function () { // function retur barang satu
        var token = $('#token').val();
        var id = $('#retursatu').val();
        var noretur = $('#noretur').val();
        var route = "/retursatu";

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                noretur,
                noretur,
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
                reloadTableRetur();
                reloadTable();
                $('#modalReturSatu').modal('toggle');
            }
        });
    });

    function kembalisatu(btn) {
        $('#kembalisatu').val(btn.value)
    }

    $('#yakinkembalisatu').click(function () {
        var token = $('#token').val();
        var id = $('#kembalisatu').val();
        var route = "/kembalisatu";

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'POST',
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
                reloadTableRetur();
                reloadTable();
                $('#modalKembalisatu').modal('toggle');
            }
        });
    });

    $('#simpan').click(function () {
  

        //data penjualan yang diretur
        var idpenjualan = $('#idpenjualan').val();
        var totalbayar = $('#totalbayar').val();
        var jumlahbayar = $('#jumlahbayar').val();
        var invoice = $('#invoice').val();
        if (jQuery.trim(invoice) == '' || invoice == undefined) {
            alert("Nomor invoice tidak valid!");
            window.location.href = '/retur';
            return;
        }

        //data retur
        var nomoretur = $('#noretur').val();
        var totalbayarretur = $('#totalbayarretur').val();
        var tglretur = $('#tgl').val();

        var route = "/retur";
        var token = $('#token').val();

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
                idpenjualan: idpenjualan,
                totalbayar: totalbayar,
                jumlahbayar: jumlahbayar,
                invoice: invoice,
                nomoretur: nomoretur,
                totalbayarretur: totalbayarretur,
                tglretur: tglretur,
                _token: token
            },
            error: function (res) {
                var errors = res.responseJSON;
                var pesan = '';
                $.each(errors, function (index, value) {
                    pesan += value + "\n";
                });

                return swal({
                    type: 'error',
                    title: pesan,
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});
            },
            success: function () {
                return swal({
                    type: 'success',
                    title: 'Sukses Retur Disimpan !',
                    timer: 700
                }).catch(function (timeout) {
                    window.location.replace("/retur");

                });
            }
        });
    });
    
</script>
@endsection