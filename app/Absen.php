<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $table = 'absen';

    protected $fillable = ['user_id','tgl_absen','jam_absen'];

    public function user() {
        return $this->belongsTo('App\User');
    }

}
