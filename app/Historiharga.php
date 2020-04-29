<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historiharga extends Model
{
    protected $table = 'histori_harga';

    protected $fillable = ['harga_terakhir','harga_naik','barang_id'];

    public function barang() {
        return $this->belongsTo('App\Barang');
    }
}
