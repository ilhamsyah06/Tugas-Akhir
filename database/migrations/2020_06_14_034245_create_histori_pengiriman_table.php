<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriPengirimanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histori_pengiriman', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('barang_id')->unsigned();
            $table->foreign('barang_id')->references('id')->on('barang')->onUpdate('restrict')->onDelete('restrict');
            $table->integer('stok_dikirim');
            $table->date('tgl_pengiriman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histori_pengiriman');
    }
}
