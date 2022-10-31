<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\JabatanFungsional;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JabatanFungsionalController extends Controller
{
    public function index(Request $req)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {

            $filter = $req->filter;
            $sortby = $req->sortby;
            $sortdesc = $req->sortdesc;
            $params = $req->params;

            $models = JabatanFungsional::query();

            if (!empty($filter)) {
                $filter = addslashes($filter);
                $models = $models->where(function ($q) use ($filter) {
                    $q->whereRaw('LOWER(jabatan_fungsional_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }


    public function store(Request $req)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            // Validasi
            $this->validate(
                $req,
                [
                    'jabatan_fungsional_nama' => ['required', 'unique:jabatan_fungsional,jabatan_fungsional_nama'],
                ],
                [
                    'jabatan_fungsional_nama.required' => 'Nama jenjang jabatan harus diisi.',
                    'jabatan_fungsional_nama.unique' => 'Nama jenjang jabatan sudah ada.',
                ]
            );

            $model = JabatanFungsional::create([
                'jabatan_fungsional_nama' => $req->jabatan_fungsional_nama
            ]);

            $data = collect([]);
            $data->push([
                'jabatan_fungsional_nama' => $model->jabatan_fungsional_nama,
            ]);

            logAction('Tambah Jabatan Fungsional', json_encode($data), $model->jabatan_fungsional_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true,
                'model' => $model,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {

            $model = JabatanFungsional::where('jabatan_fungsional_id', $id)
                ->firstOrFail();
            return response()->json([
                'success' => true,
                'model' => $model,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => '',
                'message' => 'Unauthorized'
            ], 403);
        }
    }


    public function update(Request $req, $id)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            // Validasi
            $this->validate(
                $req,
                [
                    'jabatan_fungsional_nama' => ['required', Rule::unique('jabatan_fungsional', 'jabatan_fungsional_nama')->ignore($id, 'jabatan_fungsional_id')],
                ],
                [
                    'jabatan_fungsional_nama.required' => 'Nama jenjang jabatan harus diisi.',
                    'jabatan_fungsional_nama.unique' => 'Nama jenjang jabatan sudah ada.',
                ]
            );

            $model = JabatanFungsional::findOrFail($id);
            $model->update([
                'jabatan_fungsional_nama' => $req->jabatan_fungsional_nama
            ]);

            $data = collect([]);
            $data->push([
                'jabatan_fungsional_nama' => $model->jabatan_fungsional_nama,
            ]);

            logAction('Update Jabatan Fungsional', json_encode($data), $model->jabatan_fungsional_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true,
                'model' => $model,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {

            $model = JabatanFungsional::findOrFail($id);
            $model->delete();

            $data = collect([]);
            $data->push([
                'jabatan_fungsional_nama' => $model->jabatan_fungsional_nama,
            ]);

            logAction('Delete Jabatan Fungsional', json_encode($data), $model->jabatan_fungsional_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }
}
