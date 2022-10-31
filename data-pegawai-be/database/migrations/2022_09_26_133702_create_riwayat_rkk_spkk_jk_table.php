<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatRkkSpkkJkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_rkk_spkk_jk', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_rkk_spkk_jk')->nullable();
            $table->integer('order')->nullable()->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('riwayat_rkk_spkk_jk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->unsignedBigInteger('master_rkk_spkk_jk_id')->nullable();
            $table->foreign('master_rkk_spkk_jk_id')->references('id')->on('master_rkk_spkk_jk');
            $table->string('bulan')->nullable();
            $table->string('tahun')->nullable();
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
        Schema::dropIfExists('master_rkk_spkk_jk');
        Schema::dropIfExists('riwayat_rkk_spkk_jk');
    }
}
