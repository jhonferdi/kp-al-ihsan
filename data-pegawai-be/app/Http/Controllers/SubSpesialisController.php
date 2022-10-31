<?php

namespace App\Http\Controllers;

use App\Models\SubSpesialis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SubSpesialisController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = SubSpesialis::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(sub_spesialis_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
        ], 200);
    }

    public function store(Request $req)
    {
        // Validasi
        $this->validate(
            $req,
            [
                'spesialis_id' => ['required'],
                'sub_spesialis_nama' => ['required', 'unique:sub_spesialis,sub_spesialis_nama'],
            ],
            [
                'spesialis_id.required' => 'Spesialis harus dipilih.',
                'sub_spesialis_nama.required' => 'Sub spesialis harus diisi.',
                'sub_spesialis_nama.unique' => 'Sub spesialis sudah ada.',
            ]
        );

        $model = SubSpesialis::create([
            'spesialis_id' => $req->spesialis_id,
            'sub_spesialis_nama' => $req->sub_spesialis_nama
        ]);

        $data = collect([]);
        $data->push([
            'spesialis_id' > $model->spesialis_id,
            'sub_spesialis_nama' => $model->sub_spesialis_nama,
        ]);

        logAction('Tambah Sub Spesialis', json_encode($data), $model->sub_spesialis_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = SubSpesialis::where('sub_spesialis_id', $id)
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
                'spesialis_id' => ['required'],
                'sub_spesialis_nama' => ['required', Rule::unique('sub_spesialis', 'sub_spesialis_nama')->ignore($id, 'sub_spesialis_id')],
            ],
            [
                'spesialis_id.required' => 'Spesialis harus dipilih.',
                'sub_spesialis_nama.required' => 'Sub spesialis harus diisi.',
                'sub_spesialis_nama.unique' => 'Sub spesialis sudah ada.',
            ]
        );

        $model = SubSpesialis::findOrFail($id);
        $model->update([
            'spesialis_id' => $req->spesialis_id,
            'sub_spesialis_nama' => $req->sub_spesialis_nama
        ]);

        $data = collect([]);
        $data->push([
            'spesialis_id' > $model->spesialis_id,
            'sub_spesialis_nama' => $model->sub_spesialis_nama,
        ]);

        logAction('Update Sub Spesialis', json_encode($data), $model->sub_spesialis_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = SubSpesialis::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'spesialis_id' => $model->spesialis_id,
            'sub_spesialis_nama' => $model->sub_spesialis_nama,
        ]);

        logAction('Delete Sub Spesialis', json_encode($data), $model->sub_spesialis_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
