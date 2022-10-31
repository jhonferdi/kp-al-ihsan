<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('str', function (Blueprint $table) {
            $table->bigIncrements('str_id');

            $table->string('nama_pegawai')->nullable();
            $table->string('nomor_str')->nullable();
            $table->date('tanggal_kadaluarsa_str')->nullable();
            $table->integer('status_str')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->string('jenis_str')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('str');
    }
}
