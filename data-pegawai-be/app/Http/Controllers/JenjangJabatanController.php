<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenjangJabatan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JenjangJabatanController extends Controller
{
    public function index(Request $req)
    {
        // $user = Auth::user();

        // if ($user->role_id == 1) {

        //     $filter = $req->filter;
        //     $sortby = $req->sortby;
        //     $sortdesc = $req->sortdesc;
        //     $params = $req->params;

        //     $models = JenjangJabatan::query();

        //     if (!empty($filter)) {
        //         $filter = addslashes($filter);
        //         $models = $models->where(function ($q) use ($filter) {
        //             $q->whereRaw('LOWER(jenjang_jabatan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
        //         'success' => false,
        //         'message' => 'Unauthorized'
        //     ], 403);
        // }
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = JenjangJabatan::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(jenjang_jabatan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                    'jenjang_jabatan_nama' => ['required', 'unique:jenjang_jabatan,jenjang_jabatan_nama'],
                ],
                [
                    'jenjang_jabatan_nama.required' => 'Nama jenjang jabatan harus diisi.',
                    'jenjang_jabatan_nama.unique' => 'Nama jenjang jabatan sudah ada.',
                ]
            );

            $model = JenjangJabatan::create([
                'jenjang_jabatan_nama' => $req->jenjang_jabatan_nama
            ]);

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

        if ($user->role_id == 1) {

            $model = JenjangJabatan::where('jenjang_jabatan_id', $id)
                ->firstOrFail();
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


    public function update(Request $req, $id)
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            // Validasi
            $this->validate(
                $req,
                [
                    'jenjang_jabatan_nama' => ['required', Rule::unique('jenjang_jabatan', 'jenjang_jabatan_nama')->ignore($id, 'jenjang_jabatan_id')],
                ],
                [
                    'jenjang_jabatan_nama.required' => 'Nama jenjang jabatan harus diisi.',
                    'jenjang_jabatan_nama.unique' => 'Nama jenjang jabatan sudah ada.',
                ]
            );

            $model = JenjangJabatan::findOrFail($id);
            $model->update([
                'jenjang_jabatan_nama' => $req->jenjang_jabatan_nama
            ]);

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

        if ($user->role_id == 1) {

            $model = JenjangJabatan::findOrFail($id);
            $model->delete();

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

    public function getData()
    {
        $models = JenjangJabatan::selectRaw('jenjang_jabatan_id as jenjang_jabatan_id, jenjang_jabatan_nama as jenjang_jabatan_nama')->orderBy('jenjang_jabatan_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
