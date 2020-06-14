<?php

namespace App;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    public static function getBulanSaatIni()
    {
        $arrbulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $arrbulan[date('m')] . ' ' . date('Y');
    }

    public static function sisipkanNol($nilai, $panjang) {
        $kembali = '';

        for ($i = 0; $i < ($panjang - strlen($nilai)); $i++) {
            $kembali .= '0';
        }

        return $kembali.$nilai;
    }

    public static function getRealIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) //CHEK IP YANG DISHARE DARI INTERNET
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //UNTUK CEK IP DARI PROXY
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function kekata($x) {
        $x = abs($x);
        $angka = array("", "satu", "dua", "tiga", "empat", "lima",
        "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($x <12) {
            $temp = " ". $angka[$x];
        } else if ($x <20) {
            $temp = Utility::kekata($x - 10). " belas";
        } else if ($x <100) {
            $temp = Utility::kekata($x/10)." puluh". Utility::kekata($x % 10);
        } else if ($x <200) {
            $temp = " seratus" . Utility::kekata($x - 100);
        } else if ($x <1000) {
            $temp = Utility::kekata($x/100) . " ratus" . Utility::kekata($x % 100);
        } else if ($x <2000) {
            $temp = " seribu" . Utility::kekata($x - 1000);
        } else if ($x <1000000) {
            $temp = Utility::kekata($x/1000) . " ribu" . Utility::kekata($x % 1000);
        } else if ($x <1000000000) {
            $temp = Utility::kekata($x/1000000) . " juta" . Utility::kekata($x % 1000000);
        } else if ($x <1000000000000) {
            $temp = Utility::kekata($x/1000000000) . " milyar" . Utility::kekata(fmod($x,1000000000));
        } else if ($x <1000000000000000) {
            $temp = Utility::kekata($x/1000000000000) . " trilyun" . Utility::kekata(fmod($x,1000000000000));
        }     
            return $temp;
    }


    public static function terbilang($x, $style=4) {
        if($x<0) {
            $hasil = "minus ". trim(Utility::kekata($x));
        } else {
            $hasil = trim(Utility::kekata($x));
        }     

        $hasil .= ' rupiah';

        switch ($style) {
            case 1:
                $hasil = strtoupper($hasil);
                break;
            case 2:
                $hasil = strtolower($hasil);
                break;
            case 3:
                $hasil = ucwords($hasil);
                break;
            default:
                $hasil = ucfirst($hasil);
                break;
        }     
        return $hasil;
    }

    public static function getHargaPembulatan($nilai, $pembulat) {
        $hasil = (ceil($nilai) % $pembulat == 0) ? ceil($nilai) : round(($nilai + $pembulat / 2 ) / $pembulat) * $pembulat;
        return $hasil;
    }

    public static function tambahSpasi($str, $panjang, $order) {
        $kembali = "";
        for($i = 0; $i < $panjang - strlen($str); $i++) {
            $kembali .= " ";
        }   

        if ($order == 'front') {
            return $kembali.$str;
        } else {
            return $str.$kembali;
        }
    }

    public static function printStruk($kode) {
        $penjualan = Penjualan::where('no_invoice', $kode)->first();
        $diskon = Detailpenjualan::where('penjualan_id', $penjualan->id)->sum('diskon_item');
        $jumlah = Detailpenjualan::where('penjualan_id', $penjualan->id)->sum('qty');

                                        //SET NAMA PRINTER YANG SUDAH TERINSTAL
        $connector = new WindowsPrintConnector("POS-58");
        
        
        $printer = new Printer($connector);
        // $printer->initialize();

        $printer->text("           TOKO ANDIKA\n");
        $printer->text("           BOGEM GURAH\n");
        $printer->text("         HP 085856886313\n");
        $printer -> feed(1);
        $printer->text("Tanggal : ".$penjualan->tgl_penjualan->format('d/m/y')."\n");
        $printer->text("Nota    : ".$penjualan->no_invoice."\n");
        $printer->text("Kasir   : ".$penjualan->user->name."\n");
        $printer->text("================================\n");
        $printer->text(Utility::tambahSpasi("Nama", 5,'front')."  ".Utility::tambahSpasi("QTY", 7,'front')."  ".Utility::tambahSpasi("Harga", 6, 'front') ."  ".Utility::tambahSpasi("Total", 6, 'front')."\n");
        foreach ($penjualan->penjualandetail as $key => $value) {
            $printer->text(Utility::tambahSpasi(substr(strip_tags($value->barang->nama_barang), 0, 8), 5,'front')."  ".Utility::tambahSpasi($value->qty, 3,'front')."  ".Utility::tambahSpasi(number_format($value->harga, 0, ',', '.'), 7, 'front') ."  ".Utility::tambahSpasi(number_format($value->harga*$value->qty, 0, ',', '.'), 6, 'front')."\n");
        }
        $printer->text("--------------------------------\n");

        $totalpenjualan = $penjualan->total_bayar;
        $printer->text("Total   : ".Utility::tambahSpasi(number_format($totalpenjualan, 0, ',', '.'), 20, 'front')."\n");
        $printer->text("Bayar   : ".Utility::tambahSpasi(number_format($penjualan->jumlah_bayar, 0, ',', '.'), 20, 'front')."\n");
        $printer->text("Kembali : ".Utility::tambahSpasi(number_format($penjualan->jumlah_bayar-$totalpenjualan, 0, ',', '.'), 20, 'front')."\n");
        $printer->text("Total Diskon : ".Utility::tambahSpasi(number_format($diskon, 0, ',', '.'), 15, 'front')."\n");
        $printer->text("Jumlah Barang : ".Utility::tambahSpasi($jumlah, 14, 'front')."\n");

        $printer->text("--------------------------------\n");
        $printer->text("** Barang Yang Sudah Dibeli **\n");
        $printer->text("** Tidak Dapat Dikembalikan **\n");
        $printer->text("--------------------------------\n");
        $printer->text("Terima Kasih Atas Kunjungan Anda\n");
        
        $printer -> feed(1);
        $printer -> feed(1);
        $printer -> feed(1);
        $printer -> cut();
        $printer -> close();
        $printer->initialize();
    }
}
