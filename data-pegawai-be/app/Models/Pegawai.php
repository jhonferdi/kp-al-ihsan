<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use SoftDeletes;

    protected $table = 'pegawai';
    protected $guarded = ['peg_id'];
    protected $primaryKey = 'peg_id';


    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function actionLog()
    {
        return $this->hasMany(ActionLog::class, 'peg_id');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'golongan_id');
    }

    public function ijazah()
    {
        return $this->belongsTo(Ijazah::class, 'ijazah_id');
    }

    public function jenisJabatan()
    {
        return $this->belongsTo(JenisJabatan::class, 'jenis_jabatan_id');
    }

    public function kualifikasiPendidikan()
    {
        return $this->belongsTo(KualifikasiPendidikan::class, 'kualifikasi_pendidikan_id');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_id');
    }

    public function sip()
    {
        return $this->hasMany(Sip::class, 'peg_id');
    }

    public function statusPegawai()
    {
        return $this->belongsTo(StatusPegawai::class, 'status_kepegawaian_id');
    }

    public function str()
    {
        return $this->hasMany(Str::class, 'peg_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function jenisTenagaKerja()
    {
        return $this->belongsTo(JenisTenagaKerja::class, 'jenis_tenaga_kerja_id');
    }

    public function spesialis()
    {
        return $this->belongsTo(Spesialis::class, 'spesialis_id');
    }

    public function subSpesialis()
    {
        return $this->belongsTo(SubSpesialis::class, 'sub_spesialis_id');
    }

    public function pegawaiRuangan()
    {
        return $this->hasMany(PegawaiRuangan::class, 'peg_id');
    }

    public function riwayatPangkat()
    {
        return $this->hasMany(RiwayatPangkat::class, 'peg_id');
    }
    public function riwayatTugasTambahan()
    {
        return $this->hasMany(RiwayatTugasTambahan::class, 'peg_id');
    }
    public function riwayatKenaikanGajiBerkala()
    {
        return $this->hasMany(RiwayatKenaikanGajiBerkala::class, 'peg_id');
    }

    public function riwayatPrestasi()
    {
        return $this->hasMany(RiwayatPrestasi::class, 'peg_id');
    }
    public function mcu()
    {
        return $this->hasMany(Mcu::class, 'peg_id');
    }

    public function uraianTugas()
    {
        return $this->hasMany(UraianTugas::class, 'peg_id');
    }
    public function penilaianKinerja()
    {
        return $this->hasMany(PenilaianKinerja::class, 'peg_id');
    }

    public function pengalamanKerja()
    {
        return $this->hasMany(PengalamanKerja::class, 'peg_id');
    }
    public function nira()
    {
        return $this->hasMany(Nira::class, 'peg_id');
    }
    public function dataHubunganKerabat()
    {
        return $this->hasMany(DataHubunganKerabat::class, 'peg_id');
    }
    public function dataKontakDarurat()
    {
        return $this->hasMany(DataKontakDarurat::class, 'peg_id');
    }
    public function riwayatDiklat()
    {
        return $this->hasMany(RiwayatDiklat::class, 'peg_id');
    }
    public function riwayatPengangkatan()
    {
        return $this->hasMany(RiwayatPengangkatan::class, 'peg_id');
    }
    public function dataKeluarga()
    {
        return $this->hasMany(DataKeluarga::class, 'peg_id');
    }
    public function riwayatPenempatan()
    {
        return $this->hasMany(RiwayatPenempatan::class, 'peg_id');
    }
    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'peg_id');
    }

    public function manajemenKontrak()
    {
        return $this->hasMany(ManajemenKontrak::class, 'peg_id');
    }

    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class);
    }

    public function riwayatAsesmen()
    {
        return $this->hasMany(RiwayatAsesmen::class, 'peg_id');
    }


    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'peg_id');
    }

    public function riwayatRkk()
    {
        return $this->hasMany(RiwayatRkkSpkkJk::class, 'peg_id');
    }

    public function riwayatMcu()
    {
        return $this->hasMany(RiwayatMcu::class, 'peg_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'peg_id');
    }

    public function hitungTmtPensiun()
    {
        $jab = $this->jabatan;
        $usia_pensiun = 58;
        $spesialis = $this->spesialis;

        if ($this->status_kepegawaian_id == 1) {
            if (isset($jab->jabatanFungsional)) {
                if ($jab->jabatan_fungsional_id == 1 || $jab->jabatan_fungsional_id == 2) {
                    $usia_pensiun = $jab->bup;
                }
            } else {
                if ($jab && $spesialis) {
                    $sub_spesial = SubSpesialis::where('spesialis_id', $spesialis->spesialis_id)->first();
                    if ($sub_spesial) {
                        $usia_pensiun = $sub_spesial->bup;
                    } else {
                        $usia_pensiun = $spesialis->bup;
                    }
                } elseif ($jab && !$spesialis) {
                    if ($jab->jabatan_fungsional_id == 1 || $jab->jabatan_fungsional_id == 2) {
                        $usia_pensiun = $jab->bup;
                    }
                }
            }
        }

        return date('Y-m-01', strtotime($this->peg_lahir_tanggal . " +$usia_pensiun years +1 month"));
    }

    public function pegawaiAtasan()
    {
        return $this->belongsTo(PegawaiAtasan::class, 'atasan_id', 'id');
    }
}
