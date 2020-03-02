<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';

    protected $fillable = ['nama','kode','tgl', 'barang_id', 'stok', 'masuk', 'keluar', 'saldo', 'user_id', 'keterangan'];

    protected $dates = array('tgl');

	public function barang() {
    	return $this->belongsTo('App\Barang');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
