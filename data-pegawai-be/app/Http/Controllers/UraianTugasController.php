<?php

namespace App\Http\Controllers;

use App\Models\UraianTugas;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UraianTugasController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = UraianTugas::query();

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
                'tugas' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'tugas.required' => 'Tugas harus diisi.',
                'peg_id.required' => 'ID Pegawai harus diisi.',
            ]
        );

        $model = UraianTugas::create([
            'peg_id' => $req->peg_id,
            'tugas' => $req->tugas,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tugas' => $model->tugas,
        ]);

        logAction('Tambah Uraian Tugas', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = UraianTugas::where('id', $id)
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
                'tugas' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'tugas.required' => 'Tugas harus diisi.',
                'peg_id.required' => 'ID Pegawai harus diisi.',
            ]
        );

        $model = UraianTugas::findOrFail($id);
        $model->update([
            'tugas' => $req->tugas
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tugas' => $model->tugas,
        ]);
        
        logAction('Update Uraian Tugas', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        // DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }


    public function destroy($id)
    {
        $model = UraianTugas::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tugas' => $model->tugas,
        ]);
        
        logAction('Delete Uraian Tugas', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
