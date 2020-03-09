<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    protected $table = 'gaji';

    protected $fillable = ['jumlah_hari_kerja','total_gaji','tgl_gajian','user_id','nominal_id'];

    public function nominal() {
        return $this->belongsTo('App\NominalGaji');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
