<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';

    protected $fillable = ['kode','supplier_id','tgl_pembelian','total_bayar','user_id'];

    protected $dates = array('tgl_pembelian');

    public function pembeliandetail(){
        return $this->hasMany('App\Detailpembelian');
    }
    
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function supplier() {
        return $this->belongsTo('App\Supplier');
    }
}
