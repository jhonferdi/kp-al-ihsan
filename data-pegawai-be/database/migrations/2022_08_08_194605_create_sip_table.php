<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sip', function (Blueprint $table) {
            $table->bigIncrements('sip_id');

            $table->string('nama_pegawai')->nullable();
            $table->string('nomor_sip')->nullable();
            $table->date('tanggal_kadaluarsa_sip')->nullable();
            $table->integer('status_sip')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->string('jenis_sip')->nullable();

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
        Schema::dropIfExists('sip');
    }
}
