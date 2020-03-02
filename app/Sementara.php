<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sementara extends Model
{
    protected $table = 'sementara';

    protected $fillable = ['kode','harga','jumlah','barang_id','diskon'];

    public function barang(){
        return $this->belongsTo('App\Barang');
    }
}
