<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->bigIncrements('jabatan_id');

            $table->string('jabatan_nama')->nullable();
            $table->unsignedBigInteger('jabatan_fungsional_id')->nullable();
            $table->foreign('jabatan_fungsional_id')->references('jabatan_fungsional_id')->on('jabatan_fungsional');
            $table->bigInteger('unit_kerja_id')->nullable();

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
        Schema::dropIfExists('jabatan');
    }
}
