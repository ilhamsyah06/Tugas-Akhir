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
}
