@extends('layouts.master')

@section('title','Nominal Gaji')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel panel-heading">
                <h4><i class="fa fa-plus"></i> Nominal Gaji</h4>
            </div>
            <div class="panel panel-body">
                <div class="form-group">
                    <label for="nominal">Nominal Gaji :</label>
                    <input type="text" name="nominalgaji" id="nominalgaji" class="form-control inputanangka" placeholder="Masukan Nominal Gaji">
                </div>
                <div class="form-group">
                    <a href="#" class="btn btn-primary pull-right" name="simpantambah" id="simpantambah"><i class="fa fa-plus"></i> Tambah</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> List Nominal Gaji</h4>
            </div>
            <div class="panel-body">
                <table id="nominaltabel" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('master.nominal.form_ubah')
@endsection

@section('footer')
    <script>
        $(document).ready(function() {
    $('#nominaltabel').DataTable({
        responsive: true,
        'ajax': {
            'url': '/nominalapi',
        },
        'columnDefs': [
        	{
            	'targets':0,
            	'sClass': "col-md-10",
                'render': function (data, type, full, meta) {
                    return number_format(intVal(data), 0, ',', '.');

                }
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
                        kembali += '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="fa fa-pencil"></i> </button>';
	                    kembali += '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="fa fa-trash"></i> </button>';

	                return kembali;

	            }
	        }
	    ],
        'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[1]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[1]);
            
        }
    });

    $('.inputanangka').on('keypress', function (e) {
            var c = e.keyCode || e.charCode;
            switch (c) {
                case 8: case 9: case 27: case 13:
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

    $('#nominalgaji').focus();
});

function reloadTable() {//membuat function yang bernama reload table
    var table = $('#nominaltabel').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

$('#simpantambah').click(function() {
	var route = "/nominal";
    var token = $('#token').val();

    var nominal = $('#nominalgaji').val();
    if (jQuery.trim(nominal) == '' || nominal == undefined) {
    	alert('Nama Gaji tidak boleh kosong !!');
        $('#nominalgaji').focus();
        return;
    }

    nominal = intVal(nominal);

	$.ajax({
		url: route,
		type: 'POST',
		headers: {'X-CSRF-TOKEN': token},
		dataType: 'json',
		data: {
			nominal: nominal,
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
			$('#nominalgaji').val('');
		}
	});
});

function UbahClick(btn) {
    var route = "/nominal/" + btn.value + "/edit";

    $.get(route, function (res) {
        $('#idubah').val(res.id);
        $('#nominalubah').val(res.nominal);
    });
}

$('#simpanubah').click(function () {
    var id = $('#idubah').val();
    var token = $('#token').val();
    var route = "/nominal/" + id;

    var nominalubah = $('#nominalubah').val();
    nominalubah = intVal(nominalubah);

    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            nominalubah: nominalubah,
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
    var route = "/nominal/" + id;

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