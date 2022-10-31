<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterDokumenDigitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_dokumen_digital', function (Blueprint $table) {
            $table->id();
            $table->string('file_nama');
            $table->string('keterangan')->nullable();
            $table->string('logic_show')->nullable();
            $table->boolean('is_wajib')->default(false);
            $table->integer('order')->default(0);
            $table->string('relation_to')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->string('accept_extension')->default('application/pdf');
            $table->integer('max_size_kb')->default(2048);
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
        Schema::dropIfExists('master_dokumen_digital');
    }
}
