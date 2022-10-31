<?php

namespace App\Http\Controllers;

use App\Models\PenilaianKinerja;
use App\Models\DokumenDigital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PenilaianKinerjaController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = PenilaianKinerja::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nilai) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'tahun' => ['required'],
                'nilai' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'tahun.required' => 'Tahun harus diisi.',
                'nilai.required' => 'Nilai harus diisi.',
                'peg_id.required' => 'ID Pegawai harus diisi.',
            ]
        );

        $model = PenilaianKinerja::create([
            'peg_id' => $req->peg_id,
            'nilai' => $req->nilai,
            'tahun' => $req->tahun
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nilai' => $model->nilai,
            'tahun' => $model->tahun,
        ]);

        logAction('Tambah Penilaian Kinerja', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = PenilaianKinerja::where('id', $id)
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
                'tahun' => ['required'],
                'nilai' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'tahun.required' => 'Tahun harus diisi.',
                'nilai.required' => 'Nilai harus diisi.',
                'peg_id.required' => 'ID Pegawai harus diisi.',
            ]
        );

        $model = PenilaianKinerja::findOrFail($id);
        $model->update([
            'nilai' => $req->nilai,
            'tahun' => $req->tahun
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nilai' => $model->nilai,
            'tahun' => $model->tahun,
        ]);

        logAction('Update Penilaian Kinerja', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = PenilaianKinerja::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nilai' => $model->nilai,
            'tahun' => $model->tahun,
        ]);

        logAction('Delete Penilaian Kinerja', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
