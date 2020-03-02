$(document).ready(function() {

$('#user').DataTable({
    responsive: true,
    'ajax': {
        'url': '/api/user',
    },
    'columnDefs': [
        {
            'targets':0,
            'sClass': "col-md-3"
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
                var button = '';
                    button += '<button title="Lihat Data" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modalLihat" onclick="LihatClick(this);"><i class="glyphicon glyphicon-eye-open"></i> </button>';
                    button += '<button title="Ubah Data" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modalUbah" onclick="UbahClick(this);"><i class="glyphicon glyphicon-pencil"></i> </button>';
                    button += '<button title="Hapus Data" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modalHapus" onclick="HapusClick(this);"><i class="glyphicon glyphicon-trash"></i> </button>';

                return button;

            }
        }
    ],
    'rowCallback': function (row, data, dataIndex) {
            $(row).find('button[class="btn btn-info btn-flat"]').prop('value', data[3]);
            $(row).find('button[class="btn btn-success btn-flat"]').prop('value', data[3]);
            $(row).find('button[class="btn btn-danger btn-flat"]').prop('value', data[3]);
        
    }
});
document.getElementById("nama").maxLength = 50;//kolom nama kategori masikmal 50 karakter
document.getElementById("password").maxLength = 20;//kolom nama kategori masikmal 20 karakter
document.getElementById("alamat").maxLength = 100;//kolom nama kategori masikmal 100 karakter
document.getElementById("nomor").maxLength = 14;//kolom nama kategori masikmal 50 karakter

});

function LihatClick(btn) {
route = "/user/" + btn.value;

$.get(route, function (res) {
    $('#namalihat').val(res.nama);
    $('#emaillihat').val(res.email);
    $('#passwordlihat').val(res.password);
    $('#alamatlihat').val(res.alamat);
    $('#nomorlihat').val(res.nomor);
    $('#levellihat').val(res.level);
});

}

$('#simpantambah').click(function() {
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

$.ajax({
    url: route,
    type: 'POST',
    headers: {'X-CSRF-TOKEN': token},
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
        swal({
                    title: 'Oops...',
                    text: pesan,
                    type: 'error'
                });
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

function reloadTable() {
var table = $('#user').dataTable();
table.cleanData;
table.api().ajax.reload();
}

function HapusClick(btn) {
$('#idHapus').val(btn.value);
}

$('#yakinhapus').click(function () {
var token = $('#token').val();
var id = $('#idHapus').val();
var route = "/user/" + id;

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

        alert(pesan);
    },
    success: function () {
        reloadTable();
        alert('Sukses Menghapus Data');
        $('#modalHapus').modal('toggle');
    }
});
});
