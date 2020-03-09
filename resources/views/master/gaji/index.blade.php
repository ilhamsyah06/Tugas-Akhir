@extends('layouts.master')

@section('title','Gaji')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h4><i class="fa fa-plus"></i> Tambah Gaji Karyawan</h4>
            </div>
            <div class="panel panel-body">
                <div class="form-group">
                    <label for="nomorgaji">Nomor Gaji :</label>
                    <input type="text" class="form-control" id="nomorgaji" name="nomorgaji" readonly>
                </div>
                <div class="form-group">
                    <label for="user">Pilih Karyawan :</label>
                    <select name="user" class="form-control select2" id="user" style="width: 100%;">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nominal">Pilih Nominal :</label>
                    <select name="nominal" class="form-control select2" id="nominal" style="width: 100%;">
                        <option value=""></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlahharikerja">Jumlah Hari Kerja :</label>
                    <input type="text" id="jumlahharikerja" name="jumlahharikerja" class="form-control" placeholder="Masukan Jumlah Hari Kerja">
                </div>
                <div class="form-group">
                    <label for="totalgaji">Total Gaji :</label>
                    <input type="text" class="form-control" id="totalgaji" name="totalgaji" readonly>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal Dibayar :</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal">
                </div>
                <div class="form-group">
                    <a href="#" id="simpantambah" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list-alt"></i> List Gaji Karyawan</h4>
            </div>
            <div class="panel-body">
                <table id="dataTablegajikaryawan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Jumlah Hari</th>
                            <th>Nominal</th>
                            <th>Total Gaji</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('layouts.modalhapus')
@include('master.gaji.form-ubah')
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}">
@endsection

@section('footer')
<script src="{{ asset('assets/select2/select2.full.min.js') }}"></script>

<script>

$(document).ready(function() {
  var t =   $('#dataTablegajikaryawan').DataTable({
        responsive: true,
        'ajax': {
            'url': '/gajiapi',
        },
        'columnDefs': [
        	{
            	'targets':0,
            	'sClass': "col-md-1 text-center",
        	},{
            	'targets':1,
            	'sClass': "col-md-2"
        	},{
            	'targets':2,
            	'sClass': "col-md-1"
        	},{
            	'targets':3,
            	'sClass': "col-md-2",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
        	},{
            	'targets':4,
            	'sClass': "col-md-2",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');
                }
        	},{
            	'targets':5,
            	'sClass': "col-md-1"
        	},{
	            'targets': 6,
	            'searchable': false,
	            "orderable": false,
	            "orderData": false,
	            "orderDataType": false,
	            "orderSequence": false,
	            "sClass": "text-center col-md-2 td-aksi",
	            'render': function (data, type, full, meta) {
                    var kembali = '';
                        kembali += '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="fa fa-pencil"></i> </button>';
	                    kembali += '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash"></i> </button>';

	                return kembali;

	            }
	        }
	    ],
        'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[6]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[6]);
            
        }
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    kodeOtomatis();
});

function kodeOtomatis() {
        route = "/gajiautokode";
        $.get(route, function (res) {
            $('#nomorgaji').val(res);
            reloadTable();
        });
    }

    //---- combobox pilih karyawan --//
        var routeuser = "/userapi";
        var routenominal = "/nominalapi";
        var inputuser = $('#user');
        var inputuserubah = $('#userubah');
        var inputnominal = $('#nominal');
        var inputnominalubah = $('#nominalubah');

        var listuser = document.getElementById("user");
        while (listuser.hasChildNodes()) {
            listuser.removeChild(listuser.firstChild);
        }
        inputuser.append('<option value=" "> -- Pilih Karyawan -- </option>');

        var listuserubah = document.getElementById("userubah");
        while (listuserubah.hasChildNodes()) {
            listuserubah.removeChild(listuserubah.firstChild);
        }
        inputuserubah.append('<option value=" "> -- Pilih Karyawan -- </option>');


        var listnominalubah = document.getElementById("nominalubah");
        while (listnominalubah.hasChildNodes()) {
            listnominalubah.removeChild(listnominalubah.firstChild);
        }
        inputnominalubah.append('<option value=" "> -- Pilih Nominal Gaji -- </option>');

        var listnominal = document.getElementById("nominal");
        while (listnominal.hasChildNodes()) {
            listnominal.removeChild(listnominal.firstChild);
        }
        inputnominal.append('<option value=" "> -- Pilih Nominal Gaji -- </option>');

        $.get(routeuser, function (res) {
            // console.log(res);
            $.each(res.data, function (index, value) {
                inputuser.append('<option value="' + value[1] + '">' + value[0] + '</option>');
                inputuserubah.append('<option value="' + value[1] + '">' + value[0] + '</option>');
            });
        });

        $.get(routenominal, function (res) {
            // console.log(res);
            $.each(res.data, function (index, value) {
                inputnominal.append('<option value="' + value[0] + '">' + number_format(value[0]) + '</option>');
                inputnominalubah.append('<option value="' + value[0] + '">' + number_format(value[0]) + '</option>');
            });
        });

        $('#user').select2();
        $('#userubah').select2();
        $('#nominal').select2();
        $('#nominalubah').select2();
    //---- combobox pilih karyawan --//

    $(document).ready(function () {
        $("#jumlahharikerja").keyup(function () {
            var blug = intVal($("#jumlahharikerja").val());
            var jlug = intVal($("#nominal").val());
            var totalug = jlug * blug;
            $("#totalgaji").val(formatRibuan(totalug));
        });
    });

    
    $(document).ready(function () {
        $("#jumlahharikerjaubah").keyup(function () {
            var blug = intVal($("#jumlahharikerjaubah").val());
            var jlug = intVal($("#nominalubah").val());
            var totalug = jlug * blug;
            $("#totalgajiubah").val(formatRibuan(totalug));
        });
    });


    $('#simpantambah').click(function () {
        var route = "/gaji";
        var token = $('#token').val();

        var user = $('#user').val();
        if (jQuery.trim(user) == 0 || user == undefined) {
            alert('Harap Pilih Nama Karyawan !!');
            $('#user').focus();
            return false;
        }

        var nominal = $('#nominal').val();
        if (jQuery.trim(nominal) == 0 || nominal == undefined) {
            alert('Harap Pilih Nominal Gaji !!');
            $('#nominal').focus();
            return false;
        }

        var jumlahhari = $('#jumlahharikerja').val();
        if (jQuery.trim(jumlahhari) == '' || jumlahhari == undefined) {
            alert('Jumlah Hari Tidak Boleh Kosong !!');
            $('#jumlahharikerja').focus();
            return;
        }

        var nomorgaji = $('#nomorgaji').val();
        if (jQuery.trim(nomorgaji) == '' || nomorgaji == undefined) {
            alert('Nomor Gaji Tidak Boleh Kosong !!');
            $('#nomorgaji').focus();
            return;
        }

        var totalgaji = $('#totalgaji').val();
        if (jQuery.trim(totalgaji) == '' || totalgaji == undefined) {
            alert('Total Gaji Tidak Boleh Kosong !!');
            $('#totalgaji').focus();
            return;
        }

        totalgaji = intVal(totalgaji);

        var tanggal = $('#tanggal').val();
        if (jQuery.trim(tanggal) == '' || tanggal == undefined) {
            alert('Tanggal tidak boleh kosong !!');
            $('#tanggal').focus();
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
                user: user,
                nominal: nominal,
                jumlahhari: jumlahhari,
                totalgaji: totalgaji,
                tanggal: tanggal,
                nomorgaji: nomorgaji,
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
          }).catch(function(timeout) { });
            },
            success: function () {
                reloadTable();
                $('#user').val('');
                $('#nominal').val('');
                $('#jumlahharikerja').val('');
                $('#totalgaji').val('');
                $('#tanggal').val('');
        
            return swal({
              type: 'success',
              title: 'Data berhasil disimpan.',
              showConfirmButton: true,
              timer: 2000
          }).catch(function(timeout) { });

            }
        });
    });

    
    function reloadTable() {
        var table = $('#dataTablegajikaryawan').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }

    function UbahClick(btn) {
        route = "/gaji/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#idubah').val(res.id);
            $('#jumlahharikerjaubah').val(res.jumlahhari);
            $('#tanggalubah').val(res.tanggal);
            $('#nomorgajiubah').val(res.nomorgaji);
            $('#userubah').val('' + res.user).trigger('change');
            $('#nominalubah').val('' + res.nominal).trigger('change');
            $('#totalgajiubah').val(number_format(intVal(res.totalgaji), 0, ',', '.'));
            $('#jumlahharikerjaubah').focus();
        });

    }

    $('#simpanubah').click(function () {
        var id = $('#idubah').val();
        var token = $('#token').val();
        var route = "/gaji/" + id;

        var user = $('#userubah').val();
        if (jQuery.trim(user) == 0 || user == undefined) {
            alert('Harap Pilih Nama Karyawan !!');
            $('#userubah').focus();
            return false;
        }

        var nominal = $('#nominalubah').val();
        if (jQuery.trim(nominal) == 0 || nominal == undefined) {
            alert('Harap Pilih Nominal Gaji !!');
            $('#nominalubah').focus();
            return false;
        }

        var nomorgaji = $('#nomorgajiubah').val();
        if (jQuery.trim(nomorgaji) == '' || nomorgaji == undefined) {
            alert('Nomor Gaji Tidak Boleh Kosong !!');
            $('#nomorgajiubah').focus();
            return;
        }

        var jumlahhari = $('#jumlahharikerjaubah').val();
        if (jQuery.trim(jumlahhari) == '' || jumlahhari == undefined) {
            alert('Jumlah Hari Tidak Boleh Kosong !!');
            $('#jumlahharikerjaubah').focus();
            return;
        }

        var totalgaji = $('#totalgajiubah').val();
        if (jQuery.trim(totalgaji) == '' || totalgaji == undefined) {
            alert('Total Gaji Tidak Boleh Kosong !!');
            $('#totalgajiubah').focus();
            return;
        }

        totalgaji = intVal(totalgaji);

        var tanggal = $('#tanggalubah').val();
        if (jQuery.trim(tanggal) == '' || tanggal == undefined) {
            alert('Tanggal tidak boleh kosong !!');
            $('#tanggalubah').focus();
            return;
        }

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'PUT',
            dataType: 'json',
            data: {
                user: user,
                nomorgaji: nomorgaji,
                nominal: nominal,
                jumlahhari: jumlahhari,
                totalgaji: totalgaji,
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
        var route = "/gaji/" + id;

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
          }).catch(function(timeout) { });
            },
            success: function () {
                reloadTable();
                $('#modalHapus').modal('toggle');
                return swal({
              type: 'success',
              title: 'Data berhasil dihapus.',
              showConfirmButton: true,
              timer: 2000
          }).catch(function(timeout) { });
            }
        });
    });

</script>
@endsection