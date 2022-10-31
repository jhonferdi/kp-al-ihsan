<?php

namespace App\Http\Controllers;

use App\Models\StatusPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StatusPegawaiController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = StatusPegawai::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(status_kepegawaian) like (?)', ['%' . (strtolower($filter)) . '%']);
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
            'success' > true,
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
                'status_kepegawaian' => ['required', 'unique:status_pegawai,status_kepegawaian'],
            ],
            [
                'status_kepegawaian.required' => 'Status kepegawaian harus diisi.',
                'status_kepegawaian.unique' => 'Status kepegawaian sudah ada.',
            ]
        );

        $model = StatusPegawai::create([
            'status_kepegawaian' => $req->status_kepegawaian
        ]);

        $data = collect([]);
        $data->push([
            'status_kepegawaian' => $model->status_kepegawaian,
        ]);

        logAction('Tambah Status Pegawai', json_encode($data), $model->status_kepegawaian_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = StatusPegawai::where('status_kepegawaian_id', $id)
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
                'status_kepegawaian' => ['required', Rule::unique('status_pegawai', 'status_kepegawaian')->ignore($id, 'status_kepegawaian_id')],
            ],
            [
                'status_kepegawaian.required' => 'Status kepegawaian harus diisi.',
                'status_kepegawaian.unique' => 'Status kepegawaian sudah ada.',
            ]
        );

        $model = StatusPegawai::findOrFail($id);
        $model->update([
            'status_kepegawaian' => $req->status_kepegawaian
        ]);

        $data = collect([]);
        $data->push([
            'status_kepegawaian' => $model->status_kepegawaian,
        ]);

        logAction('Update Status Pegawai', json_encode($data), $model->status_kepegawaian_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = StatusPegawai::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'status_kepegawaian' => $model->status_kepegawaian,
        ]);

        logAction('Delete Status Pegawai', json_encode($data), $model->status_kepegawaian_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ]);
    }

    public function getData()
    {
        $models = StatusPegawai::selectRaw('status_kepegawaian_id as status_kepegawaian_id, status_kepegawaian as status_kepegawaian')->orderBy('status_kepegawaian', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
