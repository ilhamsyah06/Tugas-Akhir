<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historipengiriman extends Model
{
    protected $table = 'histori_pengiriman';

    protected $fillable = ['barang_id','stok_dikirim'];

    public function barang() {
    	return $this->belongsTo('App\Barang');
    }
}
