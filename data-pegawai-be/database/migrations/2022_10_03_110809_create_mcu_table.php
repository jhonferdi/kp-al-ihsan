<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMcuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_mcu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penyakit')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('order')->nullable()->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('mcu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->unsignedBigInteger('master_mcu_id')->nullable();
            $table->foreign('master_mcu_id')->references('id')->on('master_mcu');
            $table->string('status')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('tahun_mcu')->nullable();
            $table->string('jenis_pemeriksaan')->nullable();
            $table->date('tanggal_vaksin')->nullable();
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
        Schema::dropIfExists('mcu');
    }
}
