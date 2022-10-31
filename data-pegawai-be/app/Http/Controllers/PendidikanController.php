<?php

namespace App\Http\Controllers;

use App\Models\Pendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PendidikanController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Pendidikan::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(pendidikan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'pendidikan_nama' => ['required', 'unique:pendidikan,pendidikan_nama'],
            ],
            [
                'pendidikan_nama.required' => 'Nama pendidikan harus diisi.',
                'pendidikan_nama.unique' => 'Nama pendidikan sudah ada.',
            ]
        );

        $model = Pendidikan::create([
            'pendidikan_nama' => $req->pendidikan_nama
        ]);

        $data = collect([]);
        $data->push([
            'pendidikan_nama' => $model->pendidikan_nama,
        ]);

        logAction('Tambah Pendidikan', json_encode($data), $model->pendidikan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = Pendidikan::where('pendidikan_id', $id)
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
                'pendidikan_nama' => ['required', Rule::unique('pendidikan', 'pendidikan_nama')->ignore($id, 'pendidikan_id')],
            ],
            [
                'pendidikan_nama.required' => 'Nama pendidikan harus diisi.',
                'pendidikan_nama.unique' => 'Nama pendidikan sudah ada.',
            ]
        );

        $model = Pendidikan::findOrFail($id);
        $model->update([
            'pendidikan_nama' => $req->pendidikan_nama
        ]);

        $data = collect([]);
        $data->push([
            'pendidikan_nama' => $model->pendidikan_nama,
        ]);

        logAction('Update Pendidikan', json_encode($data), $model->pendidikan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = Pendidikan::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'pendidikan_nama' => $model->pendidikan_nama,
        ]);

        logAction('Delete Pendidikan', json_encode($data), $model->pendidikan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function getData()
    {
        $models = Pendidikan::selectRaw('pendidikan_id as pendidikan_id, pendidikan_nama as pendidikan_nama')->orderBy('pendidikan_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
