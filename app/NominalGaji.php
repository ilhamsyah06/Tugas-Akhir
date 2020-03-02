<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NominalGaji extends Model
{
    protected $table = 'nominal_gaji';

    protected $fillable = ['nominal'];
}
