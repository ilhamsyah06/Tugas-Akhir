<div class="form-group">
    {!! Form::label('nominalawallihat', 'Uang Awal :') !!}
    {!! Form::text('nominalawallihat', null, ['class' => 'form-control inputanangka', 'placeholder' => 'Masukan Uang Modal Kasir', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('nominalakhirlihat', 'Uang Akhir :') !!}
    {!! Form::text('nominalakhirlihat', null, ['class' => 'form-control inputanangka', 'placeholder' => 'Masukan Uang Modal Kasir', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('tanggallihat', 'Tanggal :') !!}
    {!! Form::date('tanggallihat', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('userinputlihat', 'Kasir :') !!}
    {!! Form::text('userinputlihat', null, ['class' => 'form-control', 'required','readonly']) !!}
</div>