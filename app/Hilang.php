<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hilang extends Model
{
    protected $table = 'hilang';

    protected $fillable = ['id', 'barang_id', 'jumlah'];

    public function barang() {
    	return $this->belongsTo('App\Barang', 'barang_id');
    }

    public function opname() {
    	return $this->hasOne("App\Opname", 'id');
    }
}
