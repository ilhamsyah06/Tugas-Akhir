@extends('layouts.master')

@section('title','User')
@php $users = DB::table('users')->get(); @endphp
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user-plus"></i> Tambah User</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Form::label('nama', 'Nama') !!}
                    {!! Form::text('nama', null, ['class' => 'form-control', 'placeholder' => 'Masukan Nama Pengguna',
                    'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Masukan Email Pengguna',
                    'required']) !!}
                </div>

                {!! Form::label('password', 'Password') !!} <br />
                <div class="form-group input-group">
                    {!! Form::text('password', null, ['class' => 'form-control', 'placeholder' => 'Masukan Kata Sandi'])
                    !!}
                    <span class="input-group-btn">
                        <a class="btn btn-flat btn-success" id="katasandiauto">
                            <i class="fa fa-magic"> Otomatis</i>
                        </a>
                    </span>
                </div>

                <div class="form-group">
                    {!! Form::label('alamat', 'Alamat') !!}
                    {!! Form::textarea('alamat', null, ['id' => 'alamat', 'rows' => 4, 'cols' => 43, 'style' =>
                    'resize:none']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('nomor', 'Nomor Telephone/Wa') !!}
                    {!! Form::text('nomor', null, ['class' => 'form-control', 'placeholder' => 'Masukan Nomor Pengguna',
                    'required']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('level', 'Level') !!}
                    {!! Form::select('level', array('' => '-- Pilih Level --', 'admin' => 'Admin', 'kasir' => 'Kasir',
                    'gudang' => 'Gudang'), null, ['class' => 'form-control']); !!}
                </div>

                <div class="form-group">
                    <a href="#" class="btn btn-primary btn-flat" type="submit" id="simpantambah"><i
                            class="fa fa-save"></i>
                        Simpan Data</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-users"></i> List Status User</h4>
            </div>
            <div class="panel-body">
                <table id="supplier" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Waktu Login Terakhir</th>
                            <th>Waktu Logout Terakhir</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->last_login_at}}</td>
                                <td>{{$user->last_logout_at}}</td>
                                <td @if(Cache::has('user-is-online-' . $user->id)) class="success" @else class="danger" @endif>
                                    @if(Cache::has('user-is-online-' . $user->id))
                                        <span class="text-success"><i class="fa fa-user"></i> Online</span>
                                    @else
                                        <span class="text-danger"><i class="fa fa-times"></i> Offline</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-list-alt"></i> List User</h4>
            </div>
            <div class="panel-body">
                <table id="user" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Level</th>
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

@include('master.user.modal')
@endsection

@section('footer')
<script>
    $(document).ready(function () {
      var t = $('#user').DataTable({
            responsive: true,
            scrollX: true,
            'ajax': {
                'url': '/api/user',
            },
            'columnDefs': [{
                    'targets': 0,
                    'sClass': "text-center col-md-1"
                },{
                    'targets': 1,
                    'sClass': "col-md-4"
                },
                {
                    'targets': 2,
                    'sClass': "col-md-3"
                },
                {
                    'targets': 3,
                    'sClass': "col-md-1",
                    render: function (data, type, row, meta) {
                        var label = '';
                        if (data == 'admin') {
                            label = 'label-success';
                        }
                        if (data == 'gudang') {
                            label = 'label-danger';
                        }
                        if (data == 'kasir') {
                            label = 'label-info';
                        }
                        return '<span class="label ' + label + '">' + data + '</span>';
                    }
                },
                {
                    'targets': 4,
                    'searchable': false,
                    "orderable": false,
                    "orderData": false,
                    "orderDataType": false,
                    "orderSequence": false,
                    "sClass": "text-center col-md-4 td-aksi",
                    'render': function (data, type, full, meta) {
                        var button =
                            '<button title="Lihat Data" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modalLihat" onclick="LihatClick(this);"><i class="glyphicon glyphicon-eye-open"></i> </button>';
                        button +=
                            '<button title="Ubah Data" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="glyphicon glyphicon-pencil"></i> </button>';
                        button +=
                            '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="glyphicon glyphicon-trash"></i> </button>';

                        return button;

                    }
                }
            ],
            'rowCallback': function (row, data, dataIndex) {
                $(row).find('button[class="btn btn-info btn-flat"]').prop('value', data[4]);
                $(row).find('button[class="btn btn-warning btn-flat"]').prop('value', data[4]);
                $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[4]);
            }
        });

        t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

        document.getElementById("nama").maxLength = 50; //kolom nama kategori masikmal 50 karakter
        document.getElementById("password").maxLength = 20; //kolom nama kategori masikmal 20 karakter
        document.getElementById("alamat").maxLength = 100; //kolom nama kategori masikmal 100 karakter
        document.getElementById("nomor").maxLength = 14; //kolom nama kategori masikmal 50 karakter

        $('#ubahkatasandi').click(function () {
            var ubahkatasandi = $('#ubahkatasandi').is(':checked') ? 1 : 0;
            if (ubahkatasandi == 1) {
                $('#divSandi').removeClass('hidden');
                // $('#divValidasiSandi').removeClass('hidden');
            } else {
                $('#divSandi').addClass('hidden');
                // $('#divValidasiSandi').addClass('hidden');
            }
        });

    });

    function LihatClick(btn) {
        route = "/user/" + btn.value;

        $.get(route, function (res) {
            $('#namalihat').val(res.nama);
            $('#emaillihat').val(res.email);
            $('#alamatlihat').val(res.alamat);
            $('#nomorlihat').val(res.nomor);
            $('#levellihat').val(res.level);
        });

    }

    $('#simpantambah').click(function () {
        var route = "/user";
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

        var password = $('#password').val();
        if (jQuery.trim(password) == '' || password == undefined) {
            alert('Password tidak boleh kosong !!');
            $('#password').focus();
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

        var level = $('#level').val();
        if (jQuery.trim(level) == 0 || level == undefined) {
            alert('Harap Pilih Level Akunmu !!');
            $('#level').focus();
            return false;
        }

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
                nama: nama,
                email: email,
                password: password,
                alamat: alamat,
                nomor: nomor,
                level: level,
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
                $('#password').val('');
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

    function reloadTable() {
        var table = $('#user').dataTable();
        table.cleanData;
        table.api().ajax.reload();
    }

    function UbahClick(btn) {
        route = "/user/" + btn.value + "/edit";

        $.get(route, function (res) {
            $('#idubah').val(res.id);
            $('#namaubah').val(res.name);
            $('#emailubah').val(res.email);
            $('#alamatubah').val(res.alamat);
            $('#passwordubah').val('');
            $('#nomorubah').val(res.nomorhp);
            $('#levelubah').val('' + res.level).trigger('change');

            var ubahkatasandi = $('#ubahkatasandi').is(':checked') ? 1 : 0;
            if (ubahkatasandi == 1) {
                $('#divSandi').addClass('hidden');
                // $('#divValidasiSandi').addClass('hidden');

                $("#ubahkatasandi").prop('checked', false);
            }

        });

        document.getElementById("alamat").maxLength = 100; //kolom nama kategori masikmal 100 karakter
        document.getElementById("nomorubah").maxLength = 13; //kolom nama kategori masikmal 50 karakter
        document.getElementById("namaubah").maxLength = 50; //kolom nama kategori masikmal 50 karakter
        document.getElementById("password").maxLength = 20; //kolom nama kategori masikmal 20 karakter

    }

    $('#simpanubah').click(function () {
        var id = $('#idubah').val();
        var token = $('#token').val();
        var route = "/user/" + id;

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

        var level = $('#levelubah').val();
        if (jQuery.trim(level) == 0 || level == undefined) {
            alert('Harap Pilih Level Akunmu !!');
            $('#levelubah').focus();
            return false;
        }

        var password = '';
        var ubahkatasandi = $('#ubahkatasandi').is(':checked') ? 1 : 0;
        if (ubahkatasandi == 1) {
            password = $('#passwordubah').val();
            if (password == '' || password == undefined) {
                alert('Kata Sandi tidak boleh kosong');
                $('#passwordubah').focus();
                return;
            }
        }

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': token
            },
            type: 'PUT',
            dataType: 'json',
            data: {
                name: nama,
                email: email,
                alamat: alamat,
                nomorhp: nomor,
                ubahkatasandi: ubahkatasandi,
                level: level,
                password: password,
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

    function HapusClick(btn) {
        $('#idHapus').val(btn.value);
    }

    $('#yakinhapus').click(function () {
        var token = $('#token').val();
        var id = $('#idHapus').val();
        var route = "/user/" + id;

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

    function randomPassword(length) { // membuat function yang berfungsi membuat kode acak
        var randomstring = Math.random().toString(36).slice(length * -1);
        return randomstring;
    }

    $('#katasandiautoubah').click(function () {
        $('#passwordubah').val(randomPassword(5));
    });

    $('#katasandiauto').click(function () { //membuat aksi ketika button otomatis di klik, mengisi form password 
        $('#password').val(randomPassword(5));
    });
</script>

@endsection