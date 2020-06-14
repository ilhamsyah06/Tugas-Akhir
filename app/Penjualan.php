<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = ['no_invoice','tgl_penjualan','total_bayar','jumlah_bayar','kembalian'];

    protected $dates = array('tgl_penjualan');

    public function penjualandetail(){
        return $this->hasMany('App\Detailpenjualan');
    }
    
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function retur() {
        return $this->hasMany('App\Retur');
    }

    public function keuntungan() {
        $details = $this->penjualandetail;

        $t = 0;
        foreach ($details as $key => $value) {
            $t += ($value->qty * $value->harga) - ($value->qty * $value->harga_beli) - ($value->diskon_item);//profit
        }

        return $t;
    }

    public function totalpenjualan() {
        $details = $this->penjualandetail;

        $t = 0;
        foreach ($details as $key => $value) {
            $t += ($value->qty * $value->harga) - ($value->diskon_item);//profit
        }

        return $t;
    }


}
