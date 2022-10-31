<?php

namespace App\Http\Controllers;

use App\Models\JenisJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JenisJabatanController extends Controller
{
    public function index(Request $req)
    {
        // $user = Auth::user();

        // if ($user->role_id == 1) {

        //     $filter = $req->filter;
        //     $sortby = $req->sortby;
        //     $sortdesc = $req->sortdesc;
        //     $params = $req->params;

        //     $models = JenisJabatan::query();

        //     if (!empty($filter)) {
        //         $filter = addslashes($filter);
        //         $models = $models->where(function ($q) use ($filter) {
        //             $q->whereRaw('LOWER(jenis_jabatan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
        //         });
        //     }

        //     if (!empty($sortby)) {
        //         if ($sortdesc == 'true') {
        //             $models = $models->orderByDesc($sortby);
        //         } else {
        //             $models = $models->orderBy($sortby);
        //         }
        //     }

        //     $page = $req->get('page', 1);
        //     $count = $models->count();
        //     $perpage = $req->perpage == 'all' ? $count : $req->perpage ?? 20;
        //     $models->skip(($page - 1) * $perpage)->take($perpage);
        //     $models = $models->get();

        //     return response()->json([
        //         'success' => true,
        //         'data' => $models,
        //         'count' => $count
        //     ], 200);
        // } else {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized'
        //     ], 403);
        // }
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = JenisJabatan::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(jenis_jabatan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
        $user = Auth::user();

        if ($user->role_id == 1) {
            // Validasi
            $this->validate(
                $req,
                [
                    'jenis_jabatan_nama' => ['required', 'unique:jenis_jabatan,jenis_jabatan_nama'],
                ],
                [
                    'jenis_jabatan_nama.required' => 'Nama jenis jabatan harus diisi.',
                    'jenis_jabatan_nama.unique' => 'Nama jenis jabatan sudah ada.',
                ]
            );

            $model = JenisJabatan::create([
                'jenis_jabatan_nama' => $req->jenis_jabatan_nama
            ]);

            $data = collect([]);
            $data->push([
                'jenis_jabatan_nama' => $model->jenis_jabatan_nama,
            ]);

            logAction('Tambah Jenis Jabatan', json_encode($data), $model->jenis_jabatan_id, Auth::user()->username, Auth::user()->peg_id);
            
            return response()->json([
                'success' => true,
                'model' => $model,
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role_id == 1) {

            $model = JenisJabatan::where('jenis_jabatan_id', $id)
                ->firstOrFail();
            return response()->json([
                'success' => true,
                'model' => $model,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }


    public function update(Request $req, $id)
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            // Validasi
            $this->validate(
                $req,
                [
                    'jenis_jabatan_nama' => ['required', Rule::unique('jenis_jabatan', 'jenis_jabatan_nama')->ignore($id, 'jenis_jabatan_id')],
                ],
                [
                    'jenis_jabatan_nama.required' => 'Nama jenis jabatan harus diisi.',
                    'jenis_jabatan_nama.unique' => 'Nama jenis jabatan sudah ada.',
                ]
            );

            $model = JenisJabatan::findOrFail($id);
            $model->update([
                'jenis_jabatan_nama' => $req->jenis_jabatan_nama
            ]);

            $data = collect([]);
            $data->push([
                'jenis_jabatan_nama' => $model->jenis_jabatan_nama,
            ]);

            logAction('Update Jenis Jabatan', json_encode($data), $model->jenis_jabatan_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true,
                'model' => $model,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role_id == 1) {

            $model = JenisJabatan::findOrFail($id);
            $model->delete();

            $data = collect([]);
            $data->push([
                'jenis_jabatan_nama' => $model->jenis_jabatan_nama,
            ]);

            logAction('Delete Jenis Jabatan', json_encode($data), $model->jenis_jabatan_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function getData()
    {
        $models = JenisJabatan::selectRaw('jenis_jabatan_id as jenis_jabatan_id, jenis_jabatan_nama as jenis_jabatan_nama')->orderBy('jenis_jabatan_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
