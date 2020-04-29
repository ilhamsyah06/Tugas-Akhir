<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailRetur extends Model
{
    protected $table = 'retur_detail';

    protected $fillable = ['retur_id','barang_id','harga','qty','diskon_item','total'];
    
    public function retur(){
        return $this->belongsTo('App\Retur');
    }

    public function barang(){
        return $this->belongsTo('App\Barang');
    }
}
