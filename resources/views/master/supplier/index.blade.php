@extends('layouts.master')

@section('title', 'Supplier')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user-plus"></i> Tambah Supplier</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('nama', 'Nama') !!}
                    {!! Form::text('nama', null, ['class' => 'form-control', 'placeholder' => 'Masukan Nama Supplier', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Masukan Email Supplier', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('alamat', 'Alamat') !!}
                    {!! Form::textarea('alamat', null, ['id' => 'alamat', 'rows' => 4, 'cols' => 57, 'style' => 'resize:none']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('nomor', 'Nomor Telephone/Wa') !!}
                    {!! Form::text('nomor', null, ['class' => 'form-control', 'placeholder' => 'Masukan Nomor Supplier', 'required']) !!}
                </div>

                <div class="form-group">
                    <a href="#" class="btn btn-primary btn-flat" type="submit" id="simpantambah"><i class="fa fa-save"></i>
                        Simpan Data</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-users"></i> List Supplier</h4>
            </div>
            <div class="panel-body">
                <table id="supplier" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Nomor HP</th>
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

@include('master.supplier.modal')

@endsection

@section('footer')
    <script>
    $(document).ready(function() {
   var t = $('#supplier').DataTable({
        responsive: true,
        'ajax': {
            'url': '/api/supplier',
        },
        'columnDefs': [
            {
            	'targets':0,
            	'sClass': "text-center col-md-1"
            },
        	{
            	'targets':1,
            	'sClass': "col-md-3"
            },
            {
                'targets':2,
            	'sClass': "col-md-3"
            },
            {
	            'targets': 3,
	            'searchable': false,
	            "orderable": false,
	            "orderData": false,
	            "orderDataType": false,
	            "orderSequence": false,
	            "sClass": "text-center col-md-4 td-aksi",
	            'render': function (data, type, full, meta) {
                    var button = '<button title="Lihat Data" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modalLihat" onclick="LihatClick(this);"><i class="glyphicon glyphicon-eye-open"></i> </button>';
                        button += '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="glyphicon glyphicon-pencil"></i> </button>';
                        button += '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="glyphicon glyphicon-trash"></i> </button>';

	                return button;

	            }
	        }
	    ],
        'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-info btn-flat"]').prop('value', data[0]);
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[0]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[0]);
        }
    });

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

document.getElementById("nama").maxLength = 50;//kolom nama kategori masikmal 50 karakter
document.getElementById("alamat").maxLength = 150;//kolom nama kategori masikmal 100 karakter
document.getElementById("nomor").maxLength = 14;//kolom nama kategori masikmal 50 karakter

});


function LihatClick(btn) {
    route = "/supplier/" + btn.value;

    $.get(route, function (res) {
        $('#namalihat').val(res.nama_supplier);
        $('#emaillihat').val(res.email);
        $('#alamatlihat').val(res.alamat);
        $('#nomorlihat').val(res.nomor_hp);
        $('#userinput').val(res.user_id);
        $('#tglinput').val(res.tgl_input);
    });

}

function UbahClick(btn) {
    route = "/supplier/" + btn.value + "/edit";

    $.get(route, function (res) {
        $('#idubah').val(res.id);
        $('#namaubah').val(res.nama_supplier);
        $('#emailubah').val(res.email);
        $('#alamatubah').val(res.alamat);
        $('#nomorubah').val(res.nomor_hp);

    });

        document.getElementById("namaubah").maxLength = 50;//kolom nama kategori masikmal 50 karakter
        document.getElementById("alamatubah").maxLength = 150;//kolom nama kategori masikmal 100 karakter
        document.getElementById("nomorubah").maxLength = 13;//kolom nama kategori masikmal 50 karakter

}

$('#simpanubah').click(function () {
    var id = $('#idubah').val();
    var token = $('#token').val();
    var route = "/supplier/" + id;

    var nama = $('#namaubah').val();
    if (jQuery.trim(nama) == '' || nama == undefined) {
    	alert('Nama tidak boleh kosong !!');
        $('#namaubah').focus();
        return;
    }

    var email = $('#emailubah').val();
    if (jQuery.trim(email) == '' || email == undefined) {
    	alert('Email tidak boleh kosong !!');
        $('#emailubah').focus();
        return;
    }

    var alamat = $('#alamatubah').val();
    if (jQuery.trim(alamat) == '' || alamat == undefined) {
    	alert('Alamat tidak boleh kosong !!');
        $('#alamatubah').focus();
        return;
    }

    var nomor = $('#nomorubah').val();
    if (jQuery.trim(nomor) == '' || nomor == undefined) {
    	alert('Nomor tidak boleh kosong !!');
        $('#nomorubah').focus();
        return;
    }

    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            name : nama,
            email : email,
            alamat : alamat,
            nomorhp : nomor,
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
            $('#modalUbah').modal('toggle');
            return swal({
              type: 'success',
              title: 'Data berhasil diubah.',
              showConfirmButton: true,
              timer: 2000
          }).catch(function(timeout) { });
        }
    });
});


function reloadTable() {
    var table = $('#supplier').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

    $('#simpantambah').click(function() {
	var route = "/supplier";
    var token = $('#token').val();

    var nama = $('#nama').val();
    if (jQuery.trim(nama) == '' || nama == undefined) {
    	alert('Nama tidak boleh kosong !!');
        $('#nama').focus();
        return;
    }

    var email = $('#email').val();
    if (jQuery.trim(email) == '' || email == undefined) {
    	alert('Email tidak boleh kosong !!');
        $('#email').focus();
        return;
    }

    var alamat = $('#alamat').val();
    if (jQuery.trim(alamat) == '' || alamat == undefined) {
    	alert('Alamat tidak boleh kosong !!');
        $('#alamat').focus();
        return;
    }

    var nomor = $('#nomor').val();
    if (jQuery.trim(nomor) == '' || nomor == undefined) {
    	alert('Nomor tidak boleh kosong !!');
        $('#nomor').focus();
        return;
    }

	$.ajax({
		url: route,
		type: 'POST',
		headers: {'X-CSRF-TOKEN': token},
		dataType: 'json',
		data: {
            nama: nama,
            email: email,
            alamat: alamat,
            nomor: nomor,
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
            $('#nama').val('');
            $('#email').val('');
            $('#alamat').val('');
            $('#nomor').val('');
            return swal({
              type: 'success',
              title: 'Data berhasil disimpan.',
              showConfirmButton: true,
              timer: 2000
          }).catch(function(timeout) { });
		}
	});
});

function HapusClick(btn) {
    $('#idHapus').val(btn.value);
}

$('#yakinhapus').click(function () {
    var token = $('#token').val();
    var id = $('#idHapus').val();
    var route = "/supplier/" + id;

    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
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