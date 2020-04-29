<!DOCTYPE html>
<html lang="in">
<head>
    <meta charset="utf-8">

    <title>Laporan Detail Retur Barang</title>
</head>

<body>

<style type="text/css">

    .tg  {border-collapse:collapse;border-spacing:0;border-color:#ccc;width: 100%; }
    .tg td{font-family:Arial;font-size:12px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;}
    .tg th{font-family:Arial;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#f0f0f0;}
    .tg .tg-3wr7{font-weight:bold;font-size:12px;font-family:"Arial", Helvetica, sans-serif !important;;text-align:center}
    .tg .tg-ti5e{font-size:10px;font-family:"Arial", Helvetica, sans-serif !important;;text-align:center}
    .tg .tg-rv4w{font-size:10px;font-family:"Arial", Helvetica, sans-serif !important;}

    .tg .tg-3wr7-detail{font-weight:bold;font-size:10px;font-family:"Arial", Helvetica, sans-serif !important;;text-align:center}
    .tg .tg-rv4w-detail{font-size:9px;font-family:"Arial", Helvetica, sans-serif !important;}
    .tg td.tg-rv4w-detail{font-family:Arial;font-size:9px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color: #fafafa;}
    .tg th.tg-3wr7-detail{font-family:Arial;font-size:10px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color: #b7b7b7;}


    body {
        font-family: sans-serif;
        font-size: 10pt;
        margin: 0.5cm 0;
        text-align: justify;
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
    #title .title-header-4 {
        text-align: center;
        margin-top: 0px;
        font-weight: normal;
        font-size: 10px;
        font-style: italic;
    }

    #title .title-header-alamat {
        text-align: center;
        margin-top: 0px;
        font-weight: normal;
        font-size: 11pt;
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

    /*#header,*/
    /*#footer {*/
    /*position: fixed;*/
    /*left: 0;*/
    /*right: 0;*/
    /*color: #aaa;*/
    /*font-size: 0.9em;*/
    /*}*/

    #footer {
        position: fixed;
        left: 0;
        right: 0;
        color: #aaa;
        font-size: 0.9em;
    }

    /*#header {*/
    /*top: 0;*/
    /*border-bottom: 0.1pt solid #aaa;*/
    /*}*/

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

    .page-number {
        text-align: right;
    }

    .page-number:before {
        content: "Halaman " counter(page);
    }

    hr {
        page-break-after: always;
        border: 0;
    }

    .garis-dua {
        border-top: 0.1pt solid #aaa;
    }
    .garis-satu {
        border-top: 3pt solid #000;
    }

    .logo-img {
        top: 12px;
    }

    .center {
        text-align: center;
    }
    .left {
        text-align: left;
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
            <td class="judul_laporan">LAPORAN DETAIL RETUR BARANG</td>
        </tr>
        <tr>
            <td class="subjudul_laporan">{{ $periode }}</td>
        </tr>
    </table>
</div>

<div id="footer">
    <table>
        <tr>
            <td>Aplikasi POS ANDIKA</td>
            <td style="text-align: right;" class="page-number"></td>
        </tr>
    </table>
</div>

<table id="dataTableBuilder" class="table table-striped table-bordered table-hover display select tg"
       cellspacing="0" width="100%" border="1" style="margin-top: 10px;">
    <thead>
    <tr>
        <th style="width: 5%" class="tg-3wr7"><strong>#</strong></th>
        <th style="width:9%;" class="tg-3wr7"><strong>No. Retur</strong></th>
        <th style="width:9%;" class="tg-3wr7"><strong>No. Invoice</strong></th>
        <th class="tg-3wr7" style="width: 13%"><strong>Tanggal</strong></th>
        <th class="tg-3wr7"><strong>Operator</strong></th>
        <th style="text-align: center;" class="tg-3wr7"><strong>Total</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($retur as $i => $value)
        <tr>
            <td style="width: 5%; text-align: center;" class="tg-rv4w">{{ $i+1 }}</td>
            <td style="width:9%; text-align: center;" class="tg-rv4w">{{ $value->no_retur }}</td>
            <td style="width:9%; text-align: center;" class="tg-rv4w">{{ $value->penjualan->no_invoice }}</td>
            <td style="width:13%; text-align: center;" class="tg-rv4w">{{ $value->created_at->format('d/m/Y H:i:s') }}</td>
            <td style="width:9%; text-align: center;" class="tg-rv4w">{{ $value->user->name }}</td>
            <td style="width: 9%; text-align: right;" class="tg-rv4w">{{ number_format($value->total(), 0, ',', '.') }}</td>
        </tr>

        <tr>
            <th style="text-align: center;" class="tg-3wr7-detail"></th>
            <th style="text-align: center;"  class="tg-3wr7-detail"><strong>Barang</strong></th>
            <th style="text-align: center;" class="tg-3wr7-detail"><strong>QTY</strong></th>
            <th style="text-align: center; width: 10%;" class="tg-3wr7-detail"><strong>Harga Jual</strong></th>
            <th style="text-align: center; width: 10%;" class="tg-3wr7-detail"><strong>Diskon Item</strong></th>
            <th style="text-align: center;" class="tg-3wr7-detail"><strong>Sub Total</strong></th>
        </tr>

        @foreach($value->detailretur as $detail)
            <tr>
                <td style="text-align: center;" class="tg-rv4w-detail"></td>
                <td class="tg-rv4w-detail">{{ $detail->barang->kode.' - '.$detail->barang->nama_barang }}</td>
                <td style="text-align: center;" class="tg-rv4w-detail">{{ $detail->qty }}</td>
                <td style="text-align: right;" class="tg-rv4w-detail">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td style="text-align: right;" class="tg-rv4w-detail">{{ number_format($detail->diskon_item, 0, ',', '.') }}</td>
                <td style="text-align: right;" class="tg-rv4w-detail">{{ number_format($detail->harga * $detail->qty, 0, ',', '.') }}</td>
            </tr>
        @endforeach


    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5" class="tg-3wr7">Total Transaksi</th>
        <th style="width: 9%; text-align: right;" class="tg-3wr7">{{ number_format($total, 0, ',', '.') }}</th>
    </tr>
    </tfoot>
</table>



</body>
</html>