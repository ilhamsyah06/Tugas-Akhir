<div class="form-group">
    {!! Form::label('namaubah', 'Nama') !!}
    {!! Form::text('namaubah', null, ['class' => 'form-control', 'placeholder' => 'Masukan Nama Supplier']) !!}
</div>

<div class="form-group">
    {!! Form::label('emailubah', 'Email') !!}
    {!! Form::text('emailubah', null, ['class' => 'form-control', 'placeholder' => 'Masukan Email Supplier', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('alamatubah', 'Alamat') !!}
    {!! Form::textarea('alamatubah', null, ['rows' => 4, 'cols' => 77, 'style' => 'resize:none']) !!}
</div>

<div class="form-group">
    {!! Form::label('nomorubah', 'Nomor Telephone/Wa') !!}
    {!! Form::text('nomorubah', null, ['class' => 'form-control', 'placeholder' => 'Masukan Nomor Supplier', 'required']) !!}
</div>