<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GolonganController extends Controller
{
    public function index(Request $req)
    {
        // $user = Auth::user();

        // if ($user->role_id == 1) {

        //     $filter = $req->filter;
        //     $sortby = $req->sortby;
        //     $sortdesc = $req->sortdesc;
        //     $params = $req->params;

        //     $models = Golongan::query();

        //     if (!empty($filter)) {
        //         $filter = addslashes($filter);
        //         $models = $models->where(function ($q) use ($filter) {
        //             $q->whereRaw('LOWER(golongan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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

        $models = Golongan::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(golongan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                    'golongan_nama' => ['required', 'unique:golongan,golongan_nama'],
                ],
                [
                    'golongan_nama.required' => 'Nama golongan harus diisi.',
                    'golongan_nama.unique' => 'Nama golongan sudah ada.',
                ]
            );

            $model = Golongan::create([
                'golongan_nama' => $req->golongan_nama,
                'nama_pangkat' => $req->nama_pangkat
            ]);

            $data = collect([]);
            $data->push([
            'golongan_nama' => $model->golongan_nama,
            'nama_pangkat' => $model->nama_pangkat,
            ]);

            logAction('Tambah Golongan', json_encode($data), $model->golongan_id, Auth::user()->username, Auth::user()->peg_id);
            
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
            $model = Golongan::where('golongan_id', $id)
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
                    'golongan_nama' => ['required', Rule::unique('golongan', 'golongan_nama')->ignore($id, 'golongan_id')],
                ],
                [
                    'golongan_nama.required' => 'Nama golongan harus diisi.',
                    'golongan_nama.unique' => 'Nama golongan sudah ada.',
                ]
            );

            $model = Golongan::findOrFail($id);
            $model->update([
                'golongan_nama' => $req->golongan_nama,
                'nama_pangkat' => $req->nama_pangkat
            ]);

            $data = collect([]);
            $data->push([
                'golongan_nama' => $model->golongan_nama,
                'nama_pangkat' => $model->nama_pangkat,
            ]);

            logAction('Update Golongan', json_encode($data), $model->golongan_id, Auth::user()->username, Auth::user()->peg_id);

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

    public function destroy(Request $req, $id)
    {
        $user = Auth::user();

        if ($user->role_id == 1) {
            $model = Golongan::findOrFail($id);
            $model->delete();

            $data = collect([]);
            $data->push([
                'golongan_nama' => $model->golongan_nama,
                'nama_pangkat' => $model->nama_pangkat,
            ]);

            logAction('Delete Golongan', json_encode($data), $model->golongan_id, Auth::user()->username, Auth::user()->peg_id);

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
        $models = Golongan::selectRaw('golongan_id as golongan_id, golongan_nama as golongan_nama')->orderBy('golongan_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
