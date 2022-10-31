<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\MasterDokumenDigital;
use App\Models\DokumenDigital;
use App\Models\MasterAsesmen;
use App\Models\MasterDiklat;
use App\Models\MasterPendidikan;
use App\Models\MasterMcu;
use App\Models\UnitKerja;
use App\Models\Ruangan;
use App\Models\PegawaiRuangan;
use App\Models\DataHubunganKerabat;
use App\Models\DataKontakDarurat;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\JabatanFungsional;
use App\Models\JenisJabatan;
use App\Models\JenisTenagaKerja;
use App\Models\KabupatenKota;
use App\Models\Kecamatan;
use App\Models\KelurahanDesa;
use App\Models\KodePos;
use App\Models\KualifikasiPendidikan;
use App\Models\MasterRkk;
use App\Models\Mcu;
use App\Models\Pendidikan;
use App\Models\Provinsi;
use App\Models\Sip;
use App\Models\Spesialis;
use App\Models\StatusPegawai;
use App\Models\Str;
use App\Models\SubSpesialis;
use App\Models\TenagaKerja;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $req)
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
            $filter = $req->filter;
            $sortby = $req->sortby;
            $sortdesc = $req->sortdesc;
            $params = $req->params;

            $models = Pegawai::with([
                'bidang',
                'golongan',
                'ijazah',
                'jabatan',
                'jenisJabatan',
                'jenisTenagaKerja',
                // 'jenjangJabatan',
                'kualifikasiPendidikan',
                'pendidikan',
                'sip',
                'spesialis',
                'statusPegawai',
                'str',
                'unitKerja',
            ]);

            if (!empty($filter)) {
                $filter = addslashes($filter);
                $models = $models->where(function ($q) use ($filter) {
                    $q->whereRaw('LOWER(peg_nama_lengkap) like (?)', ['%' . (strtolower($filter)) . '%'])
                        ->orWhereRaw('LOWER(peg_nama_tanpa_gelar) like (?)', ['%' . (strtolower($filter)) . '%']);
                });
            }

            if (!empty($sortby)) {
                if ($sortdesc == 'true') {
                    $models = $models->orderByDesc($sortby);
                } else {
                    $models = $models->orderBy($sortby);
                }
            }

            if (is_array($params)) {
                foreach ($params as $key => $val) {
                    if (empty($val) && $val !== 0 || $val == 'null') continue;
                    switch ($key) {
                        case 'status_kepegawaian_id':
                            $models->whereHas('statusPegawai', function ($q) use ($val) {
                                $q->where('status_kepegawaian_id', $val);
                            });
                            break;
                    }
                }
                if ($params['status_kepegawaian_id'] === '0') {
                    $models = $models->whereDoesntHave('statusPegawai');
                }
            }

            $page = $req->get('page', 1);
            $count = $models->count();
            $perpage = $req->perpage == 'all' ? $count : $req->perpage ?? 20;
            $models->skip(($page - 1) * $perpage)->take($perpage);
            $models = $models->get();

            foreach ($models as $key => $model) {
                $model->peg_usia = Carbon::parse($model->peg_lahir_tanggal)->diff(Carbon::now())->y;
                $model->peg_masa_kerja = Carbon::parse($model->peg_tmt)->diff(Carbon::now())->y;
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        return response()->json([
            'success' => true,
            'data' => $models,
            'count' => $count
        ], 200);
    }

    public function store(Request $req)
    {
        $this->validate(
            $req,
            [
                'peg_nama_lengkap' => ['required', 'string'],
                'peg_nama_tanpa_gelar' => ['required', 'string'],
                'peg_jenis_kelamin' => ['required', 'string'],
                'peg_lahir_tempat' => ['required', 'string'],
                'peg_lahir_tanggal' => ['required'],
                'peg_ktp' => ['required', 'string'],
                'peg_email' => ['email:dns', 'unique:pegawai,peg_email'],
            ],
            [
                'peg_nama_lengkap.required' => 'Nama lengkap harus diisi.',
                'peg_nama_tanpa_gelar.required' => 'Nama tanpa gelar harus diisi.',
                'peg_lahir_tempat.required' => 'Tempat lahir harus diisi.',
                'peg_lahir_tanggal.required' => 'Tanggal lahir harus diisi.',
                'peg_ktp.required' => 'KTP harus diisi.',
            ]
        );

        $model = Pegawai::create([
            'peg_telp_hp' => $req->peg_telp_hp,
            'peg_email' => $req->peg_email,
            'peg_tmt' => $req->peg_tmt,
            'peg_tmt_golongan_akhir' => $req->peg_tmt_golongan_akhir,
            'peg_masa_kerja' => Carbon::parse($req->peg_tmt)->diff(Carbon::now())->y,
            'peg_rumah_alamat' => $req->peg_rumah_alamat,
            'prov_id' => $req->prov_id,
            'kabkot_id' => $req->kabkot_id,
            'kec_id' => $req->kec_id,
            'keldes_id' => $req->keldes_id,
            'peg_alamat_rw' => $req->peg_alamat_rw,
            'peg_alamat_rt' => $req->peg_alamat_rt,
            'peg_kodepos' => $req->peg_kodepos,
            'peg_ktp_rumah_alamat' => $req->peg_ktp_rumah_alamat,
            'ktp_prov_id' => $req->ktp_prov_id,
            'ktp_kabkot_id' => $req->ktp_kabkot_id,
            'ktp_kec_id' => $req->ktp_kec_id,
            'ktp_keldes_id' => $req->ktp_keldes_id,
            'peg_ktp_alamat_rw' => $req->peg_ktp_alamat_rw,
            'peg_ktp_alamat_rt' => $req->peg_ktp_alamat_rt,
            'peg_ktp_kodepos' => $req->peg_ktp_kodepos,
            'peg_nama_lengkap' => $req->peg_nama_lengkap,
            'peg_gelar_depan' => $req->peg_gelar_depan,
            'peg_gelar_belakang' => $req->peg_gelar_belakang,
            'peg_nama_tanpa_gelar' => $req->peg_nama_tanpa_gelar,
            'peg_nip_nipt_nik' => $req->peg_nip_nipt_nik,
            'peg_golongan_darah' => $req->peg_golongan_darah,
            'peg_agama' => $req->peg_agama,
            'peg_lahir_tempat' => $req->peg_lahir_tempat,
            'peg_lahir_tanggal' => $req->peg_lahir_tanggal,
            'peg_usia' => Carbon::parse($req->peg_lahir_tanggal)->diff(Carbon::now())->y,
            'peg_status_pernikahan' => $req->peg_status_pernikahan,
            'peg_jenis_kelamin' => $req->peg_jenis_kelamin,
            'kualifikasi_pendidikan_id' => $req->kualifikasi_pendidikan_id,
            'bidang_id' => $req->bidang_id,
            'pendidikan_id' => $req->pendidikan_id,
            'peg_almamater_nama' => $req->peg_almamater_nama,
            'unit_kerja_id' => $req->unit_kerja_id,
            'spesialis_id' => $req->spesialis_id,
            'jabatan_id' => $req->jabatan_id,
            'jenis_jabatan_id' => $req->jenis_jabatan_id,
            'golongan_id' => $req->golongan_id,
            'peg_status_kerja' => $req->peg_status_kerja,
            'jenis_tenaga_kerja_id' => $req->jenis_tenaga_kerja_id,
            'status_kepegawaian_id' => $req->status_kepegawaian_id,
            'peg_sk_pengangkatan' => $req->peg_sk_pengangkatan,
            'peg_ktp' => $req->peg_ktp,
            'peg_no_kk' => $req->peg_no_kk,
            'peg_npwp' => $req->peg_npwp,
            'peg_bpjs' => $req->peg_bpjs,
            'peg_bpjs_ketenagakerjaan' => $req->peg_bpjs_ketenagakerjaan,
            'peg_nomor_rekening' => $req->peg_nomor_rekening,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nip' => $model->peg_nip_nipt_nik,
            'nama' => $model->peg_nama_tanpa_gelar,
            'jenis_kelamin' => $model->peg_jenis_kelamin,
            'tanggal_lahir' => $model->peg_lahir_tanggal,
            'ktp' => $model->peg_ktp,
            'email' => $model->peg_email,
        ]);

        logAction('Tambah Pegawai', json_encode($data), $model->peg_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
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
                'unitKerja.parent.parent.parent',
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
                'pegawaiRuangan.ruangan.parent.parent.parent',
                'pegawaiAtasan',
                'sip',
                'str',
                'riwayatMcu'
            )->where('peg_id', $id)
                ->firstOrFail();
            // $unit_kerja = UnitKerja::find($models->unit_kerja_id);
            // if ($unit_kerja) {
            //     if ($unit_kerja->unit_kerja_level == 1) {
            //         $models->instalasi = $unit_kerja->unit_kerja_id;
            //         $models->peg_instalasi = UnitKerja::find($unit_kerja->unit_kerja_id);
            //     } elseif ($unit_kerja->unit_kerja_level == 2) {
            //         $models->instalasi = $unit_kerja->unit_kerja_parent;
            //         $models->ruangan = $unit_kerja->unit_kerja_id;
            //         $models->peg_instalasi = UnitKerja::find($unit_kerja->unit_kerja_parent);
            //         $models->peg_ruangan = UnitKerja::find($unit_kerja->unit_kerja_id);
            //     } else {
            //         $ruangan = UnitKerja::find($unit_kerja->unit_kerja_parent);
            //         $models->instalasi = $ruangan->unit_kerja_parent;
            //         $models->ruangan = $ruangan->unit_kerja_id;
            //         $models->unit_ruangan = $unit_kerja->unit_kerja_id;
            //         $models->peg_instalasi = UnitKerja::find($ruangan->unit_kerja_parent);
            //         $models->peg_ruangan = UnitKerja::find($ruangan->unit_kerja_id);
            //         $models->peg_unit_ruangan = UnitKerja::find($unit_kerja->unit_kerja_id);
            //     }
            // }
            $models->peg_usia = Carbon::parse($models->peg_lahir_tanggal)->diff(Carbon::now())->y;
            $models->peg_masa_kerja = Carbon::parse($models->peg_tmt)->diff(Carbon::now())->y;
            $models->sipSingle = Sip::where('peg_id', $id)->orderBy('tanggal_kadaluarsa_sip', 'desc')->latest()->first();
            $models->strSingle = Str::where('peg_id', $id)->orderBy('tanggal_kadaluarsa_str', 'desc')->latest()->first();
            $models->masaKerjaBulan = Carbon::parse($models->peg_tmt)->diff(Carbon::now())->m;
            $models->masaKerjaTahun = Carbon::parse($models->peg_tmt)->diff(Carbon::now())->y;
            $models->kontakDarurat = DataKontakDarurat::where('peg_id', $id)->first();
            $models->pathTemplate = asset('template/RiwayatHidup.doc');

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
            $files = DokumenDigital::with('master_dokumen_digital')->where('peg_id', $id)->get()

                ->groupBy('master_dokumen_digital.relation_to')
                ->transform(function ($_files) {
                    return $_files->groupBy('entity_id')->transform(function ($__files) {
                        return $__files->keyBy('master_dokumen_digital_id');
                    });
                });
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        return response()->json([
            'success' => true,
            'models' => $models,
            'dokumen_digital' => $files,
            'master_dokumen_digital' => $master,
        ], 200);
    }

    public function updateUtama(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'peg_email' => ['email:dns', Rule::unique('pegawai', 'peg_email')->ignore($id, 'peg_id')],
            ],
            [
                'peg_email.email' => 'Alamat email yang dimasukan harus email yang valid.',
            ]
        );
        $model = Pegawai::findOrFail($id);
        $model->update([
            'peg_telp_hp' => $request->peg_telp_hp,
            'peg_email' => $request->peg_email,
            'peg_tmt' => $request->peg_tmt,
            'peg_tmt_golongan_akhir' => $request->peg_tmt_golongan_akhir,
            'peg_masa_kerja' => Carbon::parse($request->peg_tmt)->diff(Carbon::now())->y,
            'peg_rumah_alamat' => $request->peg_rumah_alamat,
            'prov_id' => $request->prov_id,
            'kabkot_id' => $request->kabkot_id,
            'kec_id' => $request->kec_id,
            'keldes_id' => $request->keldes_id,
            'peg_alamat_rw' => $request->peg_alamat_rw,
            'peg_alamat_rt' => $request->peg_alamat_rt,
            'peg_kodepos' => $request->peg_kodepos,
            'peg_ktp_rumah_alamat' => $request->peg_ktp_rumah_alamat,
            'ktp_prov_id' => $request->ktp_prov_id,
            'ktp_kabkot_id' => $request->ktp_kabkot_id,
            'ktp_kec_id' => $request->ktp_kec_id,
            'ktp_keldes_id' => $request->ktp_keldes_id,
            'peg_ktp_alamat_rw' => $request->peg_ktp_alamat_rw,
            'peg_ktp_alamat_rt' => $request->peg_ktp_alamat_rt,
            'peg_ktp_kodepos' => $request->peg_ktp_kodepos,
        ]);

        User::where('peg_id', $model->peg_id)->first()->update([
            'email' => $model->peg_email
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nip' => $model->peg_nip_nipt_nik,
            'nama' => $model->peg_nama_tanpa_gelar,
            'email' => $model->peg_email,
        ]);

        logAction('Update Pegawai Utama', json_encode($data), $model->peg_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function updateProfile(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'peg_nama_lengkap' => ['required', 'string'],
                'peg_nama_tanpa_gelar' => ['required', 'string'],
                'peg_jenis_kelamin' => ['required', 'string'],
                'peg_lahir_tempat' => ['required', 'string'],
                'peg_lahir_tanggal' => ['required'],
                'peg_ktp' => ['required', 'string'],
            ],
            [
                'peg_nama_lengkap.required' => 'Nama lengkap harus diisi.',
                'peg_nama_tanpa_gelar.required' => 'Nama tanpa gelar harus diisi.',
                'peg_lahir_tempat.required' => 'Tempat lahir harus diisi.',
                'peg_lahir_tanggal.required' => 'Tanggal lahir harus diisi.',
                'peg_ktp.required' => 'KTP harus diisi.',
            ]
        );
        $model = Pegawai::findOrFail($id);
        $unit_kerja_id = $request->unit_kerja_id;
        // if ($request->instalasi && !$request->ruangan) {
        //     $unit_kerja_id = $request->instalasi;
        // } elseif ($request->instalasi && $request->ruangan && !$request->unit_ruangan) {
        //     $unit_kerja_id = $request->ruangan;
        // } else {
        //     $unit_kerja_id = $request->unit_ruangan;
        // }
        if (is_array($request->pegawai_ruangan)) {
            $update_ids = [];
            $inserts = [];
            foreach ($request->pegawai_ruangan as $pegawai_ruangan) {
                if (isset($pegawai_ruangan['peg_ruangan_id'])) {
                    $peg_ruangan_id = $pegawai_ruangan['peg_ruangan_id'];
                    $update_ids[] = $peg_ruangan_id;
                    PegawaiRuangan::where('peg_id', $id)->where('peg_ruangan_id', $peg_ruangan_id)->update([
                        'role' => $pegawai_ruangan['role'],
                        'ruangan_id' => $pegawai_ruangan['ruangan_id'],
                        'role_id' => PegawaiRuangan::roleToId($pegawai_ruangan['role']),
                    ]);
                } else {
                    $inserts[] = [
                        'peg_id' => $id,
                        'role' => $pegawai_ruangan['role'],
                        'ruangan_id' => $pegawai_ruangan['ruangan_id'],
                        'role_id' => PegawaiRuangan::roleToId($pegawai_ruangan['role']),
                    ];
                }
            }
            PegawaiRuangan::where('peg_id', $id)->whereNotIn('peg_ruangan_id', $update_ids)->delete();
            if (count($inserts)) {
                PegawaiRuangan::insert($inserts);
            }
        }
        $model->update([
            'peg_nama_lengkap' =>  $request->peg_nama_lengkap,
            'peg_gelar_depan' => $request->peg_gelar_depan,
            'peg_gelar_belakang' => $request->peg_gelar_belakang,
            'peg_nama_tanpa_gelar' => $request->peg_nama_tanpa_gelar,
            'peg_nip_nipt_nik' => $request->peg_nip_nipt_nik,
            'peg_golongan_darah' => $request->peg_golongan_darah,
            'peg_agama' => $request->peg_agama,
            'peg_lahir_tempat' => $request->peg_lahir_tempat,
            'peg_lahir_tanggal' => $request->peg_lahir_tanggal,
            'peg_usia' => Carbon::parse($request->peg_lahir_tanggal)->diff(Carbon::now())->y,
            'peg_status_pernikahan' => $request->peg_status_pernikahan,
            'peg_jenis_kelamin' => $request->peg_jenis_kelamin,
            'kualifikasi_pendidikan_id' => $request->kualifikasi_pendidikan_id,
            'bidang_id' => $request->bidang_id,
            'pendidikan_id' => $request->pendidikan_id,
            'peg_almamater_nama' => $request->peg_almamater_nama,
            'unit_kerja_id' => $unit_kerja_id,
            'spesialis_id' => $request->spesialis_id,
            'sub_spesialis_id' => $request->sub_spesialis_id,
            'jabatan_id' => $request->jabatan_id,
            'jabatan_fungsional_id' => $request->jabatan_fungsional_id,
            'jenis_jabatan_id' => $request->jenis_jabatan_id,
            'golongan_id' => $request->golongan_id,
            'peg_status_kerja' => $request->peg_status_kerja,
            'tenaga_kerja_id' => $request->tenaga_kerja_id,
            'jenis_tenaga_kerja_id' => $request->jenis_tenaga_kerja_id,
            'status_kepegawaian_id' => $request->status_kepegawaian_id,
            'peg_sk_pengangkatan' => $request->peg_sk_pengangkatan,
            'peg_ktp' => $request->peg_ktp,
            'peg_no_kk' => $request->peg_no_kk,
            'peg_npwp' => $request->peg_npwp,
            'peg_bpjs' => $request->peg_bpjs,
            'peg_bpjs_ketenagakerjaan' => $request->peg_bpjs_ketenagakerjaan,
            'peg_nomor_rekening' => $request->peg_nomor_rekening,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nip' => $model->peg_nip_nipt_nik,
            'nama' => $model->peg_nama_tanpa_gelar,
            'jenis_kelamin' => $model->peg_jenis_kelamin,
            'tanggal_lahir' => $model->peg_lahir_tanggal,
            'ktp' => $model->peg_ktp,
            'email' => $model->peg_email,
        ]);

        logAction('Update Pegawai Profile', json_encode($data), $model->peg_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function updateAbout(Request $request, $id)
    {
        $model = Pegawai::findOrFail($id);
        $model->update([
            'tentang_saya' => $request->tentang_saya,
        ]);
        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nip' => $model->peg_nip_nipt_nik,
            'nama' => $model->peg_nama_tanpa_gelar,
            'tentang' => $model->tentang_saya,
        ]);

        logAction('Update Pegawai About', json_encode($data), $model->peg_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = Pegawai::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nip' => $model->peg_nip_nipt_nik,
            'nama' => $model->peg_nama_tanpa_gelar,
            'jenis_kelamin' => $model->peg_jenis_kelamin,
            'tanggal_lahir' => $model->peg_lahir_tanggal,
            'ktp' => $model->peg_ktp,
            'email' => $model->peg_email,
        ]);

        logAction('Delete Pegawai', json_encode($data), $model->peg_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function getKabKot(Request $request)
    {
        $models = KabupatenKota::where('provinsi_id', $request->provinsi_id)->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }

    public function getKec(Request $request)
    {
        $models = Kecamatan::where('kabkot_id', $request->kabkot_id)->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }

    public function getKel(Request $request)
    {
        $models = KelurahanDesa::where('kecamatan_id', $request->kecamatan_id)->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }

    public function getOptions()
    {
        $pegawai = Pegawai::select('peg_id', 'peg_nama_lengkap')->orderBy('peg_nama_lengkap', 'asc')->get();
        $kualifikasi_pendidikan = KualifikasiPendidikan::select('kualifikasi_pendidikan_id', 'kualifikasi_pendidikan')->orderBy('kualifikasi_pendidikan', 'asc')->get();
        $bidang = Bidang::select('bidang_id', 'bidang_nama')->orderBy('bidang_nama', 'asc')->get();
        $pendidikan = Pendidikan::select('pendidikan_id', 'pendidikan_nama')->orderBy('pendidikan_nama', 'asc')->get();
        $unit_kerja = UnitKerja::treeSort(UnitKerja::where('unit_kerja_id', '>=', '1000')->get());
        foreach ($unit_kerja as &$model) {
            if ($model->unit_kerja_level) {
                $model->unit_kerja_nama = str_repeat('-', $model->unit_kerja_level) . ' ' . $model->unit_kerja_nama;
            }
        }
        $ruangan = Ruangan::treeSort(Ruangan::get());
        foreach ($ruangan as &$model) {
            if ($model->ruangan_level) {
                $model->nama_ruangan = str_repeat('-', $model->ruangan_level) . ' ' . $model->nama_ruangan;
            }
        }
        $spesialis = Spesialis::select('spesialis_id', 'spesialis_nama')->orderBy('spesialis_nama', 'asc')->get();
        $jabatan = Jabatan::select('jabatan_id', 'jabatan_nama', 'jenis_jabatan_id', 'unit_kerja_id_struktural')->orderBy('jabatan_nama', 'asc')->get();
        $jenis_jabatan = JenisJabatan::select('jenis_jabatan_id', 'jenis_jabatan_nama')->orderBy('jenis_jabatan_nama', 'asc')->get();
        $golongan = Golongan::select('golongan_id', 'golongan_nama')->orderBy('golongan_nama', 'asc')->get();
        $jenis_tenaga_kerja = JenisTenagaKerja::select('jenis_tenaga_kerja_id', 'jenis_tenaga_kerja_nama')->orderBy('jenis_tenaga_kerja_nama', 'asc')->get();
        $jabatan_fungsional = JabatanFungsional::select('jabatan_fungsional_id', 'jabatan_fungsional_nama')->orderBy('jabatan_fungsional_nama', 'asc')->get();
        $status_pegawai = StatusPegawai::select('status_kepegawaian_id', 'status_kepegawaian')->orderBy('status_kepegawaian', 'asc')->get();
        $provinsi = Provinsi::select('id', 'nama')->orderBy('nama', 'asc')->get();
        return response()->json([
            'pegawai' => $pegawai,
            'kualifikasi_pendidikan' => $kualifikasi_pendidikan,
            'bidang' => $bidang,
            'pendidikan' => $pendidikan,
            'unit_kerja' => $unit_kerja,
            'ruangan' => $ruangan,
            'spesialis' => $spesialis,
            'jabatan' => $jabatan,
            'jenis_jabatan' => $jenis_jabatan,
            'golongan' => $golongan,
            'jenis_tenaga_kerja' => $jenis_tenaga_kerja,
            'jabatan_fungsional' => $jabatan_fungsional,
            'status_pegawai' => $status_pegawai,
            'provinsi' => $provinsi,
        ]);
    }

    public function getUnitKerja(Request $request)
    {
        $pegawai = Pegawai::where('peg_nama_lengkap', $request->peg_nama_lengkap)->firstOrFail();
        $model = UnitKerja::where('unit_kerja_id', $pegawai->unit_kerja_id)->select('unit_kerja_nama')->first();
        return response()->json([
            'success' => true,
            'data' => $model,
        ], 200);
    }

    public function getSubSpes(Request $request)
    {
        $model = SubSpesialis::where('spesialis_id', $request->spesialis_id)->select('sub_spesialis_id', 'sub_spesialis_nama')->orderBy('sub_spesialis_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $model,
        ], 200);
    }

    public function getUnitKerjaRuangan(Request $request)
    {
        $model = UnitKerja::where('unit_kerja_parent', $request->instalasi)->select('unit_kerja_id', 'unit_kerja_nama')->orderBy('unit_kerja_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $model,
        ], 200);
    }

    public function getUnitKerjaUnitRuangan(Request $request)
    {
        $model = UnitKerja::where('unit_kerja_parent', $request->ruangan)->select('unit_kerja_id', 'unit_kerja_nama')->orderBy('unit_kerja_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $model,
        ], 200);
    }

    // public function getJenTenKer(Request $request)
    // {
    //     $model = JenisTenagaKerja::where('tenaga_kerja_id', $request->id)->select('jenis_tenaga_kerja_id', 'jenis_tenaga_kerja_nama')->orderBy('jenis_tenaga_kerja_nama', 'asc')->get();
    //     return response()->json([
    //         'success' => true,
    //         'data' => $model,
    //     ], 200);
    // }
}
