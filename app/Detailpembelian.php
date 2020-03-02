<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailpembelian extends Model
{
    protected $table = 'detail_pembelian';

    protected $fillable = ['pembelian_id','barang_id','qty','harga','total'];

    public function pembelian(){
        return $this->belongsTo('App\Pembelian');
    }

    public function barang(){
        return $this->belongsTo('App\Barang');
    }
}
