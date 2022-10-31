<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->bigIncrements('peg_id');

            $table->bigInteger('peg_atasan_id')->nullable();
            // ==========================================================================================================
            $table->unsignedBigInteger('jenis_jabatan_id')->nullable();
            $table->foreign('jenis_jabatan_id')->references('jenis_jabatan_id')->on('jenis_jabatan');
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->foreign('jabatan_id')->references('jabatan_id')->on('jabatan');
            $table->unsignedBigInteger('jenjang_jabatan_id')->nullable();
            $table->foreign('jenjang_jabatan_id')->references('jenjang_jabatan_id')->on('jenjang_jabatan');
            $table->unsignedBigInteger('unit_kerja_id')->nullable();
            $table->foreign('unit_kerja_id')->references('unit_kerja_id')->on('unit_kerja');
            $table->unsignedBigInteger('spesialis_id')->nullable();
            $table->foreign('spesialis_id')->references('spesialis_id')->on('spesialis');
            $table->unsignedBigInteger('golongan_id')->nullable();
            $table->foreign('golongan_id')->references('golongan_id')->on('golongan');
            $table->unsignedBigInteger('kualifikasi_pendidikan_id')->nullable();
            $table->foreign('kualifikasi_pendidikan_id')->references('kualifikasi_pendidikan_id')->on('kualifikasi_pendidikan');
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->foreign('bidang_id')->references('bidang_id')->on('bidang');
            $table->unsignedBigInteger('jenis_tenaga_kerja_id')->nullable();
            $table->foreign('jenis_tenaga_kerja_id')->references('jenis_tenaga_kerja_id')->on('jenis_tenaga_kerja');
            $table->unsignedBigInteger('status_kepegawaian_id')->nullable();
            $table->foreign('status_kepegawaian_id')->references('status_kepegawaian_id')->on('status_pegawai');
            $table->unsignedBigInteger('pendidikan_id')->nullable();
            $table->foreign('pendidikan_id')->references('pendidikan_id')->on('pendidikan');
            $table->unsignedBigInteger('str_id')->nullable();
            $table->foreign('str_id')->references('str_id')->on('str');
            $table->unsignedBigInteger('sip_id')->nullable();
            $table->foreign('sip_id')->references('sip_id')->on('sip');
            $table->unsignedBigInteger('ijazah_id')->nullable();
            $table->foreign('ijazah_id')->references('ijazah_id')->on('ijazah');
            // ==========================================================================================================
            $table->string('peg_nama_lengkap')->nullable();
            $table->string('peg_gelar_depan')->nullable();
            $table->string('peg_gelar_belakang')->nullable();
            $table->string('peg_nama_tanpa_gelar')->nullable();
            $table->string('peg_almamater_nama')->nullable();
            $table->string('peg_jenis_kelamin')->nullable();
            $table->string('peg_lahir_tempat')->nullable();
            $table->date('peg_lahir_tanggal')->nullable();
            $table->string('peg_agama')->nullable();
            $table->string('peg_golongan_darah')->nullable();
            $table->string('peg_nip_nipt_nik')->nullable();
            $table->float('peg_usia')->nullable();
            $table->string('peg_bpjs')->nullable();
            $table->string('peg_bpjs_ketenagakerjaan')->nullable();
            $table->string('peg_nomor_rekening')->nullable();
            $table->string('peg_npwp')->nullable();
            $table->string('peg_ktp')->nullable();
            $table->string('peg_no_kk')->nullable();
            $table->float('peg_masa_kerja')->nullable();
            $table->date('peg_tmt')->nullable();
            $table->date('peg_tmt_golongan_akhir')->nullable();
            $table->string('peg_status_kerja')->nullable();
            $table->string('peg_status_pernikahan')->nullable();
            $table->string('peg_sk_pengangkatan')->nullable();
            $table->string('peg_telp_hp')->nullable();
            $table->string('peg_email')->nullable();
            $table->string('peg_rumah_alamat')->nullable();
            $table->string('peg_nama_provinsi')->nullable();
            $table->string('peg_nama_kota')->nullable();
            $table->string('peg_nama_kec')->nullable();
            $table->string('peg_kel_desa')->nullable();
            $table->string('peg_alamat_rw')->nullable();
            $table->string('peg_alamat_rt')->nullable();
            $table->string('peg_kodepos')->nullable();
            $table->string('peg_ktp_rumah_alamat')->nullable();
            $table->string('peg_ktp_nama_provinsi')->nullable();
            $table->string('peg_ktp_nama_kota')->nullable();
            $table->string('peg_ktp_nama_kec')->nullable();
            $table->string('peg_ktp_kel_desa')->nullable();
            $table->string('peg_ktp_alamat_rw')->nullable();
            $table->string('peg_ktp_alamat_rt')->nullable();
            $table->string('peg_ktp_kodepos')->nullable();

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
        Schema::dropIfExists('pegawai');
    }
}
