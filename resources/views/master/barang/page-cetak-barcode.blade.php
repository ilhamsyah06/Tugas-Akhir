@extends('layouts.master')

@section('title','Barcode')
@section('style')
<style type="text/css" media="print">
    @page {
        size: landscape;
    }
</style>
@endsection
@section('content')
@php
    $no = 1;
    $kode = $_POST['kode'];
    $qty = $_POST['qty'];
    $harga = $_POST['harga'];
@endphp
<div class="container">
<div class="row">
    <table width="100%">
        <tr>
            
            @for($i=1; $i <= $qty; $i++) <td align="center" style="border:1px solid #ccc">
                <p class="price" style="margin-left: 0%; margin-right: 0%; margin-bottom: 0%; margin-top: 2%; font-size: 12px;">Harga: Rp.{{number_format($harga)}},-</p>
                <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($kode, 'C128',1,20)}}">
                <br><p style="font-size: 10px; margin:0%;">{{$kode}}</p>
                </td>
                @if ($no++ %5 ==0)
        </tr>
        <tr>
            @endif
            @endfor
        </tr>
    </table>
    <br>
    <div class="form-group">
        <a id="printpagebutton" type="button" onclick="printpage()" class="btn btn-danger"><i class="fa fa-print"></i>
            Cetak Barcode</a>
    </div>
</div>
</div>
@endsection
@section('footer')
<script>
    function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        printButton.style.visibility = 'visible';
    }
</script>
@endsection