@extends('layouts.master')

@section('title','Koreksi Pembelian')

@section('content')
<div class="row">
    <input type="hidden" id="idubahsekali" value="{{$pembelian->id}}">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-plus"></i> Barang</h4>
            </div>
            <div class="panel-body">
                @include('master.pembelian.form-tambah')
            </div>
            <div class="panel-footer">
                <a href="/listpembelian" class="btn btn-warning btn-flat" id="batal"><i class="fa fa-close"></i> Batal</a>
                <a href="#" class="btn btn-primary btn-flat pull-right" id="tambahbaris"><i
                        class="fa fa-plus-square"></i> Tambah Baris</a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-cart-plus"></i> Detail Pembelian Barang</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6 form-horizontal form-left">
                        <div class="form-group">
                            <label for="namasupplier" class="control-label col-sm-4">Supllier: </label>
                            <div class="col-sm-8">
                                <input type="hidden" name="idsupplier" id="idsupplier"
                                    value="{{ $pembelian->supplier_id }}" class="form-control" />
                                <input type="text" name="namasupplier" id="namasupplier"
                                    value="{{ $pembelian->supplier->nama_supplier }}" class="form-control"
                                    placeholder="Nama Supplier" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="tgl" class="control-label col-sm-4">Tanggal: </label>
                            <div class="col-sm-8">
                                <input type="text" name="tgl" id="tgl"
                                    value="{{ $pembelian->tgl_pembelian->format('m/d/Y') }}"
                                    class="form-control inputantgl" placeholder="MM/DD/YYYY" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-horizontal form-left">
                        <div class="form-group">
                            <label for="kode" class="control-label col-sm-4">No. Bukti: </label>
                            <div class="col-sm-8">
                                <input type="text" name="kode" id="kode" value="{{$pembelian->kode}}"
                                    class="form-control" disabled />
                                <input type="hidden" name="totalbayar" id="totalbayar"
                                    value="{{ $pembelian->total_bayar }}" class="form-control" readonly disabled />
                            </div>
                        </div>
                    </div>
                </div>

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilder">
                    <thead>
                        <tr>
                            <th class="text-center col-md-2">Kode</th>
                            <th class="text-center col-md-3">Nama</th>
                            <th class="text-center col-md-1">QTY</th>
                            <th class="text-center col-md-2">Harga</th>
                            <th class="text-center col-md-2">SubTotal</th>
                            <th class="text-center col-md-2">Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="text-align:right; padding:20px; font-size:22px; color: #000000;">Rp.0
                            </th>
                        </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>

                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ url('listpembelian')}}" class="btn btn-info"> List Pembelian</a>
                            <a href="#" class="btn btn-success btn-flat pull-right" id="simpan"><i
                                    class="fa fa-save"></i> Selesai
                                dan Simpan Transaksi</a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
@include('layouts.modalhapus')
@include('master.pembelian.modal')
@endsection

@section('footer')
<script>
    $(document).ready(function () {
        var table = $('#dataTableBuilder').DataTable({
            ordering: false,
            searching: false,
            paging: false,
            responsive: true,
            info: false,
            'ajax': {
                'url': '/getsementara',
                'data': function (d) {
                    d.kode = $('#kode').val();
                }
            },
            'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-md-2",
            }, {
                'targets': 1,
                'sClass': "col-md-3",
            }, {
                'targets': 2,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
            }, {
                'targets': 3,
                'sClass': "text-right col-md-2",
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
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-2 td-aksi",
                'render': function (data, type, full, meta) {
                    var kembali =
                        '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="fa fa-edit fa-fw"></i> </button>';
                    kembali +=
                        '<button title="Hapus Item" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash fa-fw"></i> </button>';

                    return kembali;
                }
            }],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[5]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[5]);
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;
                if (data.length > 0) {
                    total = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            // console.log(a);
                            return intVal(a) + intVal(b);
                        });

                    // Update footer
                    $(api.column(1).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp.'
                         + number_format(total, 0, ',', '.') + ''
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

        //--- Pencarian Barang ketik ajax--//
        var timer;

        $('#barang').on('keydown', function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 13) {
                clearTimeout(timer);
            }
        }).on('keyup', function (e) {
            timer = setTimeout(function () {
                //alert disini
                var keywords = $('#barang').val();

                if (keywords.length > 0) {

                    $.get('/findbarang/kode/' + keywords, function (res) {
                        if (res.nama == null) {
                            $('#barangasli').val(null);
                            $('#harga_beli').val(null);
                            $('#nama').val('Tidak ditemukan');
                        } else {
                            $('#barangasli').val(res.kode);
                            $('#harga_beli').val(number_format(res.harga_beli, 0, ',',
                                '.'));
                            $('#nama').val(res.nama);
                        }
                    });
                }
            }, 500);
        });

        //--Pencarian Barang ketik ajax--- //

        //---datepicker javascript----//
        var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
        $('.inputantgl').datepicker({
            format: "mm/dd/yyyy",
            container: container,
            todayHighlight: true,
            autoclose: true,
        });


        $('.inputanangka').on('keypress', function (e) {
            var c = e.keyCode || e.charCode;
            switch (c) {
                case 8:
                case 9:
                case 27:
                case 13:
                    return;
                case 65:
                    if (e.ctrlKey === true) return;
            }
            if (c < 48 || c > 57) e.preventDefault();
        }).on('keyup', function () {
            //alert('disini');
            var inp = $(this).val().replace(/\./g, '');
            $(this).val(formatRibuan(inp));

            loadTotal();
        });

        $('#barangasli').val(null);

        $('#barang').focus();

    });

    // ----- Cari Barang ------- //

    $('#tabelcari').DataTable({
        responsive: true,
        'ajax': {
            'url': '/barangpembelian',
        },
        'columnDefs': [{
            'targets': 0,
            'sClass': "text-center col-lg-2",
            render: function (data, type, row, meta) {
                return '<span style="font-size: 12px;" class="label label-danger' +
                    '">' + data + '</span>';
            }
        }, {
            'targets': 1,
            'sClass': "col-lg-3"
        }, {
            'targets': 2,
            'sClass': "col-lg-2",
            render: function (data, type, row, meta) {
                return '<span style="font-size: 12px;" class="label label-primary' +
                    '">' + data + '</span>';
            }
        }, {
            'targets': 3,
            'sClass': "text-right col-lg-2",
            'render': function (data, type, full, meta) {
                return number_format(intVal(data), 0, ',', '.');

            }
        }, {
            'targets': 4,
            'sClass': "text-right col-lg-1",
            'render': function (data, type, full, meta) {
                return number_format(intVal(data), 0, ',', '.');

            }
        }, {
            'targets': 5,
            'searchable': false,
            "orderable": false,
            "orderData": false,
            "orderDataType": false,
            "orderSequence": false,
            "sClass": "text-center col-lg-2 td-aksi",
            'render': function (data, type, full, meta) {
                var kembali = '';
                kembali +=
                    '<button title="Pilih Data" class="btn btn-success btn-flat" onclick="PilihClickBarang(this);"><i class="fa fa-hand-pointer-o fa-fw"></i> </button>';
                return kembali;

            }
        }],
        'rowCallback': function (row, data, dataIndex) {
            $(row).find('button[class="btn btn-success btn-flat"]').prop('value', data[5]);
        }
    });

    function CariClick(btn) {
        reloadTableCari();
    }

    function reloadTableCari() {
        var table = $('#tabelcari').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }

    //--- Fungsi PilihClick Barang modal ----///
    function PilihClickBarang(btn) {

        route = "/barang/" + btn.value + "/edit";
        $.get(route, function (res) {
            $('#barangasli').val(res.kode);
            $('#barang').val(res.kode);
            $('#nama').val(res.nama);
            $('#harga_beli').val(number_format(res.hargabeli, 0, ',', '.'));
            $('#qty').val('1');

            $('#barang').focus();

            $('#modalCariBarang').modal('toggle');

            loadTotal();


        });
    }
    //--------------------------------//

    function loadTotal() {
        var qty = $('#qty').val();
        var harga = $('#harga_beli').val();
        $('#total').val(null);

        if (qty != undefined && jQuery.trim(qty) != '' && intVal(qty) > 0 && harga != undefined && jQuery.trim(harga) !=
            '' && intVal(harga) > 0) {
            var total = intVal(qty) * intVal(harga);
            $('#total').val(number_format(total, 0, ',', '.'));
        }

        var qty1 = $('#qtyubah').val();
        var harga1 = $('#harga_beliubah').val();
        $('#totalubah').val(null);

        if (qty1 != undefined && jQuery.trim(qty1) != '' && intVal(qty1) > 0 && harga1 != undefined && jQuery.trim(
                harga1) != '' && intVal(harga1) > 0) {
            var total1 = intVal(qty1) * intVal(harga1);
            $('#totalubah').val(number_format(total1, 0, ',', '.'));
        }
    }

    function reloadTable() {
        var table = $('#dataTableBuilder').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }

    ///---- function tambah barang di keranjang ----///
    $('#tambahbaris').click(function () {

        var route = "/sementara";
        var token = $('#token').val();

        var kode = $('#kode').val();
        if (jQuery.trim(kode) == '' || kode == undefined) {
            alert("Kode tidak valid!");
            window.location.href = '/pembelian';
        }

        var barang = $('#barangasli').val();
        if (jQuery.trim(barang) == '' || barang == undefined) {
            alert('Kode barang tidak boleh dikosongkan');
            $('#barang').focus();
            return;
        }

        var qty = $('#qty').val();
        if (jQuery.trim(qty) == '' || qty == ' ' || intVal(qty) <= 0) {
            alert('QTY pembelian tidak valid');
            $('#qty').focus();
            return;
        }

        qty = intVal(qty);

        var harga = $('#harga_beli').val();
        if (jQuery.trim(harga) == '' || harga == ' ' || intVal(harga) <= 0) {
            alert('Harga pembelian tidak valid');
            $('#harga_beli').focus();
            return;
        }

        harga = intVal(harga);

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
                barang: barang,
                kode: kode,
                qty: qty,
                harga: harga,
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

                $('#barang').val(null);
                $('#barangasli').val(null);
                $('#nama').val(null);
                $('#harga_beli').val(null);
                $('#total').val(null);
                $('#qty').val(null);

                $('#barang').focus();
            }
        });
    });

    function UbahClick(btn) {
        var route = "/sementara/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#idubah').val(res.id);
            $('#barangubah').val(res.barang);
            $('#namaubah').val(res.nama);
            $('#harga_beliubah').val(number_format(intVal(res.harga), 0, ',', '.'));
            $('#qtyubah').val(number_format(intVal(res.jumlah), 0, ',', '.'));
            $('#totalubah').val(number_format(intVal(res.total), 0, ',', '.'));

            $('#qtyubah').focus();

        });

    }

    $('#simpanubah').click(function () {
        var id = $('#idubah').val();
        var route = "/sementara/" + id;
        var token = $('#token').val();

        var harga = $('#harga_beliubah').val();
        if (jQuery.trim(harga) == '' || harga == ' ' || intVal(harga) <= 0) {
            alert('Harga pembelian tidak valid');
            $('#harga_beliubah').focus();
            return;
        }

        harga = intVal(harga);

        var qty = $('#qtyubah').val();
        if (jQuery.trim(qty) == '' || qty == ' ' || intVal(qty) <= 0) {
            alert('QTY pembelian tidak valid');
            $('#qtyubah').focus();
            return;
        }

        qty = intVal(qty);

        var diskon = '';

        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                qty: qty,
                harga: harga,
                diskon: diskon,
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
                $('#modalUbah').modal('toggle');
            }
        });
    });

    function HapusClick(btn) {
        $('#idHapus').val(btn.value);
    }

    $('#yakinhapus').click(function () {
        var token = $('#token').val();
        var id = $('#idHapus').val();
        var route = "/sementara/" + id;

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
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
                $('#modalHapus').modal('toggle');
            }
        });
    });

    $('#simpan').click(function () {
        var kode = $('#kode').val();
        if (jQuery.trim(kode) == '' || kode == undefined) {
            alert("Kode tidak valid!");
            window.location.href = '/pembelian';
        }

        var idsupplier = $('#idsupplier').val();
        if (jQuery.trim(idsupplier) == '' || idsupplier == undefined) {
            alert('Harap Isikan Nama Supplier');
            $('#idsupplier').focus();
            return;
        }

        var tgl = $("#tgl").val();
        if (jQuery.trim(tgl) == '' || tgl == ' ' || tgl == undefined) {
            alert('Inputkan tanggal pembelian dengan benar !');
            $('#tgl').focus();
            return;
        }

        var totalbayar = $('#totalbayar').val();

        var table = $('#dataTableBuilder').DataTable();
        if (!table.data().count()) {
            alert('Tidak ada data detail pembelian di tabel !!');
            return;
        }

        var id = $('#idubahsekali').val();
        var token = $('#token').val();
        var route = "/pembelian/" + id;

        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                tgl: tgl,
                kode: kode,
                idsupplier: idsupplier,
                total: totalbayar,
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
                return swal({
                    type: 'success',
                    title: 'Sukses Melakukan Koreksi Pembelian.',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {
                    window.location.href = '/listpembelian';
                });

            }
        });
    });
</script>
@endsection