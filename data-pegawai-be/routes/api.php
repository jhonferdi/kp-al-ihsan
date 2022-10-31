<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\UserController as UserResourceController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BidangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\SpesialisController;
use App\Http\Controllers\JenisJabatanController;
use App\Http\Controllers\IjazahController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JabatanFungsionalController;
use App\Http\Controllers\JenisTenagaKerjaController;
use App\Http\Controllers\JenjangJabatanController;
use App\Http\Controllers\KualifikasiPendidikanController;
use App\Http\Controllers\PegawaiRuanganController;
use App\Http\Controllers\PendidikanController;
use App\Http\Controllers\RiwayatPangkatController;
use App\Http\Controllers\RiwayatTugasTambahanController;
use App\Http\Controllers\RiwayatKenaikanGajiBerkalaController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SipController;
use App\Http\Controllers\StatusPegawaiController;
use App\Http\Controllers\StrController;
use App\Http\Controllers\NiraController;
use App\Http\Controllers\TenagaKerjaController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UraianTugasController;
use App\Http\Controllers\PenilaianKinerjaController;
use App\Http\Controllers\PengalamanKerjaController;
use App\Http\Controllers\DataHubunganKerabatController;
use App\Http\Controllers\DokumenDigitalController;
use App\Http\Controllers\ManajemenKontrakController;
use App\Http\Controllers\RiwayatAsesmenController;
use App\Http\Controllers\RiwayatDiklatController;
use App\Http\Controllers\RiwayatPendidikanController;
use App\Http\Controllers\DataKontakDaruratController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\RiwayatPengangkatanController;
use App\Http\Controllers\RiwayatPrestasiController;
use App\Http\Controllers\DataKeluargaController;
use App\Http\Controllers\McuController;
use App\Http\Controllers\RiwayatPenempatanController;
use App\Http\Controllers\RiwayatRkkController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubSpesialisController;
use App\Http\Controllers\ActionLogController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\RiwayatMcuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//User


// Guest API
Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('forget-password', [UserController::class, 'forgetPassword']);

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset']);

    Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend']);

    Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
    Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});

// Auth API
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user/get-user', [UserController::class, 'getUser']);
    Route::get('user/get-options', [UserController::class, 'getOptions']);
    Route::put('user/reset-password/{id}', [UserController::class, 'resetPassword']);
    Route::put('user/edit/{id}', [UserController::class, 'update']);
    Route::delete('user/delete/{id}', [UserController::class, 'destroy']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::post('user', [UserController::class, 'store']);
    Route::get('user', [UserController::class, 'index']);
    Route::post('pegawai/dokumen-digital/{peg_id}/{master_dokumen_digital_id}/{entity_id?}', [DokumenDigitalController::class, 'doUpload']);

    // Settings
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::put('/password', [ProfileController::class, 'changePassword']);
    });

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::middleware(['cors'])->group(function () {
        // Pegawai
        Route::put('pegawai/update-utama/{id}', [PegawaiController::class, 'updateUtama']);
        Route::put('pegawai/update-profile/{id}', [PegawaiController::class, 'updateProfile']);
        Route::put('pegawai/update-about/{id}', [PegawaiController::class, 'updateAbout']);
        Route::get('pegawai/kabupaten-kota', [PegawaiController::class, 'getKabKot']);
        Route::get('pegawai/kecamatan', [PegawaiController::class, 'getKec']);
        Route::get('pegawai/kelurahan-desa', [PegawaiController::class, 'getKel']);
        Route::get('pegawai/jabatan-fungsional', [PegawaiController::class, 'getJabFung']);
        Route::get('pegawai/unit-kerja-unit-ruangan', [PegawaiController::class, 'getUnitKerjaUnitRuangan']);
        Route::get('pegawai/unit-kerja-ruangan', [PegawaiController::class, 'getUnitKerjaRuangan']);
        Route::get('pegawai/unit-kerja', [PegawaiController::class, 'getUnitKerja']);
        Route::get('pegawai/options', [PegawaiController::class, 'getOptions']);
        Route::get('pegawai/sub-spesialis', [PegawaiController::class, 'getSubSpes']);
        Route::post('pegawai/master-mcu', [McuController::class, 'saveMasterMcu']);
        Route::apiResource('pegawai', PegawaiController::class);

        //Pegawai Ruangan
        Route::get('pegawai-ruangan/option', [PegawaiRuanganController::class, 'getOption']);
        Route::apiResource('pegawai-ruangan', PegawaiRuanganController::class);

        // Jabatan
        Route::get('jabatan/jabatan-fungsional', [JabatanController::class, 'getJabFung']);
        Route::apiResource('jabatan', JabatanController::class);

        // Action Log
        Route::apiResource('action-log', ActionLogController::class);

        // Riwayat Mcu
        Route::apiResource('pegawai/riwayat-mcu', RiwayatMcuController::class);

        // Jenis Jabatan
        Route::apiResource('jenis-jabatan', JenisJabatanController::class);

        // Jenjang Jabatan
        Route::apiResource('jenjang-jabatan', JenjangJabatanController::class);

        // Unit Kerja
        Route::get('unit-kerja/list', [PegawaiController::class, 'getListUnitKerja']);
        Route::delete('unit-kerja/instalasi/{id}', [UnitKerjaController::class, 'destroyInstalasi']);
        Route::delete('unit-kerja/ruangan/{id}', [UnitKerjaController::class, 'destroyRuangan']);
        Route::apiResource('unit-kerja', UnitKerjaController::class);

        // Spesialis
        Route::apiResource('spesialis', SpesialisController::class);

        // Golongan
        Route::apiResource('golongan', GolonganController::class);

        // Kualifikasi Pendidikan
        Route::apiResource('kualifikasi-pendidikan', KualifikasiPendidikanController::class);

        // Bidang
        Route::apiResource('bidang', BidangController::class);

        // Jenis Tenaga Kerja
        Route::apiResource('jenis-tenaga-kerja', JenisTenagaKerjaController::class);

        // Status Pegawai
        Route::apiResource('status-pegawai', StatusPegawaiController::class);

        // Pendidikan
        Route::apiResource('pendidikan', PendidikanController::class);

        // Manajemen Kontrak
        Route::apiResource('pegawai/manajemen-kontrak', ManajemenKontrakController::class);

        // Pengalaman Kerja
        Route::apiResource('pegawai/pengalaman-kerja', PengalamanKerjaController::class);

        // Riwayat Pendidikan
        Route::apiResource('pegawai/riwayat-pendidikan', RiwayatPendidikanController::class);

        // Riwayat Kenaikan Gaji Berkala
        Route::apiResource('pegawai/riwayat-kenaikan-gaji-berkala', RiwayatKenaikanGajiBerkalaController::class);

        // NIRA
        Route::apiResource('pegawai/nira', NiraController::class);

        //Data Hubungan Kerabat
        Route::apiResource('pegawai/data-hubungan-kerabat', DataHubunganKerabatController::class);

        //Data Keluarga
        Route::apiResource('pegawai/data-keluarga', DataKeluargaController::class);

        //Data Kontak Darurat
        Route::apiResource('pegawai/data-kontak-darurat', DataKontakDaruratController::class);

        //Riwayat Pengangkatan
        Route::apiResource('pegawai/riwayat-pengangkatan', RiwayatPengangkatanController::class);

        //Riwayat Penempatan
        Route::apiResource('pegawai/riwayat-penempatan', RiwayatPenempatanController::class);

        //Riwayat Tugas Tambahan
        Route::apiResource('pegawai/riwayat-tugas-tambahan', RiwayatTugasTambahanController::class);

        //Riwayat Prestasi
        Route::apiResource('pegawai/riwayat-prestasi', RiwayatPrestasiController::class);

        // Riwayat Diklat
        Route::apiResource('pegawai/riwayat-diklat', RiwayatDiklatController::class);

        // Riwayat Asesmen
        Route::apiResource('pegawai/riwayat-asesmen', RiwayatAsesmenController::class);

        // Pelanggaran
        Route::apiResource('pegawai/pelanggaran', PelanggaranController::class);

        // Riwayat Kesehatan
        Route::apiResource('pegawai/riwayat-kesehatan', McuController::class);

        // Riwayat Kesehatan
        Route::apiResource('pegawai/riwayat-kesehatan-vaksin', McuController::class);

        // Riwayat RKK & SPKK JK
        Route::apiResource('pegawai/rkk', RiwayatRkkController::class);

        // Ijazah
        Route::apiResource('ijazah', IjazahController::class);

        // SIP
        Route::apiResource('sip', SipController::class);

        // STR
        Route::apiResource('str', StrController::class);

        // Sertifikat
        Route::apiResource('sertifikat', SertifikatController::class);

        // Uraian Tugas
        Route::apiResource('pegawai/uraian-tugas', UraianTugasController::class);

        // Penilaian Kinerja
        Route::apiResource('pegawai/penilaian-kinerja', PenilaianKinerjaController::class);
        
        //Rekapitulasi Pegawai
        Route::apiResource('rekapitulasi-pegawai', RecapController::class);

        //Jabatan Fungsional
        Route::apiResource('jabatan-fungsional', JabatanFungsionalController::class);
        
        //Ruangan
        Route::apiResource('ruangan',RuanganController::class);
        
        //Tenaga Kerja
        Route::apiResource('tenaga-kerja', TenagaKerjaController::class);
        
        //Sub Spesialis
        Route::apiResource('sub-spesialis', SubSpesialisController::class);
        
        //Riwayat Pangkat
        Route::apiResource('riwayat-pangkat', RiwayatPangkatController::class);
        
        //Role
        Route::apiResource('role', RoleController::class);
    });
});
