<?php

namespace App\Http\Controllers;

use App\Models\RiwayatRkkSpkkJk;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatRkkController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatRkkSpkkJk::query();

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
                'bulan' => ['required'],
                'tahun' => ['required'],
            ],
            [
                'bulan.required' => 'Bulan harus diisi.',
                'tahun.required' => 'Tahun harus diisi.',
            ]
        );

        $model = RiwayatRkkSpkkJk::create([
            'peg_id' => $req->peg_id,
            'bulan' => $req->bulan,
            'tahun' => $req->tahun,
            'master_rkk_spkk_jk_id' => $req->master_rkk_spkk_jk_id,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
            'master_rkk_id' => $model->master_rkk_spkk_jk_id,
        ]);

        logAction('Tambah Riwayat RKK & SPKK PK', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatRkkSpkkJk::where('id', $id)
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
                'bulan' => ['required'],
                'tahun' => ['required'],
            ],
            [
                'bulan.required' => 'Bulan harus diisi.',
                'tahun.required' => 'Tahun harus diisi.',
            ]
        );

        $model = RiwayatRkkSpkkJk::findOrFail($id);
        $model->update([
            'bulan' => $req->bulan,
            'tahun' => $req->tahun,
            'master_rkk_spkk_jk_id' => $req->master_rkk_spkk_jk_id,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
            'master_rkk_id' => $model->master_rkk_spkk_jk_id,
        ]);

        logAction('Update Riwayat RKK & SPKK PK', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatRkkSpkkJk::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
            'master_rkk_id' => $model->master_rkk_spkk_jk_id,
        ]);

        logAction('Delete Riwayat RKK & SPKK PK', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
