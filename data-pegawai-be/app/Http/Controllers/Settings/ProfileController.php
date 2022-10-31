<?php

namespace App\Http\Controllers\Settings;

use Carbon\Carbon;
use App\Models\Sip;
use App\Models\Str;
use App\Models\Pegawai;
use App\Models\MasterMcu;
use App\Models\MasterRkk;
use App\Models\UnitKerja;
use App\Models\MasterDiklat;
use Illuminate\Http\Request;
use App\Models\MasterAsesmen;
use App\Models\DokumenDigital;
use App\Rules\MatchOldPassword;
use App\Models\MasterPendidikan;
use App\Models\DataKontakDarurat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MasterDokumenDigital;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->user();

        $models = Pegawai::with(
            'bidang',
            'golongan',
            'ijazah',
            'jabatan',
            'jenisJabatan',
            'jenisTenagaKerja',
            'jabatan.jabatanFungsional',
            'kualifikasiPendidikan',
            'pendidikan',
            'nira',
            'mcu.masterMcu',
            'spesialis',
            'subSpesialis',
            'statusPegawai',
            'unitKerja',
            'dataHubunganKerabat',
            'dataKeluarga',
            'dataKontakDarurat',
            'uraianTugas',
            'penilaianKinerja',
            'manajemenKontrak',
            'pengalamanKerja',
            'riwayatPengangkatan',
            'riwayatKenaikanGajiBerkala',
            'riwayatPenempatan',
            'riwayatTugasTambahan',
            'riwayatPrestasi',
            'riwayatDiklat.masterDiklat',
            'riwayatPendidikan.masterPendidikan',
            'riwayatAsesmen.masterAsesmenKompetensi',
            'pelanggaran',
            'riwayatRkk.masterRkkSpkkJk',
            'pegawaiRuangan.ruangan',
            'pegawaiAtasan',
            'sip',
            'str',
            'riwayatMcu'
        )->where('peg_id', $user->peg_id)
            ->firstOrFail();
        $unit_kerja = UnitKerja::find($models->unit_kerja_id);
        if ($unit_kerja) {
            if ($unit_kerja->unit_kerja_level == 1) {
                $models->instalasi = $unit_kerja->unit_kerja_id;
                $models->peg_instalasi = UnitKerja::find($unit_kerja->unit_kerja_id);
            } elseif ($unit_kerja->unit_kerja_level == 2) {
                $models->instalasi = $unit_kerja->unit_kerja_parent;
                $models->ruangan = $unit_kerja->unit_kerja_id;
                $models->peg_instalasi = UnitKerja::find($unit_kerja->unit_kerja_parent);
                $models->peg_ruangan = UnitKerja::find($unit_kerja->unit_kerja_id);
            } else {
                $ruangan = UnitKerja::find($unit_kerja->unit_kerja_parent);
                $models->instalasi = $ruangan->unit_kerja_parent;
                $models->ruangan = $ruangan->unit_kerja_id;
                $models->unit_ruangan = $unit_kerja->unit_kerja_id;
                $models->peg_instalasi = UnitKerja::find($ruangan->unit_kerja_parent);
                $models->peg_ruangan = UnitKerja::find($ruangan->unit_kerja_id);
                $models->peg_unit_ruangan = UnitKerja::find($unit_kerja->unit_kerja_id);
            }
        }
        $models->peg_usia = Carbon::parse($models->peg_lahir_tanggal)->diff(Carbon::now())->y;
        $models->peg_masa_kerja = Carbon::parse($models->peg_tmt)->diff(Carbon::now())->y;
        if ($user->role_id != 1) {
            $models->sipSingle = Sip::where('peg_id', $user->id)->orderBy('tanggal_kadaluarsa_sip', 'desc')->latest()->first();
            $models->strSingle = Str::where('peg_id', $user->id)->orderBy('tanggal_kadaluarsa_str', 'desc')->latest()->first();
        }
        $models->masaKerjaBulan = Carbon::parse($models->peg_tmt)->diff(Carbon::now())->m;
        $models->masaKerjaTahun = Carbon::parse($models->peg_tmt)->diff(Carbon::now())->y;
        $models->kontakDarurat = DataKontakDarurat::where('peg_id', $user->id)->first();

        // Riwayat Kesehatan
        $riwayat_kesehatan_mcu = collect([]);
        $mcu = MasterMcu::where('kategori', 'mcu')->get();
        $riwayat_kesehatan = $models->mcu->groupBy('master_mcu_id');
        foreach ($mcu as $pd) {
            if (isset($riwayat_kesehatan[$pd->id])) {
                $riwayat_kesehatan_mcu = $riwayat_kesehatan_mcu->merge($riwayat_kesehatan[$pd->id]);
            } else {
                $riwayat_kesehatan_mcu->push([
                    'master_mcu' => $pd
                ]);
            }
        }
        $models->riwayat_kesehatan_mcu = $riwayat_kesehatan_mcu;

        $riwayat_kesehatan_vaksin = collect([]);
        $vaksin = MasterMcu::where('kategori', 'vaksin')->get();
        $riwayat_kesehatan = $models->mcu->groupBy('master_mcu_id');
        foreach ($vaksin as $v) {
            if (isset($riwayat_kesehatan[$v->id])) {
                $riwayat_kesehatan_vaksin = $riwayat_kesehatan_vaksin->merge($riwayat_kesehatan[$v->id]);
            } else {
                $riwayat_kesehatan_vaksin->push([
                    'master_mcu' => $v
                ]);
            }
        }
        $models->riwayat_kesehatan_vaksin = $riwayat_kesehatan_vaksin;

        $riwayat_kesehatan_keadaan = collect([]);
        $keadaan = MasterMcu::where('kategori', 'keadaan')->get();
        $riwayat_kesehatan = $models->mcu->groupBy('master_mcu_id');
        foreach ($keadaan as $k) {
            if (isset($riwayat_kesehatan[$k->id])) {
                $riwayat_kesehatan_keadaan = $riwayat_kesehatan_keadaan->merge($riwayat_kesehatan[$k->id]);
            } else {
                $riwayat_kesehatan_keadaan->push([
                    'master_mcu' => $k
                ]);
            }
        }
        $models->riwayat_kesehatan_keadaan = $riwayat_kesehatan_keadaan;

        // Riwayat Diklat Pelatihan Dasar
        $riwayat_diklat_pelatihan_dasar = collect([]);
        $pelatihan_dasar = MasterDiklat::where('kategori_pelatihan', 'Pelatihan Dasar')->get();
        $riwayat_diklat = $models->riwayatDiklat->groupBy('master_diklat_id');
        foreach ($pelatihan_dasar as $pd) {
            if (isset($riwayat_diklat[$pd->id])) {
                $riwayat_diklat_pelatihan_dasar = $riwayat_diklat_pelatihan_dasar->merge($riwayat_diklat[$pd->id]);
            } else {
                $riwayat_diklat_pelatihan_dasar->push([
                    'master_diklat' => $pd
                ]);
            }
        }
        $models->riwayat_diklat_pelatihan_dasar = $riwayat_diklat_pelatihan_dasar;

        // Riwayat Diklat Pelatihan Keahlian
        $riwayat_diklat_pelatihan_keahlian = collect([]);
        $pelatihan_keahlian = MasterDiklat::where('kategori_pelatihan', 'Pelatihan Keahlian')->get();
        $riwayat_diklat = $models->riwayatDiklat->groupBy('master_diklat_id');
        foreach ($pelatihan_keahlian as $pd) {
            if (isset($riwayat_diklat[$pd->id])) {
                $riwayat_diklat_pelatihan_keahlian = $riwayat_diklat_pelatihan_keahlian->merge($riwayat_diklat[$pd->id]);
            } else {
                $riwayat_diklat_pelatihan_keahlian->push([
                    'master_diklat' => $pd
                ]);
            }
        }
        $models->riwayat_diklat_pelatihan_keahlian = $riwayat_diklat_pelatihan_keahlian;

        $riwayat_diklat_pelatihan_lainnya = collect([]);
        $pelatihan_lainnya = MasterDiklat::where('kategori_pelatihan', 'Pelatihan Lainnya')->get();
        $riwayat_diklat = $models->riwayatDiklat->groupBy('master_diklat_id');
        foreach ($pelatihan_lainnya as $pd) {
            if (isset($riwayat_diklat[$pd->id])) {
                $riwayat_diklat_pelatihan_lainnya = $riwayat_diklat_pelatihan_lainnya->merge($riwayat_diklat[$pd->id]);
            } else {
                $riwayat_diklat_pelatihan_lainnya->push([
                    'master_diklat' => $pd
                ]);
            }
        }
        $models->riwayat_diklat_pelatihan_lainnya = $riwayat_diklat_pelatihan_lainnya;
        // Riwayat Pendidikan
        $riwayat_pendidikan_tingkat = collect([]);
        $pendidikan = MasterPendidikan::all();
        $riwayat_pendidikan = $models->riwayatPendidikan->groupBy('master_pendidikan_id');
        foreach ($pendidikan as $pd) {
            if (isset($riwayat_pendidikan[$pd->id])) {
                $riwayat_pendidikan_tingkat = $riwayat_pendidikan_tingkat->merge($riwayat_pendidikan[$pd->id]);
            } else {
                $riwayat_pendidikan_tingkat->push([
                    'master_pendidikan' => $pd
                ]);
            }
        }
        $models->riwayat_pendidikan_tingkat = $riwayat_pendidikan_tingkat;
        // Riwayat Asesmen
        $riwayat_asesmen_perawat = collect([]);
        $asesmen_perawat = MasterAsesmen::where('kategori_asesmen', 'perawat')->orderBy('jenis_asesmen_kompetensi', 'asc')->get();
        $riwayat_asesmen = $models->riwayatAsesmen->groupBy('master_asesmen_kompetensi_id');
        foreach ($asesmen_perawat as $ak) {
            if (isset($riwayat_asesmen[$ak->id])) {
                $riwayat_asesmen_perawat = $riwayat_asesmen_perawat->merge($riwayat_asesmen[$ak->id]);
            } else {
                $riwayat_asesmen_perawat->push([
                    'master_asesmen_kompetensi' => $ak
                ]);
            }
        }
        $models->riwayat_asesmen_perawat = $riwayat_asesmen_perawat;

        $riwayat_asesmen_nakes = collect([]);
        $asesmen_nakes = MasterAsesmen::where('kategori_asesmen', 'nakes')->orderBy('jenis_asesmen_kompetensi', 'asc')->get();
        $riwayat_asesmen = $models->riwayatAsesmen->groupBy('master_asesmen_kompetensi_id');
        foreach ($asesmen_nakes as $ak) {
            if (isset($riwayat_asesmen[$ak->id])) {
                $riwayat_asesmen_nakes = $riwayat_asesmen_nakes->merge($riwayat_asesmen[$ak->id]);
            } else {
                $riwayat_asesmen_nakes->push([
                    'master_asesmen_kompetensi' => $ak
                ]);
            }
        }
        $models->riwayat_asesmen_nakes = $riwayat_asesmen_nakes;
        // Riwayat RKK
        $riwayat_rkk_spkk_jk = collect([]);
        $rkk = MasterRkk::all();
        $riwayat_rkk = $models->riwayatRkk->groupBy('master_rkk_spkk_jk_id');
        foreach ($rkk as $ak) {
            if (isset($riwayat_rkk[$ak->id])) {
                $riwayat_rkk_spkk_jk = $riwayat_rkk_spkk_jk->merge($riwayat_rkk[$ak->id]);
            } else {
                $riwayat_rkk_spkk_jk->push([
                    'master_rkk_spkk_jk' => $ak
                ]);
            }
        }
        $models->riwayat_rkk_spkk_jk = $riwayat_rkk_spkk_jk;
        // Dokumen Digital
        $master = MasterDokumenDigital::get()->groupBy('relation_to');
        $files = DokumenDigital::with('master_dokumen_digital')->where('peg_id', $user->id)->get()

            ->groupBy('master_dokumen_digital.relation_to')
            ->transform(function ($_files) {
                return $_files->groupBy('entity_id')->transform(function ($__files) {
                    return $__files->keyBy('master_dokumen_digital_id');
                });
            });

        return response()->json([
            'success' => true,
            'models' => $models,
            'dokumen_digital' => $files,
            'master_dokumen_digital' => $master,
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => ['required'],
            'password_confirmation' => ['same:password'],
        ], [
            'password.required' => 'Password harus diisi',
            'password_confirmation.same' => 'Password tidak sama'
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'success' => true
        ], 200);
    }
}
