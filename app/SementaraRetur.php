<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SementaraRetur extends Model
{
    protected $table = 'sementara_retur';

    protected $fillable = ['noretur','kode','harga','jumlah','barang_id','diskon'];

    public function barang(){
        return $this->belongsTo('App\Barang');
    }
}
