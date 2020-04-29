<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = ['kode','nama_barang','user_id','kategori_id','harga_beli','harga_jual','profit','stok','tanggal','status'];

    public function kategori() {
        return $this->belongsTo('App\Kategori');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function sementara(){
        return $this->hasMany('App\Sementara');
    }

    public function sementararetur(){
        return $this->hasMany('App\SementaraRetur');
    }

    public function historiharga()
    {
        return $this->hasMany('App\Historiharga');
    }

    public function pembeliandetail(){
        return $this->hasMany('App\Detailpembelian');
    }

    public function history() {
    	return $this->hasMany('App\History');
    }

    public function opname() {
    	return $this->hasMany('App\Opname');
    }

    public function hilang() {
    	return $this->hasMany('App\Hilang');
    }

}
