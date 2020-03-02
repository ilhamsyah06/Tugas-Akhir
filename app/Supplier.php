<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $fillable = ['nama_supplier','nomor_hp','email','alamat','user_id'];

    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function pembelian() {
    	return $this->hasMany('App\Pembelian');
    }
}
