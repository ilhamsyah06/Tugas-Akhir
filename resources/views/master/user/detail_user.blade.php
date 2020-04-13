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
   <!-- <div class="col-md-6">
        <div class="form-group">
            <label for="">Note: Silahkan Tekan Tombol Dibawah Untuk Absen Hari Ini</label><br>
            <button name="absenhariini" id="absenhariini" class="btn btn-primary btn-lg animated bounceIn"  @if ($count === 1 ) disabled @else  @endif><i class="fa fa-calendar-check-o"></i> ABSEN SEKARANG</button><br>
            @foreach ($ambildataabsen as $item)
            <span class="label label-warning"> Waktu Absen : {{ date('d-m-Y H:i:s', strtotime($item->created_at)) }}</span><br>
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
                    @if ($countgaji === 0)
                    <h3>Gaji Belum Dibayarkan</h3>                        
                    @endif
                    @foreach ($gaji as $item)
                    <h3 style="margin:5px;" class="animated fadeInRight">Nama : {{ $item->user->name}}</h3>
                    <h3 style="margin:5px;" class="animated fadeInRight">Total Gaji Dibayar Sebesar : Rp.{{ number_format($item->total_gaji) }},-</h3>
                    <span style="font-size: 15px;" class="label label-primary animated fadeInRight">Jumlah Hari Masuk : {{ $item->jumlah_hari_kerja }} Hari</span>
                    <span style="font-size: 15px;" class="label label-danger animated fadeInRight">Dibayar Tanggal : {{ date('d-m-Y', strtotime($item->tgl_gajian)) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>-->
</div>
@endsection

@section('footer')
<script>
  var alert = new Audio();
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
                alert.src = "/terimakasih.mp3";
                alert.play();
            return swal({
              type: 'success',
              title: 'Terima Kasih, Selamat Beraktifitas !!.',
              timer: 2000
          }).catch(function(timeout) {
            setTimeout(function(){
                window.location.href = window.location.href;
                  },3000)
          });
            }
        });
    });
</script>
@endsection