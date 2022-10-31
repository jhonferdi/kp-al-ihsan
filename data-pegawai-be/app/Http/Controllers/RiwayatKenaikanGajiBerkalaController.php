<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKenaikanGajiBerkala;
use App\Models\DokumenDigital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RiwayatKenaikanGajiBerkalaController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatKenaikanGajiBerkala::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nomor_sk) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'tahun' => ['required'],
                'bulan' => ['required'],
                'golongan' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'tahun.required' => 'Tahun harus diisi.',
                'bulan.required' => 'Bulan harus diisi.',
                'golongan.required' => 'Golongan harus diisi.',
            ]
        );

        $model = RiwayatKenaikanGajiBerkala::create([
            'peg_id' => $req->peg_id,
            'tahun' => $req->tahun,
            'bulan' => $req->bulan,
            'golongan' => $req->golongan,
            'masa_kerja_tahun' => $req->masa_kerja_tahun,
            'masa_kerja_bulan' => $req->masa_kerja_bulan,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
            'golongan' => $model->golongan,
            'masa_kerja_tahun' => $model->masa_kerja_tahun,
            'masa_kerja_bulan' => $model->masa_kerja_bulan,
        ]);

        logAction('Tambah Riwayat Kenaikan Gaji Berkala', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatKenaikanGajiBerkala::where('id', $id)
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
                'tahun' => ['required'],
                'bulan' => ['required'],
                'golongan' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'tahun.required' => 'Tahun harus diisi.',
                'bulan.required' => 'Bulan harus diisi.',
                'golongan.required' => 'Golongan harus diisi.',
            ]
        );

        $model = RiwayatKenaikanGajiBerkala::findOrFail($id);
        $model->update([
            'tahun' => $req->tahun,
            'bulan' => $req->bulan,
            'golongan' => $req->golongan,
            'masa_kerja_tahun' => $req->masa_kerja_tahun,
            'masa_kerja_bulan' => $req->masa_kerja_bulan,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
            'golongan' => $model->golongan,
            'masa_kerja_tahun' => $model->masa_kerja_tahun,
            'masa_kerja_bulan' => $model->masa_kerja_bulan,
        ]);

        logAction('Update Riwayat Kenaikan Gaji Berkala', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatKenaikanGajiBerkala::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
            'golongan' => $model->golongan,
            'masa_kerja_tahun' => $model->masa_kerja_tahun,
            'masa_kerja_bulan' => $model->masa_kerja_bulan,
        ]);

        logAction('Delete Riwayat Kenaikan Gaji Berkala', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
