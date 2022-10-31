<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatDiklatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_diklat', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_pelatihan')->nullable();
            $table->string('jenis_pelatihan')->nullable();
            $table->integer('order')->nullable()->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('riwayat_diklat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->unsignedBigInteger('master_diklat_id')->nullable();
            $table->foreign('master_diklat_id')->references('id')->on('master_diklat');
            $table->string('nama_pelatihan')->nullable();
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->string('lama_pelatihan')->nullable();
            $table->string('pejabat_penandatangan')->nullable();
            $table->string('nomor_sertifikat')->nullable();
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
        Schema::dropIfExists('master_diklat');
        Schema::dropIfExists('riwayat_diklat');
    }
}
