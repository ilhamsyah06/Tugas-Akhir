@extends('layouts.master')

@section('title', 'Tampil Barcode')

@section('style')
<style type="text/css" media="print">
    @page {
        size: landscape;
    }
</style>
@endsection

@section('content')
<div class="container">
<div class="row">
    <table width="100%">
        <tr>
            @for($i=1; $i <= $qty; $i++) <td align="center" style="border:1px solid #ccc">
                <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($kode, 'C128',1,20)}}">
                <br>{{$kode }}
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