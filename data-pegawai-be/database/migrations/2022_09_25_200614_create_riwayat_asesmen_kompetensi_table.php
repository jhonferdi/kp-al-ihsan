<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatAsesmenKompetensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_asesmen_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_asesmen_kompetensi')->nullable();
            $table->integer('order')->nullable()->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('riwayat_asesmen_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->unsignedBigInteger('master_asesmen_kompetensi_id')->nullable();
            $table->foreign('master_asesmen_kompetensi_id')->references('id')->on('master_asesmen_kompetensi');
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
        Schema::dropIfExists('master_asesmen_kompetensi');
        Schema::dropIfExists('riwayat_asesmen_kompetensi');
    }
}
