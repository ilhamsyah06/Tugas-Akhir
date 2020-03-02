<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','alamat','nomorhp','level','last_login_at','last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    
    public function supplier() {
        return $this->hasMany('App\Supplier');
    }

    public function barang() {
        return $this->hasMany('App\Barang');
    }

    public function modalkasir() {
        return $this->hasMany('App\Uang_modal_kasir');
    }

    public function absen()
    {
        return $this->hasMany('App\Absen');
    }
}
