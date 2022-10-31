<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPendidikanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('tingkat_pendidikan')->nullable();
            $table->integer('order')->nullable()->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('riwayat_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->unsignedBigInteger('master_pendidikan_id')->nullable();
            $table->foreign('master_pendidikan_id')->references('id')->on('master_pendidikan');
            $table->string('no_ijazah')->nullable();
            $table->date('tanggal_ijazah')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->date('tanggal_lulus')->nullable();
            $table->string('pejabat_penandatangan_ijazah')->nullable();
            $table->string('surat_izin_belajar')->nullable();
            $table->string('pejabat_penandatangan_izin_belajar')->nullable();
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
        Schema::dropIfExists('riwayat_pendidikan');
    }
}
