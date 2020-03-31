<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $table = 'retur';

    protected $fillable = ['no_retur','penjualan_id','tgl_retur','user_id'];
}
