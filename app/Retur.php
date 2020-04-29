<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $table = 'retur';
    protected $fillable = ['no_retur','penjualan_id','tgl_retur','user_id'];

    public function detailretur()
    {
        return $this->hasMany('App\DetailRetur');
    }

    public function total() {
        $details = $this->detailretur;

        $t = 0;
        foreach ($details as $key => $value) {
            $t += ($value->qty * $value->harga);
        }

        return $t;
    }

    public function penjualan()
    {
        return $this->belongsTo('App\Penjualan');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
