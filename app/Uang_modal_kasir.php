<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uang_modal_kasir extends Model
{
    protected $table = 'uang_modal_kasir';

    protected $fillable = ['uang_awal','uang_akhir','tanggal','user_id'];

    protected $dates = ['tanggal'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
