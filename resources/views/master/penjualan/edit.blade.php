@extends('layouts.master')

@section('title','Koreksi Penjualan')

@section('content')
<div class="row">
    <input type="hidden" id="idubahsekali" value="{{$penjualan->id}}">
    <!--------->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h3 class="panel-title"><i class="fa fa-cart-plus"></i> Transaksi Barang</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="kasir" class="control-label col-sm-4"><i class="fa fa-user"></i> Kasir :
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="kasir" id="kasir" value="{{ $penjualan->user->name}}"
                                    class="form-control" disabled />
                                <input type="hidden" name="totalbayar" id="totalbayar" value="" class="form-control"
                                    disabled />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="tgl" class="control-label col-sm-4"><i class="fa fa-calendar"></i> Tanggal :
                            </label>
                            <div class="col-sm-8">
                            <input type="text" name="tgl" id="tgl" value="{{ date('d-m-Y', strtotime($penjualan->tgl_penjualan)) }}" class="form-control inputantgl"
                                    placeholder="MM/DD/YYYY" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="invoice" class="control-label col-sm-4"> Kode Barang : </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    {!! Form::hidden('barangasli', null, ['id'=>'barangasli', 'class' => 'form-control',
                                    'id'=>'barangasli']) !!}
                                    <input type="text" name="kode" id="kode" value="" class="form-control"
                                        placeholder="Kode/Barcode Barang (F1)" autofocus="on" autocomplete="off" />
                                    <span class="input-group-btn">
                                        <a class="btn btn-success" type="button" data-toggle="modal"
                                            data-target="#modalCariBarang" id="caribarang" onClick="CariClick(this);">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="invoice" class="control-label col-sm-4"><i class="fa fa-address-card-o"></i> No.
                                Invoice : </label>
                            <div class="col-sm-8">
                                <input type="text" name="invoice" id="invoice" value="{{$penjualan->no_invoice}}" class="form-control" disabled />
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-12 form-horizontal">
                        <div class="form-group">
                            {!! Form::label('namabarang', 'Nama Barang :', ['class' => 'control-label col-sm-2']) !!}
                            <div class="col-sm-3 controls">
                                {!! Form::text('namabarang', null, ['id'=>'namabarang', 'class' => 'form-control',
                                'placeholder'=>'Nama Barang Toko', 'disabled']) !!}
                            </div>
                            {!! Form::label('kategori', 'Kategori :', ['class' => 'control-label col-sm-1']) !!}
                            <div class="col-sm-3">
                                {!! Form::text('kategori', null, ['class' => 'form-control', 'id'=>'kategori',
                                'disabled', 'placeholder' => 'Kategori Barang Toko']) !!}
                            </div>
                            {!! Form::label('diskonitem', 'Diskon Rp :', ['class' => 'control-label col-sm-1']) !!}
                            <div class="col-sm-2">
                                {!! Form::text('diskonitem', null, ['class' => 'form-control inputanangka',
                                'id'=>'diskonitem', 'placeholder' => 'Diskon Item Rp. (F8)', 'style' =>
                                'background-color:
                                #bfffb5;']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            {!! Form::label('harga_jual', 'Harga Jual', ['class' => 'control-label col-sm-4']) !!}

                            <div class="col-sm-3">
                                {!! Form::text('harga_jual', null, ['id'=>'harga_jual', 'class' => 'form-control
                                inputanangka', 'placeholder'=>'Harga Jual']) !!}
                            </div>

                            {!! Form::label('stok', 'Stok ', ['class' => 'control-label col-sm-1', 'style' =>
                            'text-align: left;']) !!}

                            <div class="col-sm-4">
                                {!! Form::text('stok', null, ['id'=>'stok', 'class' => 'form-control inputanangka',
                                'disabled','placeholder' => 'Stok Barang Toko']) !!}
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-2">
                                {!! Form::text('qty', null, ['class' => 'form-control inputanangka', 'id'=>'qty',
                                'placeholder' => 'Qty (F4)']) !!}
                            </div>

                            <div class="col-sm-4">
                                {!! Form::text('subtotal', null, ['id'=>'subtotal', 'class' => 'form-control',
                                'disabled', 'placeholder' => 'Sub Total Barang']) !!}
                            </div>
                            <div class="col-sm-3">
                                <a href="#" class="btn btn-primary btn-flat btn-block" id="simpantambah"><i
                                        class="fa fa-plus"></i> Tambah (SPACE)</a>
                            </div>
                            <div class="col-sm-3">
                                <a href="{{ url('penjualan') }}" class="btn btn-warning btn-flat btn-block"
                                    id="batal"><i class="fa fa-refresh"></i> Refresh (F5)</a>
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
                <br>
                <div class="row">
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="diskonview" class="control-label col-sm-4">Total Diskon Item : </label>
                            <div class="col-sm-5">
                                <input type="text" name="diskonview" id="diskonview" value=""
                                    class="form-control inputanangka" placeholder="Diskon Item Rp." readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-horizontal pull-right">
                        <div class="form-group">
                            <input type="hidden" id="totalbayar" name="totalbayar">
                            <label for="jumlahbayar" class="control-label col-sm-4">Jumlah Bayar : </label>
                            <div class="col-sm-8">
                                <input type="text" name="jumlahbayar" id="jumlahbayar" value=""
                                    class="form-control input-lg inputanangka"
                                    placeholder="Masukan Jumlah Bayar (F7)" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-horizontal">
                        <div class="form-group">
                            <label for="totalbarang" class="control-label col-sm-4">Total Barang : </label>
                            <div class="col-sm-5">
                                <input type="text" name="totalbarang" id="totalbarang" value=""
                                    class="form-control inputanangka" placeholder="Total Barang" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-horizontal pull-right">
                        <div class="form-group">
                            <label for="kembalian" class="control-label col-sm-4">Kembalian : </label>
                            <div class="col-sm-8">
                                <input type="text" name="kembalian" id="kembalian" value=""
                                    class="form-control input-lg inputanangka" placeholder="Kembalian Jumlah Bayar"
                                    readonly>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modalBantu"><i
                                    class="fa fa-exclamation"></i> Bantuan</button>
                            <a href="{{ url('listpenjualan') }}" class="btn btn-danger"><i class="fa fa-list-alt"></i>
                                List Penjualan</a>
                            <a href="#" class="btn btn-success btn-flat pull-right" id="simpan"><i
                                    class="fa fa-send"></i> Selesai dan Simpan (ENTER)</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@include('master.penjualan.modal')
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
            },{
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
                        '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="fa fa-edit fa-fw"></i> </button>';
                    kembali +=
                        '<button title="Hapus Item" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash fa-fw"></i> </button>';

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
                        'Rp.'
                         + number_format(total, 0, ',', '.') + ''
                    );
                    $('#totalbayar').val(total);
                    $('#diskonview').val(formatRibuan(diskon));
                    loadTotalBayar();
                    var rows = table.rows().count();
                    $('#totalbarang').val(rows + ' Barang');
                } else {
                    $(api.column(1).footer()).html(
                        //'Rp '+ numberfo pageTotal +' dari total Rp '+ total +''
                        'Rp 0'
                    );
                    loadTotalBayar();
                }
            },
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
            loadTotalBayar();
        });

        //--- Pencarian Barang ketik ajax--//
        var timer;

        $('#kode').on('keydown', function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 13) {
                clearTimeout(timer);
            }
        }).on('keyup', function (e) {
            timer = setTimeout(function () {
                //alert disini
                var keywords = $('#kode').val();

                if (keywords.length > 0) {

                    $.get('/findbarangtoko/kode/' + keywords, function (res) {
                        if (res.nama == null) {
                            $('#barangasli').val(null);
                            $('#harga_jual').val(null);
                            $('#stok').val(null);
                            $('#qty').val(null);
                            $('#subtotal').val(null);
                            $('#namabarang').val('Tidak ditemukan');
                            $('#kategori').val('Tidak ditemukan');
                        } else {
                            $('#barangasli').val(res.kode);
                            $('#harga_jual').val(number_format(res.harga_jual, 0, ',','.'));
                            $('#namabarang').val(res.nama);
                            $('#qty').val(1);
                            $('#stok').val(res.stok);
                            $('#kategori').val(res.kategori);
                            loadTotal();
                        }
                    });
                }
            }, 500);
        });

        //--Pencarian Barang ketik ajax--- //

    });


    
    // ----- Cari Barang ------- //
    $('#tabelcaritoko').DataTable({
        responsive: true,
        'ajax': {
            'url': '/barangpenjualan',
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

       //cari barang lewat modal//
       function PilihClickBarang(btn) {
        route = "/findbarangtoko/id/" + btn.value;

        $.get(route, function (res) {
            $('#barangasli').val(res.kode);
            $('#kode').val(res.kode);
            $('#namabarang').val(res.nama);
            $('#stok').val(number_format(res.stok, 0, ',', '.'));
            $('#harga_jual').val(number_format(res.harga_jual, 0, ',', '.'));
            $('#qty').val('1');
            $('#kategori').val(res.kategori)

            $('#modalCariBarang').modal('toggle');

            loadTotal();
            $('#kode').focus();


        });
    }

        //--merefresh data di table pencarian barang--//
        function CariClick(btn) {
        reloadTableCari();
    }

    function reloadTableCari() {
        var table = $('#tabelcaritoko').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }
    //-----------------------------------------//

    function loadTotalBayar() {
        var bayarnilai = $('#jumlahbayar').val();
        var bayartotal = $('#totalbayar').val();
        $('#kembalian').val(number_format($('#totalbayar').val(), 0, ',', '.'));

        if (bayartotal != undefined && jQuery.trim(bayartotal) != '' && intVal(bayartotal) > 0 && bayarnilai !=
            undefined && jQuery.trim(bayarnilai) != '' && intVal(bayarnilai) > 0) {
            var kembali = intVal(bayarnilai) - intVal(bayartotal);
            $('#kembalian').val(number_format(kembali, 0, ',', '.'));
        }
    }

    function reloadTable() {
        var table = $('#tabelpenjualan').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }

    function loadTotal() {
        var qty = $('#qty').val();
        var harga = $('#harga_jual').val();
        $('#subtotal').val(null);

        if (qty != undefined && jQuery.trim(qty) != '' && intVal(qty) > 0 && harga != undefined && jQuery.trim(harga) !=
            '' && intVal(harga) > 0) {
            var total = intVal(qty) * intVal(harga);
            $('#subtotal').val(number_format(total, 0, ',', '.'));
        }

        var qty1 = $('#qtyubah').val();
        var harga1 = $('#harga_jualubah').val();
        $('#totalubah').val(null);

        if (qty1 != undefined && jQuery.trim(qty1) != '' && intVal(qty1) > 0 && harga1 != undefined && jQuery.trim(
                harga1) != '' && intVal(harga1) > 0) {
            var total1 = intVal(qty1) * intVal(harga1);
            $('#totalubah').val(number_format(total1, 0, ',', '.'));
        }
        //-----------------------------------------//

        var diskon = $('#diskonubah').val();
        var total = $('#totalubah').val();

        if (diskon != undefined && jQuery.trim(diskon) != '' && intVal(diskon) > 0 && total != undefined && jQuery.trim(
                total) != '' && intVal(total) > 0) {
            var total2 = intVal(total) - intVal(diskon);
            $('#totalubah').val(number_format(total2, 0, ',', '.'));
        }
    }


    $('#simpantambah').click(function () {
        var invoice = $('#invoice').val();
        if (jQuery.trim(invoice) == '' || invoice == undefined) {
            kodeOtomatis();
            invoice = $('#invoice').val();
        }

        var route = "/sementarajual";
        var token = $('#token').val();

        var barang = $('#barangasli').val();
        if (jQuery.trim(barang) == '' || barang == undefined) {
            alert('Kode barang tidak boleh dikosongkan');
            $('#kode').focus();
            return;
        }

        var stok = $('#stok').val();
        if (jQuery.trim(stok) == '' || stok == ' ' || intVal(stok) <= 0) {
            return swal({
                type: 'error',
                title: 'Stok Barang Tidak Mencukupi!',
                showConfirmButton: true,
                timer: 2000
            }).catch(function (timeout) {
                $('#qty').focus();
            });
            return;
        }

        var diskon = $('#diskonitem').val();

        var harga = $('#harga_jual').val();
        if (jQuery.trim(harga) == '' || harga == ' ' || intVal(harga) <= 0) {
            alert('Harga jual penjualan tidak valid');
            $('#harga_jual').focus();
            return;
        }

        harga = intVal(harga);

        var qty = $('#qty').val();
        if (jQuery.trim(qty) == '' || qty == ' ' || intVal(qty) <= 0) {
            alert('QTY penjualan tidak valid');
            $('#qty').focus();
            return;
        }

        stok = intVal(stok);
        qty = intVal(qty);
        diskon = intVal(diskon);

        // console.log('stok : ' + stok + ', qty : ' + qty);

        if (stok < qty) {
            return swal({
                type: 'error',
                title: 'Stok Barang Tidak Mencukupi!',
                showConfirmButton: true,
                timer: 2000
            }).catch(function (timeout) {
                $('#qty').focus();
            });

            return;
        }

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
                kodebarang: barang,
                invoice: invoice,
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

                return swal({
                    type: 'error',
                    title: pesan,
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});
            },
            success: function () {
                reloadTable();

                $('#kode').val(null);
                $('#barangasli').val(null);
                $('#namabarang').val(null);
                $('#harga_jual').val(null);
                $('#stok').val(null);
                $('#total').val(null);
                $('#qty').val('1');
                $('#diskonitem').val(null);
                $('#kategori').val(null);
                $('#subtotal').val(null);

                $('#kode').focus();
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

    function UbahClick(btn) {
        route = "/sementarakoreksi/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#idubah').val(res.id);
            $('#barangubah').val(res.barang);
            $('#namaubah').val(res.nama);
            $('#harga_jualubah').val(number_format(intVal(res.harga), 0, ',', '.'));
            $('#stokubah').val(number_format(intVal(res.stok), 0, ',', '.'));
            $('#qtyubah').val(number_format(intVal(res.jumlah), 0, ',', '.'));
            $('#totalubah').val(number_format(intVal(res.total), 0, ',', '.'));
            $('#diskonubah').val(number_format(intVal(res.diskon), 0, ',', '.'));
            $('#qtyubah').focus();
        });

    }

    $('#simpanubah').click(function () {
        var id = $('#idubah').val();
        var token = $('#token').val();
        var route = "/sementara/" + id;

        var qty = $('#qtyubah').val();
        var stok = $('#stokubah').val();
        if (jQuery.trim(stok) == '' || stok == ' ' || intVal(stok) <= 0) {
            alert('Stok barang tidak valid');
            $('#qtyubah').focus();
            return;
        }

        if (jQuery.trim(qty) == '' || qty == ' ' || intVal(qty) <= 0) {
            alert('QTY penjualan tidak valid');
            $('#qtyubah').focus();
            return;
        }

        stok = intVal(stok);
        qty = intVal(qty);

        if (stok < qty) {
            return swal({
                type: 'error',
                title: 'Stok Barang Tidak Mencukupi!',
                showConfirmButton: true,
                timer: 2000
            }).catch(function (timeout) {
                $('#qtyubah').focus();
            });

            return;
        }

        var harga = $('#harga_jualubah').val();
        if (jQuery.trim(harga) == '' || harga == ' ' || intVal(harga) <= 0) {
            alert('Harga barang tidak valid');
            $('#harga_jualubah').focus();
            return;
        }

        harga = intVal(harga);

        var diskon = $('#diskonubah').val();
        diskon = intVal(diskon);

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
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

    $('#simpan').click(function () {
        var kembali = $('#kembalian').val();
        if (jQuery.trim(kembali) == '' || kembali == undefined || intVal(kembali) < 0) {
            alert('Jumlah Bayar Tidak cukup');
            $('#jumlahbayar').focus();

            return;
        }

        var bayar = $('#jumlahbayar').val();
        if (jQuery.trim(bayar) == '' || bayar == undefined || intVal(bayar) < 0) {
            alert('Inputkan nilai pembayaran dengan benar !');
            $('#jumlahbayar').focus();
            return;
        }
        bayar = intVal(bayar);

        var totalbayar = $('#totalbayar').val();
        totalbayar = intVal(totalbayar);

        var kembalian = $('#kembalian').val();
        kembalian = intVal(kembalian);


        var invoice = $('#invoice').val();
        if (jQuery.trim(invoice) == '' || invoice == undefined) {
            alert("Kode invoice tidak valid!");
            window.location.href = '/penjualan';
            return;
        }


        var table = $('#tabelpenjualan').DataTable();
        if (!table.data().count()) {
            alert('Tidak ada data detail penjualan di tabel !!');
            return;
        }

        var id = $('#idubahsekali').val();
        var token = $('#token').val();
        var route = "/penjualan/" + id;
        var tanggal = $('#tgl').val();

        $.ajax({
            url: route,
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
                bayar: bayar,
                totalbayar: totalbayar,
                kembalian: kembalian,
                kode: invoice,
                tgl: tanggal,
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
                $('#modalBayar').modal('toggle');
                alert('Sukses Melakukan Koreksi Transaksi Penjualan !!');
                window.location.href = '/penjualan';

            }
        });
    });

    </script>
@endsection