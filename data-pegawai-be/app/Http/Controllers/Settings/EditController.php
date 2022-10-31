<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EditController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->user();

        $models = DB::table('users')
                ->leftJoin('pegawai', 'users.peg_id' , '=', 'pegawai.peg_id')
                ->leftJoin('bidang', 'pegawai.bidang_id', '=', 'bidang.bidang_id')
                ->leftJoin('golongan', 'pegawai.golongan_id', '=', 'golongan.golongan_id')
                ->leftJoin('ijazah', 'pegawai.ijazah_id', '=', 'ijazah.ijazah_id')
                ->leftJoin('jabatan', 'pegawai.jabatan_id', '=', 'jabatan.jabatan_id')
                ->leftJoin('jabatan_fungsional', 'jabatan.jabatan_fungsional_id', '=', 'jabatan_fungsional.jabatan_fungsional_id')
                ->leftJoin('jenis_jabatan', 'pegawai.jenis_jabatan_id', '=', 'jenis_jabatan.jenis_jabatan_id')
                ->leftJoin('jenis_tenaga_kerja', 'pegawai.jenis_tenaga_kerja_id', '=', 'jenis_tenaga_kerja.jenis_tenaga_kerja_id')
                ->leftJoin('tenaga_kerja', 'jenis_tenaga_kerja.tenaga_kerja_id', '=', 'tenaga_kerja.tenaga_kerja_id')
                ->leftJoin('jenjang_jabatan', 'pegawai.jenjang_jabatan_id', '=', 'jenjang_jabatan.jenjang_jabatan_id')
                ->leftJoin('kualifikasi_pendidikan', 'pegawai.kualifikasi_pendidikan_id', '=', 'kualifikasi_pendidikan.kualifikasi_pendidikan_id')
                ->leftJoin('pendidikan', 'pegawai.pendidikan_id', '=', 'pendidikan.pendidikan_id')
                ->leftJoin('sip', 'pegawai.sip_id', '=', 'sip.sip_id')
                ->leftJoin('str', 'pegawai.str_id', '=', 'str.str_id')
                ->leftJoin('spesialis', 'pegawai.spesialis_id', '=', 'spesialis.spesialis_id')
                ->leftJoin('sub_spesialis', 'spesialis.sub_spesialis_id', '=', 'sub_spesialis.sub_spesialis_id')
                ->leftJoin('status_pegawai', 'pegawai.status_kepegawaian_id', '=', 'status_pegawai.status_kepegawaian_id')
                ->leftJoin('unit_kerja', 'pegawai.unit_kerja_id', '=', 'unit_kerja.unit_kerja_id')
                ->leftJoin('pegawai_ruangan', 'pegawai.peg_id', '=', 'pegawai_ruangan.peg_id')
                ->leftJoin('ruangan', 'pegawai_ruangan.ruangan_id', '=', 'ruangan.ruangan_id')

                ->select("*")
                ->where('users.id', '=', $user->id)
                ->first();

        return response()->json([
            'models' => $models,
            'success' => true,
        ], 200);
    }

    public function show(Request $req)
    {
        $user = $req->user();

        $models = Pegawai::with(
            'bidang', 
            'golongan', 
            'ijazah', 
            'jabatan', 
            'jenisJabatan', 
            'jenisTenagaKerja', 
            'jenjangJabatan',
            'kualifikasiPendidikan',
            'pendidikan',
            'sip',
            'spesialis',
            'statusPegawai',
            'str',
            'unitKerja'
            )->where('peg_id', $user->id)
            ->firstOrFail();
            $models->peg_usia = Carbon::parse($models->peg_lahir_tanggal)->diff(Carbon::now())->y;
            $models->peg_masa_kerja = Carbon::parse($models->peg_tmt)->diff(Carbon::now())->y;

        return response()->json([
            'models' => $models,
            'success' => true,
        ], 200);
    }

    public function update(Request $req)
    {
        $accepted_body = $req->only([
            'peg_nama_lengkap', 
            // 'peg_gelar_depan',
            // 'peg_gelar_belakang',
            'peg_nama_tanpa_gelar',
            'peg_almamater_nama',
            'peg_jenis_kelamin',
            'peg_lahir_tempat',
            'peg_lahir_tanggal',
            'peg_nip_nipt_nik',
            'peg_bpjs',
            'peg_npwp',
            'peg_ktp',
            'peg_tmt',
            'peg_tmt_golongan_akhir',
            'peg_sk_pengangkatan',
            'peg_telp_hp',
            'peg_email',
            'peg_rumah_alamat',
            'peg_nama_provinsi',
            'peg_nama_kota',
            'peg_nama_kec',
            'peg_kel_desa',
            'peg_alamat_rw',
            'peg_alamat_rt',
            'peg_kodepos'
        ]);

        $validator = Validator::make($accepted_body, [
            'peg_nama_lengkap' => ['required', 'string', 'max:255'],
            // 'peg_gelar_depan' => ['required', 'string', 'max:64',],
            // 'peg_gelar_belakang' => ['required', 'string', 'max:64'],
            'peg_nama_tanpa_gelar' => ['required', 'string', 'max:255'],
            'peg_almamater_nama' => ['required', 'string', 'max:255'],
            'peg_jenis_kelamin' => ['required', 'string', 'max:9'],
            'peg_lahir_tempat' => ['required', 'string', 'max:255'],
            'peg_lahir_tanggal' => ['required'],
            'peg_nip_nipt_nik' => ['required', 'string', 'max:50'],
            'peg_bpjs' => ['required', 'string', 'max:128'],
            'peg_npwp' => ['required', 'string', 'max:16', 'min:15'],
            'peg_ktp' => ['required', 'string', 'max:128'],
            'peg_tmt' => ['required'],
            'peg_tmt_golongan_akhir' => ['required'],
            'peg_sk_pengangkatan' => ['required', 'string', 'max:255'],
            'peg_telp_hp' => ['required', 'string', 'max:255'],
            'peg_email' => ['required', 'string', 'max:255'],
            'peg_rumah_alamat' => ['required', 'string', 'max:255'],
            'peg_nama_provinsi' => ['required', 'string', 'max:255'],
            'peg_nama_kota' => ['required', 'string', 'max:255'],
            'peg_nama_kec' => ['required', 'string', 'max:255'],
            'peg_kel_desa' => ['required', 'string', 'max:255'],
            'peg_alamat_rw' => ['required', 'string', 'max:255'],
            'peg_alamat_rt' => ['required', 'string', 'max:255'],
            'peg_kodepos' => ['required', 'string', 'max:5']
        ],
        [
            'peg_nama_lengkap.required' => 'wajib mengisi nama lengkap!',
            'peg_nama_lengkap.max' => 'maximal nama lengkap adalah 255 huruf!',
            'peg_nama_lengkap.min' => 'minimal nama lengkap adalah 4 huruf!',
            // 'peg_gelar_depan.required' => 'wajib mengisi gelar depan!',
            // 'peg_gelar_belakang.required' => 'wajib mengisi gelar belakang!',
            'peg_nama_tanpa_gelar.required' => 'wajib mengisi nama tanpa gelar!',
            'peg_almamater_nama.required' => 'wajib mengisi nama almamater!',
            'peg_jenis_kelamin.required' => 'wajib mengisi jenis kelamin!',
            'peg_lahir_tempat.required' => 'wajib mengisi tempat lahir!',
            'peg_lahir_tanggal.required' => 'wajib mengisi tanggal lahir!',
            'peg_nip_nipt_nik.required' => 'wajib mengisi nip/nipt/nik!',
            'peg_nip_nipt_nik.max' => 'nip/nipt/nik maximal 50 karakter!',
            'peg_bpjs.required' => 'wajib mengisi nomor bpjs!',
            'peg_npwp.required' => 'wajib mengisi npwp!',
            'peg_ktp.required' => 'wajib mengisi nomor ktp!',
            'peg_tmt.required' => 'wajib mengisi tmt!',
            'peg_tmt_golongan_akhir.required' => 'wajib mengisi tmt golongan akhir!',
            'peg_sk_pengangkatan.required' => 'wajib mengisi sk pengangkatan!',
            'peg_telp_hp.required' => 'wajib mengisi nomor telepon!',
            'peg_email.required' => 'wajib mengisi email!',
            'peg_rumah_alamat.required' => 'wajib mengisi alamat!',
            'peg_nama_provinsi.required' => 'wajib mengisi nama provinsi!',
            'peg_nama_kota.required' => 'wajib mengisi nama kota!',
            'peg_nama_kec.required' => 'wajib mengisi nama kecamatan!',
            'peg_kel_desa.required' => 'wajib mengisi nama kelurahan/desa!',
            'peg_alamat_rw.required' => 'wajib mengisi rw!',
            'peg_alamat_rt.required' => 'wajib mengisi rt!',
            'peg_kodepos.required' => 'wajib mengisi kodepos'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "fail",
                "message" => "Gagal Mengisi Data",
                "error" => $validator->errors(),
            ], 401);
        }

        $user = $req->user();
        
        $model = Pegawai::findOrFail($user->id)->with(['bidang', 
            'golongan', 
            'ijazah', 
            'jabatan', 
            'jenisJabatan', 
            'jenisTenagaKerja', 
            'jenjangJabatan',
            'kualifikasiPendidikan',
            'pendidikan',
            'sip',
            'spesialis',
            'statusPegawai',
            'str',
            'unitKerja'
        ]);

        $model->peg_nama_lengkap = $req->peg_nama_lengkap;
        $model->peg_nama_tanpa_gelar = $req->peg_nama_tanpa_gelar;
        $model->peg_almamater_nama = $req->peg_almamater_nama;
        $model->peg_jenis_kelamin = $req->peg_jenis_kelamin;
        $model->peg_lahir_tempat = $req->peg_lahir_tempat;
        $model->peg_lahir_tanggal = $req->peg_lahir_tanggal;
        $model->peg_nip_nipt_nik = $req->peg_nip_nipt_nik;
        $model->peg_usia = Carbon::parse($req->peg_lahir_tanggal)->diff(Carbon::now())->y;
        $model->peg_bpjs = $req->peg_bpjs;
        $model->peg_npwp = $req->peg_npwp;
        $model->peg_ktp = $req->peg_ktp;
        $model->peg_tmt = $req->peg_tmt;
        $model->peg_tmt_golongan_akhir = $req->peg_tmt_golongan_akhir;
        $model->peg_masa_kerja= Carbon::parse($req->peg_tmt)->diff(Carbon::now())->y;
        $model->peg_sk_pengangkatan = $req->peg_sk_pengangkatan;
        $model->peg_telp_hp = $req->peg_telp_hp;
        $model->peg_telp_hp = $req->peg_telp_hp;
        $model->peg_email = $req->peg_email;
        $model->peg_rumah_alamat = $req->peg_rumah_alamat;
        $model->peg_nama_provinsi = $req->peg_nama_provinsi;
        $model->peg_nama_kota = $req->peg_nama_kota;
        $model->peg_nama_kec = $req->peg_nama_kec;
        $model->peg_kel_desa = $req->peg_kel_desa;
        $model->peg_alamat_rw = $req->peg_alamat_rw;
        $model->peg_alamat_rt = $req->peg_alamat_rt;
        $model->peg_kodepos = $req->peg_kodepos;

        $model->bidang_id = $req->bidang_id; 
        $model->golongan_id = $req->golongan_id; 
        $model->ijazah_id = $req->ijazah_id; 
        $model->jabatan_id = $req->jabatan_id; 
        $model->jenis_jabatan_id = $req->jenis_jabatan_id; 
        $model->jenis_tenaga_kerja_id = $req->jenis_tenaga_kerja_id; 
        $model->jenjang_jabatan_id = $req->jenjang_jabatan_id;
        $model->kualifikasi_pendidikan_id = $req->kualifikasi_pendidikan_id;
        $model->pendidikan_id = $req->pendidikan_id;
        $model->sip_id = $req->sip_id;
        $model->spesialis_id = $req->spesialis_id;
        $model->status_kepegawaian_id = $req->status_kepegawaian_id;
        $model->str_id = $req->str_id;
        $model->unit_kerja_id = $req->unit_kerja_id;

        $model->save();

        return response()->json([
            'model' => $model,
            'success' => true,
        ], 200);
    }
}
