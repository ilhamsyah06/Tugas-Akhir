<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailpenjualan extends Model
{
    protected $table = 'detail_penjualan';

    protected $fillable = ['penjualan_id','barang_id','harga','qty','diskon_item','total'];
    
    public function penjualan(){
        return $this->belongsTo('App\Penjualan');
    }

    public function barang(){
        return $this->belongsTo('App\Barang');
    }
}
