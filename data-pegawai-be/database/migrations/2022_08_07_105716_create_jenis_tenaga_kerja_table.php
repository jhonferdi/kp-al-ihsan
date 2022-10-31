<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisTenagaKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_tenaga_kerja', function (Blueprint $table) {
            $table->bigIncrements('jenis_tenaga_kerja_id');

            $table->unsignedBigInteger('tenaga_kerja_id')->nullable();
            $table->foreign('tenaga_kerja_id')->references('tenaga_kerja_id')->on('tenaga_kerja');
            $table->string('jenis_tenaga_kerja_nama')->nullable();

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
        Schema::dropIfExists('jenis_tenaga_kerja');
    }
}
