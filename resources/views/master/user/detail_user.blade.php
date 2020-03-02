@extends('layouts.master')

@section('title', 'Detail User')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><i class="fa fa-user"></i> Detail User</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="jenis">Nama : </label>
                <input type="text" class="form-control" name="namadetail" readonly value="{{  $ambildataid->name }}"
                        required />
                </div>

                <div class="form-group">
                    <label for="jenis">Email : </label>
                    <input type="email" class="form-control" name="emaildetail"readonly value="{{  $ambildataid->email }}"
                        required />
                </div>

                <div class="form-group">
                    <label for="jenis">Alamat : </label>
                    <textarea name="alamatlihat" id="alamatdetail" cols="43" rows="4" value="" readonly
                        required>{{  $ambildataid->alamat }}</textarea>
                </div>

                <div class="form-group">
                    <label for="jenis">Nomor Telphone/Wa : </label>
                    <input type="number" class="form-control" name="nomordetail"readonly value="{{  $ambildataid->nomorhp }}"
                        required />
                </div>

                <div class="form-group">
                    <label for="jenis">Level : </label>
                    <input type="text" class="form-control" name="leveldetail" readonly value="{{  $ambildataid->level }}"
                        required />
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Note: Silahkan Tekan Tombol Dibawah Untuk Absen Hari Ini</label><br>
            <button name="absenhariini" id="absenhariini" class="btn btn-primary btn-lg"  @if ($count === 1 ) disabled @else  @endif><i class="fa fa-calendar-check-o"></i> ABSEN SEKARANG</button><br>
            @foreach ($ambildataabsen as $item)
            <span class="label label-warning"> Waktu Absen : {{ date($item->created_at) }}</span><br>
            @endforeach

            @if ($count === 1 ) 
            <span class="label label-info">Terima Kasih Sudah Absen, Selamat Ber-Aktifitas !!</span>
            @else  
             <script>
                  var user = {!! json_encode((array)auth()->user()->name) !!};
             swal({
                  type: 'info',
                  title: 'Hai '+ user +', Jangan Lupa Absen Yaa !',
                  timer: 2000
              }).catch(function(timeout) { });
             </script>
            @endif
        </div>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4><i class="fa fa-money"></i> Detail Gaji Bulan Ini</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <h3 style="margin:5px;">NAMA : {{ Auth::user()->name}}</h3>
                    <h3 style="margin:5px;">TOTAL GAJI : Rp.2.3000.000,-</h3>
                    <span class="label label-danger">Dibayar Tanggal : 2020-03-12</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>

    $('#absenhariini').click(function () {
        var route = "/absenhariini";
        var token = $('#token').val();

        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            data: {
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
            return swal({
              type: 'success',
              title: 'Terima Kasih Sudah Absen, Selamat Bekerja !!.',
              timer: 2000
          }).catch(function(timeout) {location.reload()});
            }
        });
    });
</script>
@endsection