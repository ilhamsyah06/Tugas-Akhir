@extends('layouts.master')

@section('title', 'Kategori Barang')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-plus"></i> Tambah Kategori</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="jenis">Jenis Barang : </label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Jenis Barang" value="" required autofocus />
                </div>
                <div class="form-group">
                    <a href="#" class="btn btn-primary btn-flat" type="submit" id="simpantambah"><i class="fa fa-save"></i>
                        Simpan Data</a>
                </div>
            </div>
        </div>
    </div>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4><i class="fa fa-list"></i> List Kategori Barang</h4>
        </div>
        <div class="panel-body">
            <table id="kategori" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
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
@include('master.kategori.form')
@endsection

@section('footer')
<script>
$(document).ready(function() {
    $('#kategori').DataTable({
        "pagingType": "full_numbers",
        responsive: true,
        'ajax': {
            'url': '/api/kategori',
        },
        'columnDefs': [
        	{
            	'targets':0,
            	'sClass': "col-md-10"
        	},{
	            'targets': 1,
	            'searchable': false,
	            "orderable": false,
	            "orderData": false,
	            "orderDataType": false,
	            "orderSequence": false,
	            "sClass": "text-center col-md-2 td-aksi",
	            'render': function (data, type, full, meta) {
                    var kembali = '';
                        kembali += '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="glyphicon glyphicon-pencil"></i> </button>';
	                    kembali += '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="glyphicon glyphicon-trash"></i> </button>';

	                return kembali;

	            }
	        }
	    ],
        'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[1]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[1]);
            
        }
    });
    document.getElementById("nama").maxLength = 50;//kolom nama kategori masikmal 50 karakter
});

function reloadTable() {//membuat function yang bernama reload table
    var table = $('#kategori').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

$('#simpantambah').click(function() {
	var route = "/kategori";
    var token = $('#token').val();

    var nama = $('#nama').val();
    if (jQuery.trim(nama) == '' || nama == undefined) {
    	alert('Nama Jenis Barang tidak boleh kosong !!');
        $('#nama').focus();
        return;
    }

	$.ajax({
		url: route,
		type: 'POST',
		headers: {'X-CSRF-TOKEN': token},
		dataType: 'json',
		data: {
			nama: nama,
			_token: token
		},
		error: function (res) {
			var errors = res.responseJSON;
			var pesan = '';
			$.each(errors, function (index, value) {
				pesan += value + "\n";
			});
            swal({
                        title: 'Oops...',
                        text: pesan,
                        type: 'error'
                    })
		},
		success: function () {
			reloadTable();                                                                                                                                                                                                   
            swal({
                        title: 'Sukses!!',
                        text: 'Data Berhasil Disimpan',
                        type: 'success',
                        timer: '1500'
                    });
			$('#nama').val('');
		}
	});
});

function UbahClick(btn) {
    var route = "/kategori/" + btn.value + "/edit";

    $.get(route, function (res) {
        $('#idubah').val(res.id);
        $('#namaubah').val(res.nama);
    });
}

$('#simpanubah').click(function () {
    var id = $('#idubah').val();
    var token = $('#token').val();
    var route = "/kategori/" + id;

    var namaubah = $('#namaubah').val();
    if (namaubah == '' || jQuery.trim(namaubah) == '' || namaubah == undefined) {
    	alert('Nama jenis barang tidak boleh dikosongkan');
        $('#namaubah').focus();
        return;
    }

    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            nama: namaubah,
            _token: token
        },
        error: function (res) {
            var errors = res.responseJSON;
            var pesan = '';
            $.each(errors, function (index, value) {
                pesan += value + "\n";
            });
            swal({
                        title: 'Oops...',
                        text: pesan,
                        type: 'error'
                    })
        },
        success: function () {
            reloadTable();
            swal({
                        title: 'Sukses!!',
                        text: 'Data Berhasil Diubah',
                        type: 'success',
                        timer: '1500'
                    })
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
    var route = "/kategori/" + id;

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
            swal({
                        title: 'Oops...',
                        text: pesan,
                        type: 'error'
                    })
        },
        success: function () {
            reloadTable();
            swal({
                        title: 'Sukses!!',
                        text: 'Data Berhasil Dihapus!!',
                        type: 'success',
                        timer: '1500'
                    });
            $('#modalHapus').modal('toggle');
        }
    });
});
</script>
@endsection
