@extends('layouts.master')

@section('title','Uang Modal Kasir')

@php
    $now = date('Y-m-d');
@endphp
@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card-counter danger animated flipInX">
            <i class="fa fa-money"></i>
            @foreach ($uangawal as $item)
            <span class="count-numbers">Rp.{{ number_format($item->uang_awal) }},-</span>
            @endforeach
            <span class="count-name">Uang Awal</span>
            <span class="count-sub-name">{{ $hariini }}</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-counter success animated flipInX">
            <i class="fa fa-money"></i>
            @foreach ($uangawal as $item)
            <span class="count-numbers">Rp.{{ number_format($item->uang_akhir) }},-</span>
            @endforeach
            <span class="count-name">Uang Akhir</span>
            <span class="count-sub-name">{{ $hariini }}</span>
        </div>
    </div>
</div>

<div class="row">
    <br>
    @if (Auth::user()->level === 'kasir')
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-money"></i> Tambah Uang Modal Kasir</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('nominal', 'Nominal') !!}
                    {!! Form::text('nominal', null, ['class' => 'form-control inputanangka', 'placeholder' => 'Masukan Uang Modal Kasir', 'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('tanggal', 'Tanggal :') !!}
                    {!! Form::date('tanggal', $now, ['class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    <a href="#" class="btn btn-primary btn-flat" type="submit" id="simpantambah"><i
                            class="fa fa-save"></i>
                        Simpan Data</a>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list"></i> List Uang Modal Kasir</h4>
            </div>
            <div class="panel-body">
                <table id="uangmodal" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="background-color:red; color:white;">Uang Awal</th>
                            <th style="background-color:green; color:white;">Uang Akhir</th>
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
@include('master.modal_kasir.modal')
@endsection

@section('footer')
<script>
    $(document).ready(function () {
    var route = "/cekhakakses";
    var bolehUbah;
    $.get(route, function (res) {
        bolehUbah = res;
    });

        var t = $('#uangmodal').DataTable({
            "pagingType": "full_numbers",
            "responsive": true,
            'ajax': {
                'url': '/api/modalkasir',
            },

            'columnDefs': [{
                'targets': 0,
                "orderable": false,
                "orderData": false,
                'sClass': "text-center col-md-1"
            }, {
                'targets': 1,
                "orderable": false,
                "orderData": false,
                'sClass': "text-center col-md-2",
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            }, {
                'targets': 2,
                'sClass': "text-center col-md-2",
                "orderable": false,
                "orderData": false,
                'render': function (data, type, full, meta) {
                    return 'Rp.' + number_format(intVal(data), 0, ',', '.') + ',-';

                }
            }, {
                'targets': 3,
                'sClass': "text-center col-md-2"
            }, {
                'targets': 4,
                'searchable': false,
                "orderable": false,
                "orderData": false,
                "orderDataType": false,
                "orderSequence": false,
                "sClass": "text-center col-md-2 td-aksi",
                'render': function (data, type, full, meta) {
                    var button =
                        '<button title="Lihat Data" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modalLihat" onclick="LihatClick(this);"><i class="glyphicon glyphicon-eye-open"></i> </button>';
                        if (bolehUbah == 'admin') {
                    button +=
                        '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="fa fa-pencil-square-o fa-fw"></i> </button>';
                    button +=
                        '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="glyphicon glyphicon-trash"></i> </button>';
                        }
                    return button;

                }
            }],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-info btn-flat"]').prop('value', data[4]);
                if (bolehUbah == 'admin') {
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[4]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[4]);
                }

            }
        });

        t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

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

    });

    });

    function reloadTable() {
    var table = $('#uangmodal').dataTable();
    table.cleanData;
    table.api().ajax.reload();
}

    $('#simpantambah').click(function() {
	var route = "/modalkasir";
    var token = $('#token').val();

    var nominal = $('#nominal').val();
    if (jQuery.trim(nominal) == '' || nominal == ' ' || intVal(nominal) < 0) {
        alert('Nominal tidak boleh kosong.');
        $('#nominal').focus();
        return;
    }
    nominal = intVal(nominal);

    var tanggal = $('#tanggal').val();
        if (jQuery.trim(tanggal) == 0 || tanggal == undefined) {
            alert('Harap Isikan Tanggal !!');
            $('#tanggal').focus();
            return false;
        }

	$.ajax({
		url: route,
		type: 'POST',
		headers: {'X-CSRF-TOKEN': token},
		dataType: 'json',
		data: {
            uangawal: nominal,
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
          }).catch(function(timeout) { });
		},
		success: function () {
            reloadTable();
            $('#nominal').val('');
            $('#tanggal').val('');                                                                                                                                                                                                     
            return swal({
              type: 'success',
              title: 'Data berhasil disimpan.',
              showConfirmButton: true,
              timer: 2000
          }).catch(function(timeout) { 
              window.location.href = '/modalkasir';
          });
		}
	});
});

function UbahClick(btn) {
        route = "/modalkasir/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#idubah').val(res.id);
            $('#nominalubah').val(number_format(intVal(res.uang_awal), 0, ',', '.'));
            $('#tanggalubah').val(res.tanggal);
            $('#userinput').val(res.user_id);
        });

    }

    $('#simpanubah').click(function () {
    var id = $('#idubah').val();
    var token = $('#token').val();
    var route = "/modalkasir/" + id;

    var nominal = $('#nominalubah').val();
    if (jQuery.trim(nominal) == '' || nominal == ' ' || intVal(nominal) < 0) {
        alert('Nominal Tidak tidak valid');
        $('#nominal').focus();
        return;
    }
    nominal = intVal(nominal);

    var tanggal = $('#tanggalubah').val();

    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            nominal: nominal,
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
          }).catch(function(timeout) { });
        },
        success: function () {
            reloadTable();
            $('#modalUbah').modal('toggle');
            return swal({
              type: 'success',
              title: 'Data berhasil disimpan.',
              showConfirmButton: true,
              timer: 2000
          }).catch(function(timeout) {
              window.location.href = '/modalkasir';
           });
        }
    });
});

function LihatClick(btn) {
        route = "/modalkasir/" + btn.value;

        $.get(route, function (res) {
            $('#nominalawallihat').val(number_format(intVal(res.uangawal), 0, ',', '.'));
            $('#nominalakhirlihat').val(number_format(intVal(res.uangakhir), 0, ',', '.'));
            $('#tanggallihat').val(res.tanggal);
            $('#userinputlihat').val(res.userinput);
        });

    }


function HapusClick(btn) {
        $('#idHapus').val(btn.value);
    }

    $('#yakinhapus').click(function () {
        var token = $('#token').val();
        var id = $('#idHapus').val();
        var route = "/modalkasir/" + id;

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
                swal({
                    title: 'Oops!!',
                    text: pesan,
                    type: 'error'
                });
            },
            success: function () {
                reloadTable();
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