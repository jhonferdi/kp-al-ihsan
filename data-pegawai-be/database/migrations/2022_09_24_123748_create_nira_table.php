<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNiraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nira', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peg_id')->nullable();
            $table->foreign('peg_id')->references('peg_id')->on('pegawai');
            $table->string('nomor_nira')->nullable();
            $table->date('tanggal_terdaftar')->nullable();
            $table->date('tanggal_terbit')->nullable();
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
        Schema::dropIfExists('nira');
    }
}
