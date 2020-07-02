<div class="form-group">
    {!! Form::label('nominalubah', 'Nominal:') !!}
    {!! Form::text('nominalubah', null, ['class' => 'form-control inputanangka', 'placeholder' => 'Masukan Uang Modal Kasir', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('tanggalubah', 'Tanggal :') !!}
    {!! Form::date('tanggalubah', null, ['class' => 'form-control', 'required']) !!}
</div>