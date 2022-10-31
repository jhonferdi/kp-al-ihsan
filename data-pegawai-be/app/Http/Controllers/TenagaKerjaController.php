<?php

namespace App\Http\Controllers;

use App\Models\TenagaKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TenagaKerjaController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = TenagaKerja::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(tenaga_kerja_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
            'success' => true,
            'data' => $models,
            'count' => $count
        ]);
    }


    public function store(Request $req)
    {
        // Validasi
        $this->validate(
            $req,
            [
                'tenaga_kerja_nama' => ['required', 'unique:tenaga_kerja,tenaga_kerja_nama'],
            ],
            [
                'tenaga_kerja_nama.required' => 'Tenaga kerja harus diisi.',
                'tenaga_kerja_nama.unique' => 'Tenaga kerja sudah ada.',
            ]
        );

        $model = TenagaKerja::create([
            'tenaga_kerja_nama' => $req->tenaga_kerja_nama
        ]);

        $data = collect([]);
        $data->push([
            'tenaga_kerja_nama' => $model->tenaga_kerja_nama,
        ]);

        logAction('Tambah Tenaga Kerja', json_encode($data), $model->tenaga_kerja_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = TenagaKerja::where('tenaga_kerja_id', $id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }


    public function update(Request $req, $id)
    {
        // Validasi
        $this->validate(
            $req,
            [
                'tenaga_kerja_nama' => ['required', Rule::unique('tenaga_kerja', 'tenaga_kerja_nama')->ignore($id, 'tenaga_kerja_id')],
            ],
            [
                'tenaga_kerja_nama.required' => 'Tenaga kerja harus diisi.',
                'tenaga_kerja_nama.unique' => 'Tenaga kerja sudah ada.',
            ]
        );

        $model = TenagaKerja::findOrFail($id);
        $model->update([
            'tenaga_kerja_nama' => $req->tenaga_kerja_nama
        ]);

        $data = collect([]);
        $data->push([
            'tenaga_kerja_nama' => $model->tenaga_kerja_nama,
        ]);

        logAction('Update Tenaga Kerja', json_encode($data), $model->tenaga_kerja_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = TenagaKerja::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'tenaga_kerja_nama' => $model->tenaga_kerja_nama,
        ]);

        logAction('Delete Tenaga Kerja', json_encode($data), $model->tenaga_kerja_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
