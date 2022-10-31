<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecapController extends Controller
{
    public function index(Request $req)
    {
        $dataTable = Pegawai::select(
            "tenaga_kerja.tenaga_kerja_nama as tenaga_kerja_nama",
            "jenis_tenaga_kerja.jenis_tenaga_kerja_nama as jenis_tenaga_kerja_nama",
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'L' AND status_pegawai.status_kepegawaian = 'PNS'  then 1 else 0 end) as lpns "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'P' AND status_pegawai.status_kepegawaian = 'PNS'  then 1 else 0 end) as ppns "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin IN ('L','P') AND status_pegawai.status_kepegawaian = 'PNS'  then 1 else 0 end) as jmlpns "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'L' AND status_pegawai.status_kepegawaian = 'CPNS'  then 1 else 0 end) as lcpns "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'P' AND status_pegawai.status_kepegawaian = 'CPNS'  then 1 else 0 end) as pcpns "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin IN ('L','P') AND status_pegawai.status_kepegawaian = 'CPNS'  then 1 else 0 end) as jmlcpns "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'L' AND status_pegawai.status_kepegawaian = 'Tetap'  then 1 else 0 end) as ltetap "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'P' AND status_pegawai.status_kepegawaian = 'Tetap'  then 1 else 0 end) as ptetap "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin IN ('L','P') AND status_pegawai.status_kepegawaian = 'Tetap'  then 1 else 0 end) as jmltetap "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'L' AND status_pegawai.status_kepegawaian = 'Kontrak'  then 1 else 0 end) as lkontrak "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'P' AND status_pegawai.status_kepegawaian = 'Kontrak'  then 1 else 0 end) as pkontrak "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin IN ('L','P') AND status_pegawai.status_kepegawaian = 'Kontrak'  then 1 else 0 end) as jmlkontrak "),
        )
            ->join("jenis_tenaga_kerja", "jenis_tenaga_kerja.jenis_tenaga_kerja_id", "=", "pegawai.jenis_tenaga_kerja_id")
            ->join("tenaga_kerja", "tenaga_kerja.tenaga_kerja_id", "=", "jenis_tenaga_kerja.tenaga_kerja_id")
            ->join("status_pegawai", "status_pegawai.status_kepegawaian_id", "=", "pegawai.status_kepegawaian_id")
            ->groupBy("tenaga_kerja.tenaga_kerja_nama")
            ->groupBy("jenis_tenaga_kerja.jenis_tenaga_kerja_nama")
            ->orderBy("tenaga_kerja.tenaga_kerja_nama", "asc")
            ->get();
        $dataTable1 = collect([]);
        foreach ($dataTable as $dt1) {
            $dataTable1->push([
                'tenaga_kerja_nama' => $dt1->tenaga_kerja_nama,
                'jenis_tenaga_kerja_nama' => $dt1->jenis_tenaga_kerja_nama,
                'lpns' => $dt1->lpns,
                'ppns' => $dt1->ppns,
                'jmlpns' => $dt1->jmlpns,
                'lcpns' => $dt1->lcpns,
                'pcpns' => $dt1->pcpns,
                'jmlcpns' => $dt1->jmlcpns,
                'ltetap' => $dt1->ltetap,
                'ptetap' => $dt1->ptetap,
                'jmltetap' => $dt1->jmltetap,
                'lkontrak' => $dt1->lkontrak,
                'pkontrak' => $dt1->pkontrak,
                'jmlkontrak' => $dt1->jmlkontrak,
                'jumlah_total' => $dt1->jmlpns + $dt1->jmlcpns + $dt1->jmlkontrak + $dt1->jmltetap
            ]);
        }
        $jmlTotal = 0;
        $jmlTotalPns = 0;
        $jmlTotalCpns = 0;
        $jmlTotalTetap = 0;
        $jmlTotalKontrak = 0;
        foreach ($dataTable1 as $d) {
            $jmlTotal += $d['jumlah_total'];
            $jmlTotalPns += $d['jmlpns'];
            $jmlTotalCpns += $d['jmlcpns'];
            $jmlTotalTetap += $d['jmltetap'];
            $jmlTotalKontrak += $d['jmlkontrak'];
        }

        $dataTable2 = Pegawai::select(
            "spesialis.spesialis_nama as spesialis_nama",
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'L' then 1 else 0 end) as Pria "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'P' then 1 else 0 end) as Wanita "),
            DB::raw("count(*) as Jumlah"),
        )
            ->join("spesialis", "spesialis.spesialis_id", "=", "pegawai.spesialis_id")
            ->join("status_pegawai", "status_pegawai.status_kepegawaian_id", "=", "pegawai.status_kepegawaian_id")
            ->where("status_pegawai.status_kepegawaian", "=", "Tamu")
            ->groupBy("spesialis.spesialis_nama")
            ->orderBy("spesialis.spesialis_nama", "asc")
            ->get();

        $dataTable3 = Pegawai::select(
            "spesialis.spesialis_nama as spesialis_nama",
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'L' then 1 else 0 end) as Pria "),
            DB::raw("sum(case when pegawai.peg_jenis_kelamin = 'P' then 1 else 0 end) as Wanita "),
            DB::raw("count(*) as Jumlah"),
        )
            ->join("spesialis", "spesialis.spesialis_id", "=", "pegawai.spesialis_id")
            ->join("status_pegawai", "status_pegawai.status_kepegawaian_id", "=", "pegawai.status_kepegawaian_id")
            ->whereIn("status_pegawai.status_kepegawaian", ["PNS", "Tetap", "Kontrak"])
            ->groupBy("spesialis.spesialis_nama")
            ->orderBy("spesialis.spesialis_nama", "asc")
            ->get();


        return response()->json([
            'dataTable1' => $dataTable1,
            'dataTable2' => $dataTable2,
            'dataTable3' => $dataTable3,
            'jmlTotal' => $jmlTotal,
            'jmlTotalPns' => $jmlTotalPns,
            'jmlTotalCpns' => $jmlTotalCpns,
            'jmlTotalTetap' => $jmlTotalTetap,
            'jmlTotalKontrak' => $jmlTotalKontrak,
        ]);
    }
}
