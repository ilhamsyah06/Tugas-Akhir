<!DOCTYPE html>
<html lang="in">
<head>
    <meta charset="utf-8">

    <title>Laporan Rekapitulasi Rugi/Laba</title>
</head>

<body>

<style type="text/css">

    body {
        font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
        font-weight: 400;
        /*font-family: sans-serif;*/
        font-size: 10pt;
        margin: 0.5cm 0;
        text-align: justify;
    }

    body {
        box-sizing: border-box;
        -webkit-font-smoothing: antialiased;
        font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
        font-weight: 400;
        font-size: 12px;
        line-height: 1.42857143;
        color: #333;
    }

    #title {
        width: 100%;
        text-align: center;
        margin-top: 0px;
        padding: 0px;
    }
    #title .title-header {
        text-align: center;
        margin-top: 0px;
        font-weight: bold;
        font-size: 12pt;
    }
    #title .title-header-2 {
        text-align: center;
        margin-top: 0px;
        font-weight: bold;
        font-size: 13pt;
    }
    #title .title-header-3 {
        text-align: center;
        margin-top: 0px;
        font-weight: bold;
        font-size: 20px;
    }
    #title .title-header-alamat {
        text-align: center;
        margin-top: 0px;
        font-weight: normal;
        font-size: 11pt;
        font-style: italic;
    }
    #title .title-header-4 {
        text-align: center;
        margin-top: 0px;
        font-weight: normal;
        font-size: 10px;
        font-style: italic;
    }

    #header .judul_laporan {
        text-align: center;
        margin-top: 0px;
        font-weight: bold;
        font-size: 18px;
    }
    #header .subjudul_laporan {
        text-align: center;
        margin-top: 0px;
        font-weight: bold;
        font-size: 14px;
    }

    #footer {
        position: fixed;
        left: 0;
        right: 0;
        color: #aaa;
        font-size: 0.9em;
    }

    #footer {
        bottom: 0;
        border-top: 0.1pt solid #aaa;
    }

    #footer table {
        width: 100%;
        border-collapse: collapse;
        border: none;
    }

    #footer td {
        padding: 0;
        width: 50%;
    }

   /* .page-number {
        text-align: right;
    }*/

    /*.page-number:before {
        content: "Halaman " counter(page);
    }*/

    /*hr {
        page-break-after: always;
        border: 0;
    }*/

    .garis-dua {
        border-top: 0.1pt solid #aaa;
    }
    .garis-satu {
        border-top: 3pt solid #000;
    }

    .center {
        text-align: center;
    }
    .left {
        text-align: left;
    }

    .row {
        margin-right: -15px;
        margin-left: -15px;
    }

    .btn-group-vertical>.btn-group:after, .btn-group-vertical>.btn-group:before, .btn-toolbar:after, .btn-toolbar:before, .clearfix:after, .clearfix:before, .container-fluid:after, .container-fluid:before, .container:after, .container:before, .dl-horizontal dd:after, .dl-horizontal dd:before, .form-horizontal .form-group:after, .form-horizontal .form-group:before, .modal-footer:after, .modal-footer:before, .modal-header:after, .modal-header:before, .nav:after, .nav:before, .navbar-collapse:after, .navbar-collapse:before, .navbar-header:after, .navbar-header:before, .navbar:after, .navbar:before, .pager:after, .pager:before, .panel-body:after, .panel-body:before, .row:after, .row:before {
        display: table;
        content: " ";
    }

    .btn-group-vertical>.btn-group:after, .btn-toolbar:after, .clearfix:after, .container-fluid:after, .container:after, .dl-horizontal dd:after, .form-horizontal .form-group:after, .modal-footer:after, .modal-header:after, .nav:after, .navbar-collapse:after, .navbar-header:after, .navbar:after, .pager:after, .panel-body:after, .row:after {
        clear: both;
    }

    .btn-group-vertical>.btn-group:after, .btn-group-vertical>.btn-group:before, .btn-toolbar:after, .btn-toolbar:before, .clearfix:after, .clearfix:before, .container-fluid:after, .container-fluid:before, .container:after, .container:before, .dl-horizontal dd:after, .dl-horizontal dd:before, .form-horizontal .form-group:after, .form-horizontal .form-group:before, .modal-footer:after, .modal-footer:before, .modal-header:after, .modal-header:before, .nav:after, .nav:before, .navbar-collapse:after, .navbar-collapse:before, .navbar-header:after, .navbar-header:before, .navbar:after, .navbar:before, .pager:after, .pager:before, .panel-body:after, .panel-body:before, .row:after, .row:before {
        display: table;
        content: " ";
    }

    .col-xs-6 {
        width: 42%;
    }

    .col-xs-12 {
        width: 100%;
    }

    .col-xs-6, .col-xs-12 {
        float: left;
    }

    .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }

    p {
        display: block;
        -webkit-margin-before: 1em;
        -webkit-margin-after: 1em;
        -webkit-margin-start: 0px;
        -webkit-margin-end: 0px;
    }

    p {
        margin: 0 0 10px;
    }

    .lead {
        margin-bottom: 20px;
        font-size: 21px;
        font-weight: 300;
        line-height: 1.4;
    }

    .table-responsive {
        min-height: .01%;
        overflow-x: auto;
    }

    table {
        display: table;
        border-collapse: separate;
        border-spacing: 2px;
        border-color: grey;
    }

    table {
        border-spacing: 0;
        border-collapse: collapse;
    }

    table {
        background-color: transparent;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }

    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }

    tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }

    td, th {
        display: table-cell;
        vertical-align: inherit;
    }

    th {
        font-weight: bold;
        text-align: -internal-center;
    }

    th {
        text-align: left;
    }

    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;
        /*border-top: 1px solid #ddd;*/
    }

    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        /*border-top: 1px solid #f4f4f4;*/
    }

    div {
        display: block;
    }

    .box-body {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        padding: 10px;
    }

    .kepada {
        padding-top: 0px;
        padding-bottom: 0px;
    }

    .pengesah .nama {
        font-weight: bold;
    }

</style>

<div id="title">

    <table width="100%" cellpadding="0">
        <tr>
            <td class="title-header-3">TOKO ANDIKA</td>
        </tr>
        <tr>
            <td class="title-header-alamat">Bogem - Gurah. HP: 085856886313</td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 10px">
        <tr>
            <td class="garis-satu"></td>
        </tr>
        <tr>
            <td class="garis-dua"></td>
        </tr>
    </table>
</div>

<div id="header">
    <table width="100%" style="margin-top: 10px">
        <tr>
            <td class="judul_laporan">LAPORAN REKAPITULASI RUGI LABA</td>
        </tr>
        <tr>
            <td class="subjudul_laporan">{{ $periode }}</td>
        </tr>
    </table>
</div>

<div id="footer">
    <table>
        <tr>
            <td>Aplikasi Manajemen Penjualan</td>
        </tr>
    </table>
</div>

<br />

<div class="box-body">
<div class="row">
    <div class="col-xs-6">
        <p class="lead">Pendapatan :</p>

        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th style="width:50%">Penjualan Kotor:</th>
                    <td style="text-align: right;">{{ number_format($penjualan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td class="lead" style="text-align: right;"><strong>{{ number_format($pendapatan, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-xs-6">
        <p class="lead">Pengeluaran :</p>

        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th style="width:50%">Pembelian Barang :</th>
                    <td style="text-align: right;">{{ number_format($pembelian, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td class="lead" style="text-align: right;"><strong> {{ number_format($pengeluaran, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-xs-12">
        <table>
            <tr>
                <th style="width:50%" class="lead">Grand Total Keuntungan :</th>
                <td></td>
                <td class="lead" style="text-align: right; color:red;"><strong> {{ number_format($grandtotal, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>
</div>

</div>



</body>
</html>