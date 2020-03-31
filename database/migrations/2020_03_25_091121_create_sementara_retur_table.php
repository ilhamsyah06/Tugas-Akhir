<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSementaraReturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sementara_retur', function (Blueprint $table) {
            $table->increments('id');
            $table->string('noretur');
            $table->string('kode');
            $table->integer('harga')->unsigned();
            $table->integer('jumlah')->unsigned();
            $table->integer('diskon')->nullable()->default(0);
            $table->integer('barang_id')->unsigned();
            $table->foreign('barang_id')->references('id')->on('barang')->onUpdate('restrict')->onDelete('restrict');
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
        Schema::dropIfExists('sementara_retur');
    }
}
