@extends('layouts.master')

@section('title','Barang')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> Daftar Barang
                    @if (Auth::user()->level == 'kasir')
                    <a class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalTambahtoko"
                    style="margin-top: -8px; font-weight:bold;"><i class="fa fa-plus"></i> Tambah
                    Barang Toko</a>
                    @else
                    <a class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalTambah"
                    style="margin-top: -8px; font-weight:bold;"><i class="fa fa-plus"></i> Tambah
                    Barang</a>
                    @endif
                        <a class="btn btn-warning pull-right" data-toggle="modal" data-target="#modalLihatStok"
                        style="margin-top: -8px; margin-right: 10px; font-weight: bold;"><i class="fa fa-eye"></i> Barang Stok Habis</a>
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
                            <th>Stok Toko</th>
                            <th>Stok Gudang</th>
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

@php
$stokhabis = DB::table('barang')->where('stok_gudang', 0 )->count();

if($stokhabis != 0){
    echo "<script>swal({
                    type: 'error',
                    title: 'Ada $stokhabis Stok Barang Yang Sedang Habis.',
                    showConfirmButton: false,
                    timer: 2000
                }).catch(function (timeout) {});
        </script>";
}

@endphp
@include('master.barang.modal')
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/select2/select2.full.min.js') }}"></script>

<script>
       function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        printButton.style.visibility = 'visible';
    }
    $(document).ready(function () {

        $('#barangstokhabis').DataTable({
            "pagingType": "full_numbers",
            responsive: true,
            scrollX: true,
            'ajax': {
                'url': '/barangtokohabis', //menampikan barang toko yang sedang habis stok nya
            },

            'columnDefs': [{
                'targets': 0,
                'sClass': "text-center col-md-2",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;"  class="label label-danger">' + data + '</span>';
                }
            }, {
                'targets': 1,
                'sClass': "col-md-2",
                render: function(data, type, row, meta){
                    return data.substr(0, 25);
                }
            }, {
                'targets': 2,
                'sClass': "text-center col-md-1",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-primary">' + data + '</span>';
                }
            },{
                'targets': 3,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            }, {
                'targets': 4,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            },{
                'targets': 5,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return '<span style="font-size: 12px;"  class="label label-success' +
                        '">'+'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-' + '</span>';

                }
            }],

        });

        var t = $('#barang').DataTable({
            "pagingType": "full_numbers",
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
                    return '<span style="font-size: 12px;"  class="label label-danger">' + data + '</span>';
                }
            }, {
                'targets': 2,
                'sClass': "col-md-2",
                render: function(data, type, row, meta){
                    return data.substr(0, 25);
                }
            }, {
                'targets': 3,
                'sClass': "text-center col-md-1",
                render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-primary">' + data + '</span>';
                }
            }, {
                'targets': 4,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            },{
                'targets': 5,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
            }, {
                'targets': 6,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            },{
                'targets': 7,
                'sClass': "text-right col-md-1",
                'render': function (data, type, full, meta) {
                    return '<span style="font-size: 12px;"  class="label label-success' +
                        '">'+'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-' + '</span>';

                }
            }, {
                'targets': 8,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-2 td-aksi",
                'render': function (data, type, full, meta) {
                    var button =
                        '<button title="Lihat Data" class="btn btn-info btn-flat btn-sm" data-toggle="modal" data-target="#modalLihat" onclick="LihatClick(this);"><i class="fa fa-eye"></i> </button>';
                    button +=
                        '<button title="Ubah Data" class="btn btn-warning btn-flat btn-sm" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="fa fa-pencil"></i> </button>';
                    button +=
                        '<button title="Hapus Data" class="btn btn-danger btn-flat btn-sm" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash"></i> </button>';
                        button +=
                        '<button title="Kirim Data" class="btn btn-success btn-flat btn-sm" data-toggle="modal" data-target="#modalKirim" onclick="KirimClick(this);"><i class="fa fa-send"></i> </button>';

                    return button;

                }
            }],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-info btn-flat btn-sm"]').prop('value', data[8]);
                $(row).find('button[class="btn btn-warning btn-flat btn-sm"]').prop('value', data[8]);
                $(row).find('button[class="btn btn-danger btn-flat btn-sm"]').prop('value', data[8]);
                $(row).find('button[class="btn btn-success btn-flat btn-sm"]').prop('value', data[8]);

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
                case 8: case 9:
                case 27: case 13:
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

    function reloadTable() {
        var table = $('#barang').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }



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
            $('#hargabelilihat').val(number_format(intVal(res.hargabeli), 0, ',', '.'));
            $('#hargajuallihat').val(number_format(intVal(res.hargajual), 0, ',', '.'));
            $('#profitlihat').val(number_format(intVal(res.profit), 0, ',', '.'));
            $('#kodelihat').val(res.kode);
            $('#namalihat').val(res.nama);
            $('#levellihat').val(res.kategori);
            $('#tanggallihat').val(res.tanggal);
            $('#stoktoko').val(number_format(intVal(res.stoktoko), 0, ',', '.'));
            $('#stokgudang').val(number_format(intVal(res.stokgudang), 0, ',', '.'));
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

    $('#simpantambah').click(function () {
        var route = "/barang";
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

        if (hargabeli > hargajual){
            return swal({
                    type: 'error',
                    title: 'Harga Beli Tidak Boleh Melebihi Harga Jual.',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});
        }

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
                $('#level').val(' ').trigger('change');
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
            $('#hargaterakhir').val(number_format(intVal(res.hargajual), 0, ',', '.'));
            $('#hargaterakhirtampil').val(number_format(intVal(res.hargaterakhir), 0, ',', '.'));
            $('#profitubahtoko').val(number_format(intVal(res.profit), 0, ',', '.'));
            $('#stokubahtoko').val(number_format(intVal(res.stoktoko), 0, ',', '.'));
            $('#stokubahgudang').val(number_format(intVal(res.stokgudang), 0, ',', '.'));
            $('#tanggalubahtoko').val(res.tanggal);

            $('#barcodeubah').focus();

        });

    }

    function KirimClick(btn) {
        route = "/barang/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#idkirim').val(res.id);
            $('#stokgudangkirim').val(number_format(intVal(res.stokgudang), 0, ',', '.'));
            $('#kodebarang').val(res.kode);
            $('#harga').val(res.hargajual)
            $('#kirimstok').val(null);
            $('#kirimstok').focus();

        });

    }

    $('#simpankirim').click(function () {
        var route = "/kirimstoktoko";
        var id = $('#idkirim').val();
        var token = $('#token').val();
        var stokgudang = $('#stokgudangkirim').val();
        var qty = $('#kirimstok').val();

        stokgudang = intVal(stokgudang);
        qty = intVal(qty);

        if (stokgudang < qty) {
            return swal({
                type: 'error',
                title: 'Stok Barang Tidak Mencukupi Untuk Dikirim!',
                showConfirmButton: false,
                timer: 2000
            }).catch(function (timeout) {
                $('#kirimstok').focus();
            });

            return;
        }

        var stokkirim = $('#kirimstok').val();
        if (jQuery.trim(stokkirim) == '' || stokkirim == ' ' || intVal(stokkirim) < 0) {
            return swal({
                type: 'error',
                title: 'Jumlah Kirim Stok Toko tidak boleh kosong.',
                showConfirmButton: false,
                timer: 2000
            }).catch(function (timeout) {
                $('#kirimstok').focus();
            });
            return;
        }
        stokkirim = intVal(stokkirim);

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
                idbarang: id,
                stokkirim: qty,
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
                $('#modalKirim').modal('toggle');
                var qty = $('#kirimstok').val();
                var kode = $('#kodebarang').val();
                var harga = $('#harga').val();
                $('#kodeprint').val(kode);
                $('#qtyprint').val(qty);
                $('#hargaprint').val(harga);
                return swal({
                    type: 'success',
                    title: 'Data Stok Berhasil Dikirim.',
                    showConfirmButton: false,
                    timer: 1000
                }).catch(function (timeout) {
                    $('#modalprintbarcode').modal('toggle');
                });
                
            }
        });
    });

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

        if (hargabeli > hargajual){
            return swal({
                    type: 'error',
                    title: 'Harga Beli Tidak Boleh Melebihi Harga Jual.',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {});
        }

        var profit = intVal($('#profitubahtoko').val());

        var stoktoko = $('#stokubahtoko').val();
        stoktoko = intVal(stoktoko);

        var hargaterakhir = $('#hargaterakhir').val();
        hargaterakhir = intVal(hargaterakhir);

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
                hargaterakhir: hargaterakhir,
                jenis: jenis,
                profit: profit,
                stoktoko: stoktoko,
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