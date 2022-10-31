<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatKenaikanGajiBerkalaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_kenaikan_gaji_berkala', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->string('tahun')->nullable();
            $table->string('bulan')->nullable();
            $table->string('golongan')->nullable();
            $table->string('masa_kerja_tahun')->nullable();
            $table->string('masa_kerja_bulan')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('riwayat_kenaikan_gaji_berkala');
    }
}
