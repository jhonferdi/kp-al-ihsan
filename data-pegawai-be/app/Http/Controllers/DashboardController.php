<?php

namespace App\Http\Controllers;

use App\Models\ManajemenKontrak;
use App\Models\Pegawai;
use App\Models\Sip;
use App\Models\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $count_male = Pegawai::where('peg_jenis_kelamin', 'L')->count();
        $count_female = Pegawai::where('peg_jenis_kelamin', 'P')->count();
        $count_contract = Pegawai::where('status_kepegawaian_id', 4)->count();
        $count_permanent = Pegawai::where('status_kepegawaian_id', 3)->count();
        $count_cpns = Pegawai::where('status_kepegawaian_id', 2)->count();
        $count_pns = Pegawai::where('status_kepegawaian_id', 1)->count();
        $sip_near_expired = Sip::with('pegawai', 'pegawai.jabatan')->where('tanggal_kadaluarsa_sip', '>=', Carbon::now()->translatedFormat('Y-m-d'))->orderBy('tanggal_kadaluarsa_sip', 'asc')->take(5)->get();
        $str_near_expired = Str::with('pegawai', 'pegawai.jabatan')->where('tanggal_kadaluarsa_str', '>=', Carbon::now()->translatedFormat('Y-m-d'))->orderBy('tanggal_kadaluarsa_str', 'asc')->take(5)->get();
        $contract_almost_over = ManajemenKontrak::with('pegawai')->where('tanggal_habis_kontrak', '>=', Carbon::now()->translatedFormat('Y-m-d'))->orderBy('tanggal_habis_kontrak', 'asc')->take(5)->get();
        $pegawai = Pegawai::all();
        $dataTmp = collect();
        foreach ($pegawai as $p) {
            $dataTmp->push([
                'name' => $p->peg_nama_lengkap,
                'images' => $p->peg_images,
                'pensiun' => $p->hitungTmtPensiun()
            ]);
        }
        $nearest_pension  = $dataTmp->where('pensiun', '>=', Carbon::now()->translatedFormat('Y-m-d'))->sortBy('pensiun')->values()->take(5);
        return response()->json([
            'count_male' => $count_male,
            'count_female' => $count_female,
            'count_contract' => $count_contract,
            'count_permanent' => $count_permanent,
            'count_cpns' => $count_cpns,
            'count_pns' => $count_pns,
            'sip_near_expired' => $sip_near_expired,
            'str_near_expired' => $str_near_expired,
            'contract_almost_over' => $contract_almost_over,
            'nearest_pension' => $nearest_pension
        ]);
    }
}
