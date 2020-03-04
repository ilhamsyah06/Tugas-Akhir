@extends('layouts.master')

@section('title','Barang')

@php
$stokhabis = DB::table('barang')->where('stok', 0 )->where('status','toko')->count('nama_barang');

if($stokhabis != 0){
    echo "<script> alert('Ada $stokhabis Stok Barang Yang Sudah Habis');</script>";

}

@endphp

@section('content')
<div class="container">
@if (Auth::user()->level === 'kasir')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> List Barang Toko
                    <a class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalTambah"
                        style="margin-top: -8px;"><i class="fa fa-plus"></i> Tambah
                        Barang</a>
                </h4>
            </div>
            <div class="panel-body">
                <table id="barang" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Profit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> List Barang Gudang
                    <a class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalTambahg"
                        style="margin-top: -8px;"><i class="fa fa-plus"></i> Tambah
                        Barang</a>
                </h4>
            </div>
            <div class="panel-body">
                <table id="baranggudang" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Profit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@include('master.barang.modal')
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/select2/select2.full.min.js') }}"></script>

<script>
    $(document).ready(function () {

        $('#tbl-contact thead th').each(function () {
            var title = $(this).text();
            $(this).html(title+' <input type="text" class="col-search-input" placeholder="Search ' + title + '" />');
        });

        var t = $('#baranggudang').DataTable({
            responsive: true,
            scrollX: true,
            'ajax': {
                'url': '/api/baranggudang',
            },

            'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-sm-1"
            }, {
                'targets': 1,
                'sClass': "text-center col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;"  class="label label-danger' +
                        '">' + data + '</span>';
                }
            }, {
                'targets': 2,
                'sClass': "col-md-2"
            }, {
                'targets': 3,
                'sClass': "col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-primary' +
                        '">' + data + '</span>';
                }
            }, {
                'targets': 4,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            }, {
                'targets': 5,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            },{
                'targets': 6,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return '<span style="font-size: 12px;"  class="label label-success' +
                        '">'+'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-' + '</span>';

                }
            }, {
                'targets': 7,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-2 td-aksi",
                'render': function (data, type, full, meta) {
                    var button =
                        '<button title="Lihat Data" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modalLihat" onclick="LihatClick(this);"><i class="fa fa-eye"></i> </button>';
                    button +=
                        '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbahg" onclick="UbahClickg(this);"><i class="fa fa-pencil"></i> </button>';
                    button +=
                        '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash"></i> </button>';

                    return button;

                }
            }],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-info btn-flat"]').prop('value', data[7]);
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[7]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[7]);

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

        var routelevel = "/levelapi";
        var inputTipeubah = $('#levelubahgudang');
        var inputTipegudang = $('#levelgudang')

        var listgudang = document.getElementById("levelgudang");
        while (listgudang.hasChildNodes()) {
            listgudang.removeChild(listgudang.firstChild);
        }
        inputTipegudang.append('<option value=" ">Pilih Jenis Barang</option>');

        var listubah = document.getElementById("levelubahgudang");
        while (listubah.hasChildNodes()) {
            listubah.removeChild(listubah.firstChild);
        }
        inputTipeubah.append('<option value=" ">Pilih Jenis Barang</option>');

        $.get(routelevel, function (res) {
            // console.log(res);
            $.each(res.data, function (index, value) {
                inputTipeubah.append('<option value="' + value[1] + '">' + value[0] + '</option>');
                inputTipegudang.append('<option value="' + value[1] + '">' + value[0] + '</option>');
            });
        });

        $("#levelubahgudang").select2();
        $('#levelgudang').select2();

    });

    $(document).ready(function () {
        $("#hargabeliubahgudang,#hargajualubahgudang").keyup(function () {
            var blug = intVal($("#hargabeliubahgudang").val());
            var jlug = intVal($("#hargajualubahgudang").val());
            var totalug = jlug - blug;
            $("#profitubahgudang").val(formatRibuan(totalug));
        });
    });

    $(document).ready(function () {
        $("#harga_beligudang,#harga_jualgudang").keyup(function () {
            var blug = intVal($("#harga_beligudang").val());
            var jlug = intVal($("#harga_jualgudang").val());
            var totalug = jlug - blug;
            $("#profitgudang").val(formatRibuan(totalug));
        });
    });

    function reloadTable() {
        var table = $('#barang').dataTable();
        var table1 = $('#baranggudang').dataTable();
        table.cleanData;
        table1.cleanData;
        table.api().ajax.reload();
        table1.api().ajax.reload();
    }

    function UbahClickg(btn) {
        route = "/barang/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#idubahg').val(res.id);
            $('#kodeubahgudang').val(res.kode);
            $('#namaubahgudang').val(res.nama);
            $('#levelubahgudang').val('' + res.jenisbarang).trigger('change');
            $('#hargabeliubahgudang').val(number_format(intVal(res.hargabeli), 0, ',', '.'));
            $('#hargajualubahgudang').val(number_format(intVal(res.hargajual), 0, ',', '.'));
            $('#profitubahgudang').val(number_format(intVal(res.profit), 0, ',', '.'));
            $('#tanggalubahgudang').val(res.tanggal);

            $('#kodeubahgudang').focus();

        });

    }

    $('#simpanubahg').click(function () {
        var id = $('#idubahg').val();
        var token = $('#token').val();
        var route = "/baranggudang/" + id;

        var kode = $('#kodeubahgudang').val();
        if (jQuery.trim(kode) == '' || kode == undefined) {
            alert('kode tidak boleh kosong !!');
            $('#kodeubahgudang').focus();
            return;
        }

        var hargabeli = $('#hargabeliubahgudang').val();
        if (jQuery.trim(hargabeli) == '' || hargabeli == ' ' || intVal(hargabeli) < 0) {
            alert('Harga beli tidak boleh kosong.');
            $('#hargabeliubahgudang').focus();
            return;
        }
        hargabeli = intVal(hargabeli);

        var nama = $('#namaubahgudang').val();
        if (jQuery.trim(nama) == '' || nama == undefined) {
            alert('Nama barang tidak boleh kosong !!');
            $('#namaubahgudang').focus();
            return;
        }

        var hargajual = $('#hargajualubahgudang').val();
        if (jQuery.trim(hargajual) == '' || hargajual == ' ' || intVal(hargajual) < 0) {
            alert('Harga jual tidak boleh kosong.');
            $('#hargajualubahgudang').focus();
            return;
        }
        hargajual = intVal(hargajual);

        var jenis = $('#levelubahgudang').val();
        if (jQuery.trim(jenis) == 0 || jenis == undefined) {
            alert('Harap Isikan Jenis Barang !!');
            $('#levelubahgudang').focus();
            return false;
        }

        var profit = intVal($('#profitubahgudang').val());

        var stok = $('#stokubahgudang').val();
        stok = intVal(stok);

        var tanggal = $('#tanggalubahgudang').val();

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'PUT',
            dataType: 'json',
            data: {
                kode: kode,
                hargabeli: hargabeli,
                nama: nama,
                hargajual: hargajual,
                jenis: jenis,
                profit: profit,
                stok: stok,
                tanggal: tanggal,
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
                $('#stokubahgudang').val(null);
                $('#modalUbahg').modal('toggle');
                return swal({
                    type: 'success',
                    title: 'Data berhasil diubah.',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});

            }
        });
    });

    $('#simpantambahg').click(function () {
        var route = "/baranggudang";
        var token = $('#token').val();

        var kode = $('#kodegudang').val();
        if (jQuery.trim(kode) == '' || kode == undefined) {
            alert('kode tidak boleh kosong !!');
            $('#kodegudang').focus();
            return;
        }

        var nama = $('#namagudang').val();
        if (jQuery.trim(nama) == '' || nama == undefined) {
            alert('Nama tidak boleh kosong !!');
            $('#namagudang').focus();
            return;
        }

        var jenis = $('#levelgudang').val();
        if (jQuery.trim(jenis) == 0 || jenis == undefined) {
            alert('Harap Isikan Jenis Barang !!');
            $('#levelgudang').focus();
            return false;
        }

        var hargabeli = $('#harga_beligudang').val();
        if (jQuery.trim(hargabeli) == '' || hargabeli == ' ' || intVal(hargabeli) < 0) {
            alert('Harga beli tidak boleh kosong.');
            $('#harga_beligudang').focus();
            return;
        }
        hargabeli = intVal(hargabeli);

        var hargajual = $('#harga_jualgudang').val();
        if (jQuery.trim(hargajual) == '' || hargajual == ' ' || intVal(hargajual) < 0) {
            alert('Harga jual tidak boleh kosong.');
            $('#harga_jualgudang').focus();
            return;
        }
        hargajual = intVal(hargajual);

        var profit = intVal($('#profitgudang').val());
        var tanggal = $('#tanggalgudang').val();

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
                kode: kode,
                nama: nama,
                jenis: jenis,
                hargabeli: hargabeli,
                hargajual: hargajual,
                profit: profit,
                tanggal: tanggal,
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
                $('#kodegudang').val('');
                $('#namagudang').val('');
                $('#levelgudang').val(0);
                $('#stokgudang').val('');
                $('#harga_beligudang').val('');
                $('#harga_jualgudang').val('');
                $('#profitgudang').val('');
                return swal({
                    type: 'success',
                    title: 'Data berhasil disimpan.',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});
            }
        });
    });
</script>

<script>
    $(document).ready(function () {

        var t = $('#barang').DataTable({
            responsive: true,
            scrollX: true,
            'ajax': {
                'url': '/api/barang',
            },

            'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-sm-1"
            }, {
                'targets': 1,
                'sClass': "text-center col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;"  class="label label-danger' +
                        '">' + data + '</span>';
                }
            }, {
                'targets': 2,
                'sClass': "col-md-2"
            }, {
                'targets': 3,
                'sClass': "col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-primary' +
                        '">' + data + '</span>';
                }
            }, {
                'targets': 4,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            }, {
                'targets': 5,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            },{
                'targets': 6,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return '<span style="font-size: 12px;"  class="label label-success' +
                        '">'+'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-' + '</span>';

                }
            }, {
                'targets': 7,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-2 td-aksi",
                'render': function (data, type, full, meta) {
                    var button =
                        '<button title="Lihat Data" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modalLihat" onclick="LihatClick(this);"><i class="fa fa-eye"></i> </button>';
                    button +=
                        '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="fa fa-pencil"></i> </button>';
                    button +=
                        '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash"></i> </button>';

                    return button;

                }
            }],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-info btn-flat"]').prop('value', data[7]);
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[7]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[7]);

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

        var route3 = "/levelapi";
        var inputTipe = $('#level');
        var inputTipe2 = $('#levelubahtoko');

        var list = document.getElementById("level");
        while (list.hasChildNodes()) {
            list.removeChild(list.firstChild);
        }
        inputTipe.append('<option value=" ">Pilih Jenis Barang</option>');

        var list2 = document.getElementById("levelubahtoko");
        while (list2.hasChildNodes()) {
            list2.removeChild(list2.firstChild);
        }
        inputTipe2.append('<option value=" ">Pilih Jenis Barang</option>');

        $.get(route3, function (res) {
            // console.log(res);
            $.each(res.data, function (index, value) {
                inputTipe.append('<option value="' + value[1] + '">' + value[0] + '</option>');
                inputTipe2.append('<option value="' + value[1] + '">' + value[0] + '</option>');
            });
        });

        $("#level").select2();
        $("#levelubahtoko").select2();


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

        });

    });


    $(document).ready(function () {
        $("#harga_beli,#harga_jual").keyup(function () {
            var bl = intVal($("#harga_beli").val());
            var jl = intVal($("#harga_jual").val());
            var total = jl - bl;
            $("#profit").val(formatRibuan(total));
        });
    });


    $(document).ready(function () {
        $("#hargabeliubahtoko,#hargajualubahtoko").keyup(function () {
            var blu = intVal($("#hargabeliubahtoko").val());
            var jlu = intVal($("#hargajualubahtoko").val());
            var totalu = jlu - blu;
            $("#profitubahtoko").val(formatRibuan(totalu));
        });
    });


    function LihatClick(btn) {
        route = "/barang/" + btn.value;

        $.get(route, function (res) {
            $('#hargabelilihatg').val(number_format(intVal(res.hargabeli), 0, ',', '.'));
            $('#hargajuallihatg').val(number_format(intVal(res.hargajual), 0, ',', '.'));
            $('#profitlihatg').val(number_format(intVal(res.profit), 0, ',', '.'));
            $('#kodelihatg').val(res.kode);
            $('#namalihatg').val(res.nama);
            $('#levellihatg').val(res.kategori);
            $('#tanggallihat').val(res.tanggal);
            $('#stoklihat').val(number_format(intVal(res.stok), 0, ',', '.'));
            $('#statuslihat').val(res.status);
            $('#userinputlihat').val(res.user);

        });

    }

    $('#autokode').click(function () {
        route = "/barangautokode";

        $.get(route, function (res) {
            $('#kode').val(res);
        });
    });

    $('#autokodegudang').click(function () {
        route = "/barangautokode";

        $.get(route, function (res) {
            $('#kodegudang').val(res);
        });
    });

    $('#simpantambah').click(function () {
        var route = "/barang/barangtoko";
        var token = $('#token').val();

        var kode = $('#kode').val();
        if (jQuery.trim(kode) == '' || kode == undefined) {
            alert('kode tidak boleh kosong !!');
            $('#kode').focus();
            return;
        }

        var nama = $('#nama').val();
        if (jQuery.trim(nama) == '' || nama == undefined) {
            alert('Nama tidak boleh kosong !!');
            $('#nama').focus();
            return;
        }

        var jenis = $('#level').val();
        if (jQuery.trim(jenis) == 0 || jenis == undefined) {
            alert('Harap Isikan Jenis Barang !!');
            $('#level').focus();
            return false;
        }

        var stok = $('#stok').val();
        if (jQuery.trim(stok) == '' || stok == ' ' || intVal(stok) < 0) {
            alert('Stok tidak boleh kosong.');
            $('#stok').focus();
            return;
        }
        stok = intVal(stok);

        var hargabeli = $('#harga_beli').val();
        if (jQuery.trim(hargabeli) == '' || hargabeli == ' ' || intVal(hargabeli) < 0) {
            alert('Harga beli tidak boleh kosong.');
            $('#harga_beli').focus();
            return;
        }
        hargabeli = intVal(hargabeli);

        var hargajual = $('#harga_jual').val();
        if (jQuery.trim(hargajual) == '' || hargajual == ' ' || intVal(hargajual) < 0) {
            alert('Harga jual tidak boleh kosong.');
            $('#harga_jual').focus();
            return;
        }
        hargajual = intVal(hargajual);

        var profit = intVal($('#profit').val());
        var tanggal = $('#tanggal').val();

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
                kode: kode,
                nama: nama,
                jenis: jenis,
                stok: stok,
                hargabeli: hargabeli,
                hargajual: hargajual,
                profit: profit,
                tanggal: tanggal,
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
                $('#kode').val('');
                $('#nama').val('');
                $('#level').selectedIndex = "0";
                $('#stok').val('');
                $('#harga_beli').val('');
                $('#harga_jual').val('');
                $('#profit').val('');
                return swal({
                    type: 'success',
                    title: 'Data berhasil disimpan.',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});
            }
        });
    });

    function UbahClick(btn) {
        route = "/barang/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#idubah').val(res.id);
            $('#kodeubahtoko').val(res.kode);
            $('#barcodeubah').val(res.barcode);
            $('#namaubahtoko').val(res.nama);
            $('#levelubahtoko').val('' + res.jenisbarang).trigger('change');
            $('#hargabeliubahtoko').val(number_format(intVal(res.hargabeli), 0, ',', '.'));
            $('#hargajualubahtoko').val(number_format(intVal(res.hargajual), 0, ',', '.'));
            $('#profitubahtoko').val(number_format(intVal(res.profit), 0, ',', '.'));
            $('#stokubahtoko').val(number_format(intVal(res.stok), 0, ',', '.'));
            $('#tanggalubahtoko').val(res.tanggal);

            $('#barcodeubah').focus();

        });

    }

    $('#simpanubah').click(function () {
        var id = $('#idubah').val();
        var token = $('#token').val();
        var route = "/barang/" + id;

        var kode = $('#kodeubahtoko').val();
        if (jQuery.trim(kode) == '' || kode == undefined) {
            alert('kode tidak boleh kosong !!');
            $('#kode').focus();
            return;
        }

        var hargabeli = $('#hargabeliubahtoko').val();
        if (jQuery.trim(hargabeli) == '' || hargabeli == ' ' || intVal(hargabeli) < 0) {
            alert('Harga beli tidak boleh kosong.');
            $('#hargabeliubahtoko').focus();
            return;
        }
        hargabeli = intVal(hargabeli);

        var nama = $('#namaubahtoko').val();
        if (jQuery.trim(nama) == '' || nama == undefined) {
            alert('Nama barang tidak boleh kosong !!');
            $('#namaubahtoko').focus();
            return false;
        }

        var hargajual = $('#hargajualubahtoko').val();
        if (jQuery.trim(hargajual) == '' || hargajual == ' ' || intVal(hargajual) < 0) {
            alert('Harga jual tidak boleh kosong.');
            $('#hargajualubahtoko').focus();
            return;
        }
        hargajual = intVal(hargajual);

        var jenis = $('#levelubahtoko').val();
        if (jQuery.trim(jenis) == 0 || jenis == undefined) {
            alert('Harap Isikan Jenis Barang !!');
            $('#levelubahtoko').focus();
            return false;
        }

        var profit = intVal($('#profitubahtoko').val());

        var stok = $('#stokubahtoko').val();
        if (jQuery.trim(stok) == '' || stok == ' ' || intVal(stok) < 0) {
            alert('Stok tidak boleh kosong.');
            $('#stokubahtoko').focus();
            return;
        }
        stok = intVal(stok);

        var tanggal = $('#tanggalubahtoko').val();

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'PUT',
            dataType: 'json',
            data: {
                kode: kode,
                hargabeli: hargabeli,
                nama: nama,
                hargajual: hargajual,
                jenis: jenis,
                profit: profit,
                stok: stok,
                tanggal: tanggal,
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
                $('#modalUbah').modal('toggle');
                return swal({
                    type: 'success',
                    title: 'Data berhasil diubah.',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});

            }
        });
    });



    function HapusClick(btn) {
        $('#idHapus').val(btn.value);
    }

    $('#yakinhapus').click(function () {
        var token = $('#token').val();
        var id = $('#idHapus').val();
        var route = "/barang/" + id;

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
                return swal({
                    type: 'error',
                    title: pesan,
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});
            },
            success: function () {
                reloadTable();
                $('#modalHapus').modal('toggle');
                return swal({
                    type: 'success',
                    title: 'Data berhasil dihapus.',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});
            }
        });
    });
</script>
@endsection