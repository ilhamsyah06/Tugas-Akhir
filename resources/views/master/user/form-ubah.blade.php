<div class="form-group">
    {!! Form::label('namaubah', 'Nama') !!}
    {!! Form::text('namaubah', null, ['class' => 'form-control', 'placeholder' => 'Masukan Nama Pengguna']) !!}
</div>

<div class="form-group">
    {!! Form::label('emailubah', 'Email') !!}
    {!! Form::text('emailubah', null, ['class' => 'form-control', 'placeholder' => 'Masukan Email Pengguna', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('alamatubah', 'Alamat') !!}
    {!! Form::textarea('alamatubah', null, ['rows' => 4, 'cols' => 77, 'style' => 'resize:none']) !!}
</div>

<div class="form-group">
    {!! Form::label('nomorubah', 'Nomor Telephone/Wa') !!}
    {!! Form::text('nomorubah', null, ['class' => 'form-control', 'placeholder' => 'Masukan Nomor Pengguna', 'required']) !!}
</div>

<div class="form-group"> 
    {!! Form::label('levelubah', 'Level') !!}
    {!! Form::select('levelubah', array('' => '-- Pilih Level --', 'admin' => 'Admin', 'kasir' => 'Kasir', 'gudang' => 'Gudang'), null, ['class' => 'form-control']); !!}
</div>

<div class="checkbox">
    <label>
        <input type="checkbox" name="ubahkatasandi" id="ubahkatasandi" value="" checked><strong class="f_warning">Mengubah Password</strong>
    </label>
</div>

<div id="divSandi" class="form-group hidden">
    {!! Form::label('passwordubah', 'Password Baru') !!} <br/>
    <div id="divSandi" class="form-group input-group">
        {!! Form::text('passwordubah', null, ['class' => 'form-control', 'placeholder' => 'Masukan Password']) !!}
        <span class="input-group-btn">
            <a class="btn btn-flat btn-default" id="katasandiautoubah">
                <i class="fa fa-magic"> Otomatis</i>
            </a>
        </span>
    </div>
</div>
