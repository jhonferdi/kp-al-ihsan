<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenDigitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_digital', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->bigInteger('entity_id')->nullable();
            $table->string('file_nama');
            $table->string('file_path');
            $table->string('file_url');
            $table->unsignedBigInteger('master_dokumen_digital_id')->nullable();
            $table->foreign('master_dokumen_digital_id')->references('id')->on('master_dokumen_digital');
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
        Schema::dropIfExists('dokumen_digital');
    }
}
