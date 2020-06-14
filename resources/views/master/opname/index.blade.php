@extends('layouts.master')

@section('title')
    Stok Opname Barang
@endsection

@section('content')


    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading with-border">
                        <h3 class="panel-title"><i class="fa fa-cubes"></i> Stok Opname Barang</h3>
                    </div>

                   <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-6 form-horizontal form-left">
                        <div class="form-group">
                          <label for="kode" class="control-label col-sm-4">No. Bukti : </label>
                          <div class="col-sm-8">
                            <input type="text" name="kode" id="kode" value="" class="form-control" disabled />
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 form-horizontal form-left">
                        <div class="form-group">
                          <label for="tgl" class="control-label col-sm-4">Tanggal : </label>
                          <div class="col-sm-8">
                            <input type="text" name="tgl" id="tgl" value="" class="form-control inputantgl" placeholder="MM/DD/YYYY" />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12 form-horizontal form-left">
                        <input type="hidden" name="barangasli" value="" id="barangasli" class="form-control" />
                        <div class="form-group">
                          <label for="barang" class="control-label col-sm-2">Kode Barang : </label>
                          <div class="col-sm-4 controls">
                            <div class="input-group">
                              <input type="text" name="barang" id="barang" value="" class="form-control" placeholder="Ketikkan kode barang / scan barcode" />
                              <span class="input-group-btn">
                                <a class="btn btn-default" type="button" data-toggle="modal" data-target="#modalCari" id="caribarang">
                                  <i class="fa fa-search"></i>
                                </a>
                              </span>
                            </div>
                          </div>
                          <label for="nama" class="control-label col-sm-2">Nama Barang : </label>
                          <div class="col-sm-4">
                            <input type="text" name="nama" id="nama" value="" class="form-control" placeholder="Nama Barang" disabled />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-6 form-horizontal form-left">
                        <div class="form-group">
                          <label for"stok_komputer" class="control-label col-sm-4">Stok Komputer : </label>
                          <div class="col-sm-8">
                            <input type="text" name="stok_komputer" id="stok_komputer" class="form-control inputanangka" value="" disabled />
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 form-horizontal form-left">
                        <div class="form-group">
                          <label for"stok_nyata" class="control-label col-sm-4">Stok Nyata : </label>
                          <div class="col-sm-8">
                            <input type="text" name="stok_nyata" id="stok_nyata" class="form-control inputanangka" value="" placeholder="Stok nyata setelah di cek" />
                          </div>
                        </div>          
                      </div> 
                    </div>


                    <div class="row">
                      <div class="col-sm-6 form-horizontal form-left">
                        <div class="form-group">
                          <label for"selisih" class="control-label col-sm-4">Selisih : </label>
                          <div class="col-sm-8">
                            <input type="text" name="selisih" id="selisih" class="form-control inputanangka" value="" disabled />
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6 form-horizontal form-left">
                        <div class="form-group">
                          <label for"keterangan" class="control-label col-sm-4">Keterangan : </label>
                          <div class="col-sm-8">
                            <input type="text" name="keterangan" id="keterangan" class="form-control" value="" placeholder="Keterangan stok opname" />
                          </div>
                        </div>          
                      </div> 
                    </div>
                    <!-- /.panel-body -->

                </div>
                <!-- /.panel -->

                      <div class="panel-footer">
                            <a title="Simpan Data Stok Opname" class="btn btn-primary btn-flat" href="#" id="simpan"><i class="fa fa-plus"></i>  Simpan</a>
                        </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <div class="col-xs-12">
             <div class="panel panel-default">
                 <div class="panel panel-heading">
                     <h3 class="panel-title"><i class="fa fa-list"></i> Data Pengiriman Barang</h3>
                 </div>

                 <div class="panel-body">
                     <div class="row">
                         <div class="col-sm-8 form-horizontal form-left">
                             <div class="form-group">
                                 {!! Form::label('start', 'Periode :', ['class' => 'control-label col-sm-2']) !!}
                                 <div class="col-sm-10 controls">
                                     <div class="input-daterange input-group">
                                         {!! Form::text('start', null, ['class'=>'form-control', 'id' => 'start', 'placeholder'=> 'DD/MM/YYYY']) !!}
                                         <span class="input-group-addon">s/d</span>
                                         {!! Form::text('end', null, ['class'=>'form-control', 'id'=>'end', 'placeholder'=> 'DD/MM/YYYY']) !!}
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <div class="col-sm-4 form-horizontal">
                             {!! Form::open(['url' => '/cetakpengiriman', 'method'=>'POST', 'id'=>'formcetak', 'class'=>'pull-left', 'style'=>"margin-right:5px;"]) !!}
                                         <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-print"></i>
                                             Cetak Data Penggiriman
                                         </button>
                                         {!! Form::close() !!}
                         </div>
                         
                     </div>
                     <table width="100%" class="table table-striped table-bordered table-hover" id="dataTableBuilderkirim">
                         <thead>
                         <tr>
                             <th class="col-md-1">Kode</th>
                             <th class="col-md-2">Nama Barang</th>
                             <th class="col-md-1">Qty</th>
                             <th class="col-md-1">Tanggal Pengiriman</th>
                             <th class="col-md-1">Aksi</th>
                         </tr>
                         </thead>
                         <tbody>

                         </tbody>
                     </table>

                 </div>
             </div>
             <!-- /.panel -->
         </div>
     </div>
     <!-- /.row -->
    </section>
    @include('master.opname.modal')

@endsection

@section('footer')
    <script>
        $(document).ready(function() {
    $('#dataTableBuilderCari').DataTable({
        responsive: true,
        'ajax': {
            'url': '/barangpenjualan',
        },
        'columnDefs': [
        {
            'targets':0,
            'sClass': "text-center col-lg-2",
            render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-danger">' + data + '</span>';
                }
        }, {
            'targets':1,
            'sClass': "text-center col-lg-2"
        }, {
            'targets':2,
            'sClass': "col-lg-3",
            render: function (data, type, row, meta) {
                    return '<span style="font-size: 12px;" class="label label-primary">' + data + '</span>';
                }
        },{
            'targets':3,
            'sClass': "col-lg-2",
            'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
        },{
            'targets':4,
            'sClass': "text-center col-lg-1",
            'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
        },{
            'targets': 5,
            'searchable': false,
            "orderable": false,
            "orderData": false,
            "orderDataType": false,
            "orderSequence": false,
            "sClass": "text-center col-lg-2 td-aksi",
            'render': function (data, type, full, meta) {
                var kembali = '';

                kembali += '<button title="Pilih Data" class="btn btn-success btn-flat btn-sm" onclick="PilihClick(this);"><i class="fa fa-hand-pointer-o fa-fw"></i> </button>';


                return kembali;

            }
        }],
        'rowCallback': function (row, data, dataIndex) {
            $(row).find('button[class="btn btn-success btn-flat btn-sm"]').prop('value', data[5]);
        }
    });

    document.getElementById("stok_nyata").maxLength = 15;
    document.getElementById("keterangan").maxLength = 150;

    $('.inputanangka').on('keypress', function(e) {
        var c = e.keyCode || e.charCode;
        switch (c) {
            case 8: case 9: case 27: case 13: return;
            case 65:
                if (e.ctrlKey === true) return;
        }
        if (c < 48 || c > 57) e.preventDefault();
    }).on('keyup', function() {
        //alert('disini');
        var inp = $(this).val().replace(/\./g, '');
        $(this).val(formatRibuan(inp));

        loadSelisih();
    });

    $('#barangasli').val(null);

    var timer;

    $('#barang').on('keydown', function(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 13) {
            clearTimeout(timer);
        }
    }).on('keyup', function(e) {
        timer = setTimeout(function () {
            //alert('disini');
            var keywords = $('#barang').val();

            if (keywords.length > 0) {

                $.get('/findbarangtoko/kode/' + keywords, function(res) {
                    if (res.nama == null) {
                        $('#barangasli').val(null);
                        $('#nama').val('Barang tidak ditemukan');
                        $('#stok_komputer').val(null);
                    } else {
                        $('#barangasli').val(res.kode);
                        $('#nama').val(res.nama);
                        $('#stok_komputer').val(number_format(res.stok, 0, ',', '.'));
                    }

                    loadSelisih();
                });
            } else {
                 $('#barangasli').val(null);
                 $('#nama').val(null);
                 $('#stok_komputer').val(null);
            }
        }, 500);
    });


    $('#caribarang').click(function() {
        reloadTableCari();
    });

    kodeOtomatis();

    var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";

    $('.inputantgl').datepicker({
        format: "mm/dd/yyyy",
        container: container,
        todayHighlight: true,
        autoclose: true,
    });

    $(".inputantgl").datepicker("setDate", new Date());

    var table = $('#dataTableBuilderkirim').DataTable({
        responsive: true,
        'ajax': {
            'url': '/datalaporanpengiriman',
            'data': function (d) {
                d.start = $('#start').val();
                d.end = $('#end').val();
            }
        },

        'columnDefs': [
            {
                'targets':0,
                'sClass': "text-center col-md-1"
            },{
                'targets':1,
                'sClass': "text-center col-md-2"
            },{
                'targets':2,
                'sClass': "text-center col-md-1"
            },{
                'targets':3,
                'sClass': "text-center col-md-1"
            },{
                'targets':4,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-lg-1 td-aksi",
                'render': function (data, type, full, meta) {
                    var kembali = '';
                    kembali += '<button title="Simpan Data Stok" class="btn btn-success btn-flat btn-sm" data-toggle="modal" data-target="#modalyakin" onclick="simpanClick(this);"><i class="fa fa-check"></i> </button>';
                    return kembali;
                }
            }
        ],
        'rowCallback': function (row, data, dataIndex) {
            $(row).find('button[class="btn btn-success btn-flat btn-sm"]').prop('value', data[4]);
        }
    });
    $('#start').on('change', function (e) {
        reloadTablepengiriman();
    });
    $('#end').on('change', function (e) {
        reloadTablepengiriman();
    });

    //mengatur tgl

var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";

$('.input-daterange').datepicker({
    format: "dd/mm/yyyy",
    container: container,
    todayHighlight: true,
    autoclose: true,
});

var fd = Date.today().clearTime().moveToFirstDayOfMonth();
// var firstday = fd.toString("MM/dd/yyyy");
var ld = Date.today().clearTime().moveToLastDayOfMonth();
// var lastday = ld.toString("MM/dd/yyyy");

$("#start").datepicker("setDate", fd);
$("#end").datepicker("setDate", ld);

//mengatur tgl

    $('#formcetak').on('submit', function (e) {
        if ( ! table.data().count() ) {
            alert( 'Tidak ada data yang akan dicetak !!' );
            return false;
        }

        var start = $('#start').val();
        var end = $('#end').val();

        var form = this;

        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'start')
                .val(start)
        );

        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'end')
                .val(end)
        );

        $(form).append(
            $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'lap')
                .val('semua')
        );

        
    });

});

function simpanClick(btn) {
        $('#idkirim').val(btn.value);
    }

    $('#yakinkirim').click(function () {
        var token = $('#token').val();
        var id = $('#idkirim').val();
        var route = "/simpandatastok/" + id;

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
                reloadTablepengiriman();
                $('#modalyakin').modal('toggle');
                return swal({
                    type: 'success',
                    title: 'Data berhasil disimpan.',
                    showConfirmButton: true,
                    timer: 1000
                }).catch(function (timeout) {});
            }
        });
    });

function reloadTableCari() {
    var table = $('#dataTableBuilderCari').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

function reloadTablepengiriman() {
    var table = $('#dataTableBuilderkirim').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

function kodeOtomatis() {
    var code = new Date();
    $('#kode').val('OP' + code.toString('ddMMyyHHmmss'));
}


function PilihClick(btn) {
    route = "/findbarangtoko/id/" + btn.value;

    $.get(route, function (res) {
        $('#barangasli').val(res.kode);
        $('#barang').val(res.kode);
        $('#nama').val(res.nama);
        $('#stok_komputer').val(number_format(res.stok, 0, ',', '.'));

        $('#barang').focus();

        $('#modalCari').modal('toggle');

        loadSelisih();

    });
}

function loadSelisih() {
    var stok_komputer = $('#stok_komputer').val();
    var stok_nyata = $('#stok_nyata').val();
    $('#selisih').val(null);

    if (stok_komputer != undefined && jQuery.trim(stok_komputer) != '' && intVal(stok_komputer) >= 0 && stok_nyata != undefined && jQuery.trim(stok_nyata) != '' && intVal(stok_nyata) >= 0) {
        var selisih = intVal(stok_nyata) - intVal(stok_komputer);
        $('#selisih').val(number_format(selisih, 0, ',', '.'));    
    }
}

$('#simpan').click(function () {
    var token = $('#token').val();

    var kode = $("#kode").val();
    if (kode == undefined || jQuery.trim(kode) == '') {
        alert('No. bukti tidak benar ! Refresh page!');
        return;
    }

    var tgl = $("#tgl").val();
    if (jQuery.trim(tgl) == '' || tgl==' ' || tgl == undefined) {
        alert('Tanggal stok opname tidak benar !');
        $('#tgl').focus();
        return;
    }

    var barang = $('#barangasli').val();
    if (jQuery.trim(barang) == '' || barang == undefined) {
        alert('Barang tidak valid!');
        $('#barang').focus();
        return;
    }

    var stok_komputer = $('#stok_komputer').val();
    if (jQuery.trim(stok_komputer) == '' || stok_komputer == undefined || intVal(stok_komputer) < 0) {
        alert('Inputkan stok komputer dengan benar !');
        return;
    }
    stok_komputer = intVal(stok_komputer);

    var stok_nyata = $('#stok_nyata').val();
    if (jQuery.trim(stok_nyata) == '' || stok_nyata == undefined || intVal(stok_nyata) < 0) {
        alert('Inputan stok nyata tidak benar !');
        return;
    }
    stok_komputer = intVal(stok_komputer);

    loadSelisih();

    var selisih = $('#selisih').val();
    if (jQuery.trim(selisih) == '' || selisih == undefined) {
        alert('Selisih tidak benar! Silahkan refresh page!');
        return;
    }
    selisih = intVal(selisih);

    var keterangan = $('#keterangan').val();
    if (keterangan == undefined) {
        keterangan = '';
    }

    $.ajax({
        url: '/stokopname',
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            kode: kode,
                tgl : tgl,
                barang: barang,
                stok_nyata : stok_nyata,
                stok_komputer : stok_komputer,
                selisih: selisih,
                keterangan: keterangan,
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
                    title: 'Sukses Menyimpan Data Stok Opname Barang',
                    showConfirmButton: true,
                    timer: 2000
                }).catch(function (timeout) {
                    kodeOtomatis();

                    $('#barang').val(null);
                    $('#barangasli').val(null);
                    $('#nama').val(null);
                    $('#stok_komputer').val(null);
                    $('#stok_nyata').val(null);
                    $('#selisih').val(null);
                    $(".inputantgl").datepicker("setDate", new Date());
                    $('#keterangan').val(null);

                    $('#barang').focus();
                });

        }
    });
});
    </script>
@endsection

