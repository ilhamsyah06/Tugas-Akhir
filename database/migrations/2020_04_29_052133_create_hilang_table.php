<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHilangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hilang', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->foreign('id')->references('id')->on('opname')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('barang_id')->unsigned();
            $table->foreign('barang_id')->references('id')->on('barang')->onUpdate('restrict')->onDelete('restrict');
            $table->integer('jumlah')->unsigned()->default(0);
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
        Schema::dropIfExists('hilang');
    }
}
