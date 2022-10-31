<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\DokumenDigital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Pelanggaran::query();

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
                'nomor_surat' => ['required'],
                'pejabat_yang_mengeluarkan_bap' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nomor_surat.required' => 'Nomor Surat harus diisi.',
                'pejabat_yang_mengeluarkan_bap.required' => 'Pejabat yang mengeluarkan BAP harus diisi.',
            ]
        );

        $model = Pelanggaran::create([
            'peg_id' => $req->peg_id,
            'nomor_surat' => $req->nomor_surat,
            'pejabat_yang_mengeluarkan_bap' => $req->pejabat_yang_mengeluarkan_bap,
            'tahun_kejadian' => $req->tahun_kejadian,
            'jenis_pelanggaran' => $req->jenis_pelanggaran,
            'punishment' => $req->punishment,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_surat' => $model->nomor_surat,
            'pejabat' => $model->pejabat_yang_mengeluarkan_bap,
            'tahun_kejadian' => $model->tahun_kejadian,
            'jenis_pelanggaran' => $model->jenis_pelanggaran,
            'punisment' => $model->punisment,
        ]);

        logAction('Tambah Riwayat Pelanggaran', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = Pelanggaran::where('id', $id)
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
                'nomor_surat' => ['required'],
                'pejabat_yang_mengeluarkan_bap' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nomor_surat.required' => 'Nomor Surat harus diisi.',
                'pejabat_yang_mengeluarkan_bap.required' => 'Pejabat yang mengeluarkan BAP harus diisi.',
            ]
        );

        $model = Pelanggaran::findOrFail($id);
        $model->update([
            'nomor_surat' => $req->nomor_surat,
            'pejabat' => $req->pejabat_yang_mengeluarkan_bap,
            'tahun_kejadian' => $req->tahun_kejadian,
            'jenis_pelanggaran' => $req->jenis_pelanggaran,
            'punishment' => $req->punishment,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_surat' => $model->nomor_surat,
            'pejabat' => $model->pejabat_yang_mengeluarkan_bap,
            'tahun_kejadian' => $model->tahun_kejadian,
            'jenis_pelanggaran' => $model->jenis_pelanggaran,
            'punisment' => $model->punisment,
        ]);
        
        logAction('Update Riwayat Pelanggaran', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = Pelanggaran::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_surat' => $model->nomor_surat,
            'pejabat' => $model->pejabat_yang_mengeluarkan_bap,
            'tahun_kejadian' => $model->tahun_kejadian,
            'jenis_pelanggaran' => $model->jenis_pelanggaran,
            'punisment' => $model->punisment,
        ]);
        
        logAction('Delete Riwayat Pelanggaran', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
