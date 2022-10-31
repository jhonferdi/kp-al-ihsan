<?php

namespace App\Http\Controllers;

use App\Models\Spesialis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SpesialisController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Spesialis::with(['subSpesialis']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(spesialis_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'spesialis_nama' => ['required', 'unique:spesialis,spesialis_nama'],
                // 'sub_spesialis_id' => ['required']
            ],
            [
                'spesialis_nama.required' => 'Nama spesialis harus diisi.',
                'spesialis_nama.unique' => 'Nama spesialis sudah ada.',
                // 'sub_spesialis_id.required' => 'Sub spesialis harus dipilih.'
            ]
        );

        $model = Spesialis::create([
            'spesialis_nama' => $req->spesialis_nama,
            'sub_spesialis_id' => $req->sub_spesialis_id,
        ]);

        $data = collect([]);
        $data->push([
            'spesialis_nama' => $model->spesialis_nama,
            'sub_spesialis_id' => $model->sub_spesialis_id,
        ]);

        logAction('Tambah Spesialis', json_encode($data), $model->spesialis_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = Spesialis::with('subSpesialis')->where('spesialis_id', $id)
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
                'spesialis_nama' => ['required', Rule::unique('spesialis', 'spesialis_nama')->ignore($id, 'spesialis_id')],
                // 'sub_spesialis_id' => ['required']
            ],
            [
                'spesialis_nama.required' => 'Nama spesialis harus diisi.',
                'spesialis_nama.unique' => 'Nama spesialis sudah ada.',
                // 'sub_spesialis_id' => 'Sub spesialis harus diisi.'
            ]
        );

        $model = Spesialis::findOrFail($id);
        $model->update([
            'spesialis_nama' => $req->spesialis_nama,
            'sub_spesialis_id' => $req->sub_spesialis_id,
        ]);

        $data = collect([]);
        $data->push([
            'spesialis_nama' => $model->spesialis_nama,
            'sub_spesialis_id' => $model->sub_spesialis_id,
        ]);

        logAction('Update Spesialis', json_encode($data), $model->spesialis_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = Spesialis::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'spesialis_nama' => $model->spesialis_nama,
            'sub_spesialis_id' => $model->sub_spesialis_id,
        ]);

        logAction('Delete Spesialis', json_encode($data), $model->spesialis_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function getData()
    {
        $models = Spesialis::selectRaw('spesialis_id as spesialis_id, spesialis_nama as spesialis_nama')->orderBy('spesialis_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
