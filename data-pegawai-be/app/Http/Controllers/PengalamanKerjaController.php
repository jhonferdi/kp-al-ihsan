<?php

namespace App\Http\Controllers;

use App\Models\PengalamanKerja;
use App\Models\DokumenDigital;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengalamanKerjaController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = PengalamanKerja::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(tugas) like (?)', ['%' . (strtolower($filter)) . '%']);
            });
        }

        if (!empty($sortby)) {
            if ($sortdesc == 'true') {
                $models = $models->orderByDesc($sortby);
            } else {
                $models = $models->orderBy($sortby);
            }
        }

        $page = $req->get('page', 1);
        $count = $models->count();
        $perpage = $req->perpage == 'all' ? $count : $req->perpage ?? 20;
        $models->skip(($page - 1) * $perpage)->take($perpage);
        $models = $models->get();

        return response()->json([
            'success' => 'true',
            'data' => $models,
            'count' => $count
        ], 200);
    }


    public function store(Request $req)
    {
        // Validasi
        $this->validate(
            $req,
            [
                'peg_id' => ['required'],
                'tempat_kerja' => ['required'],
                'jabatan' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'tempat_kerja.required' => 'Tempat Kerja harus diisi.',
                'jabatan.required' => 'Jabatan harus diisi.',
            ]
        );
        
        $tanggal_mulai = Carbon::parse($req['tanggal_mulai']);
        $tanggal_selesai = Carbon::parse($req['tanggal_selesai']) ;
        $tahun = $tanggal_selesai->diffInYears($tanggal_mulai);
        $bulan = $tanggal_selesai->diffInMonths($tanggal_mulai);
        $hitung_bulan = $bulan-($tahun * 12);
        $lama_bekerja = (($tahun <= 0) ?"" :" $tahun tahun ").
        (($hitung_bulan <= 0) ? "" :" $hitung_bulan bulan");
        $model = PengalamanKerja::create([
            'peg_id' => $req->peg_id,
            'tempat_kerja' => $req->tempat_kerja,
            'jabatan' => $req->jabatan,
            'has_paklaring' => $req->has_paklaring,
            'tanggal_mulai' => $req->tanggal_mulai,
            'tanggal_selesai' => $req->tanggal_selesai,
            'lama_kerja' => $lama_bekerja,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tempat_kerja' => $model->tempat_kerja,
            'jabatan' => $model->jabatan,
            'has_paklaring' => $model->has_paklaring,
            'lama_kerja' => $model->lama_kerja,
        ]);
        
        logAction('Tambah Pengalaman Kerja', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = PengalamanKerja::where('id', $id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'model' => $model,
        ]);
    }


    public function update(Request $req, $id)
    {
        // Validasi
        $this->validate(
            $req,
            [
                'peg_id' => ['required'],
                'tempat_kerja' => ['required'],
                'jabatan' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'tempat_kerja.required' => 'Tempat Kerja harus diisi.',
                'jabatan.required' => 'Jabatan harus diisi.',
            ]
        );

        $model = PengalamanKerja::findOrFail($id);
        $tanggal_mulai = Carbon::parse($req->tanggal_mulai);
        $tanggal_selesai = Carbon::parse($req->tanggal_selesai) ;
        $tahun = $tanggal_selesai->diffInYears($tanggal_mulai);
        $bulan = $tanggal_selesai->diffInMonths($tanggal_mulai);
        $hitung_bulan = $bulan-($tahun * 12);
        $lama_bekerja = (($tahun <= 0) ?"" :" $tahun tahun ").
        (($hitung_bulan <= 0) ? "" :" $hitung_bulan bulan");
        $model->update([
            'tempat_kerja' => $req->tempat_kerja,
            'jabatan' => $req->jabatan,
            'has_paklaring' => $req->has_paklaring,
            'lama_kerja' => "$lama_bekerja",
            'tanggal_mulai' => $req->tanggal_mulai,
            'tanggal_selesai' => $req->tanggal_selesai,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tempat_kerja' => $model->tempat_kerja,
            'jabatan' => $model->jabatan,
            'has_paklaring' => $model->has_paklaring,
            'lama_kerja' => $model->lama_kerja,
        ]);

        logAction('Update Pengalaman Kerja', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = PengalamanKerja::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tempat_kerja' => $model->tempat_kerja,
            'jabatan' => $model->jabatan,
            'has_paklaring' => $model->has_paklaring,
            'lama_kerja' => $model->lama_kerja,
        ]);

        logAction('Delete Pengalaman Kerja', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
