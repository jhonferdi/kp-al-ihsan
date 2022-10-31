<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\JabatanFungsional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JabatanController extends Controller
{

    public function index(Request $req)
    {
        // $user = Auth::user();

        // if ($user->role_id == 1 || $user->role_id == 2) {

        //     $filter = $req->filter;
        //     $sortby = $req->sortby;
        //     $sortdesc = $req->sortdesc;
        //     $params = $req->params;

        //     $models = Jabatan::with(['jabatanFungsional']);

        //     if (!empty($filter)) {
        //         $filter = addslashes($filter);
        //         $models = $models->where(function ($q) use ($filter) {
        //             $q->whereRaw('LOWER(jabatan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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

        $models = Jabatan::with(['jabatanFungsional','unitKerja']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(jabatan_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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

        if ($user->role_id == 1 || $user->role_id == 2) {
            // Validasi
            $this->validate(
                $req,
                [
                    'jabatan_nama' => ['required', 'unique:jabatan,jabatan_nama'],
                    'jabatan_fungsional_id' => ['required']
                ],
                [
                    'jabatan_nama.required' => 'Nama jabatan harus diisi.',
                    'jabatan_nama.unique' => 'Nama jabatan sudah ada.',
                    'jabatan_fungsional_id.required' => 'Jabatan fungsional harus dipilih.',
                ]
            );

            $model = Jabatan::create([
                'jabatan_nama' => $req->jabatan_nama,
                'jabatan_fungsional_id' => $req->jabatan_fungsional_id,
            ]);

            $data = collect([]);
            $data->push([
                'jabatan_nama' => $model->jabatan_nama,
                'jabatan_fungsional_id' => $model->jabatan_fungsional_id,
            ]);
            
            logAction('Tambah Jabatan', json_encode($data), $model->jabatan_id, Auth::user()->username, Auth::user()->peg_id);
            
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

        if ($user->role_id == 1 || $user->role_id == 2) {

            $model = Jabatan::with('jabatanFungsional','unitKerja')->where('jabatan_id', $id)
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

        if ($user->role_id == 1 || $user->role_id == 2) {
            // Validasi
            $this->validate(
                $req,
                [
                    'jabatan_nama' => ['required', Rule::unique('jabatan', 'jabatan_nama')->ignore($id, 'jabatan_id')],
                    'jabatan_fungsional_id' => ['required']
                ],
                [
                    'jabatan_nama.required' => 'Nama jabatan harus diisi.',
                    'jabatan_nama.unique' => 'Nama jabatan sudah ada.',
                    'jabatan_fungsional_id.required' => 'Jabatan fungsional harus dipilih.',
                ]
            );

            $model = Jabatan::findOrFail($id);
            $model->update([
                'jabatan_nama' => $req->jabatan_nama,
                'jabatan_fungsional_id' => $req->jabatan_fungsional_id,
            ]);

            $data = collect([]);
            $data->push([
                'jabatan_nama' => $model->jabatan_nama,
                'jabatan_fungsional_id' => $model->jabatan_fungsional_id,
            ]);

            logAction('Update Jabatan', json_encode($data), $model->jabatan_id, Auth::user()->username, Auth::user()->peg_id);

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

        if ($user->role_id == 1 || $user->role_id == 2) {

            $model = Jabatan::findOrFail($id);
            $model->delete();

            $data = collect([]);
            $data->push([
                'jabatan_nama' => $model->jabatan_nama,
                'jabatan_fungsional_id' => $model->jabatan_fungsional_id,
            ]);

            logAction('Delete Jabatan', json_encode($data), $model->jabatan_id, Auth::user()->username, Auth::user()->peg_id);

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
        $models = Jabatan::selectRaw('jabatan_id as jabatan_id, jabatan_nama as jabatan_nama')->orderBy('jabatan_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
    public function getJabFung()
    {
        $jabatan_fungsional = JabatanFungsional::select('jabatan_fungsional_id', 'jabatan_fungsional_nama')->orderBy('jabatan_fungsional_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $jabatan_fungsional,
        ], 200);
    }
}
