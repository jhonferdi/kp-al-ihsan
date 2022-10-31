<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\JenisTenagaKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JenisTenagaKerjaController extends Controller
{

    public function index(Request $req)
    {
        // $user = Auth::user();

        // if ($user->role_id == 1 || $user->role_id == 2) {

        //     $filter = $req->filter;
        //     $sortby = $req->sortby;
        //     $sortdesc = $req->sortdesc;
        //     $params = $req->params;

        //     $models = JenisTenagaKerja::with(['tenagaKerja']);

        //     if (!empty($filter)) {
        //         $filter = addslashes($filter);
        //         $models = $models->where(function ($q) use ($filter) {
        //             $q->whereRaw('LOWER(jenis_tenaga_kerja_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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

        $models = JenisTenagaKerja::with(['tenagaKerja']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(jenis_tenaga_kerja_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                    'jenis_tenaga_kerja_nama' => ['required', 'unique:jenis_tenaga_kerja,jenis_tenaga_kerja_nama'],
                    'tenaga_kerja_id' => ['required']
                ],
                [
                    'jenis_tenaga_kerja_nama.required' => 'Nama tenaga kerja harus diisi.',
                    'jenis_tenaga_kerja_nama.unique' => 'Nama tenaga kerja sudah ada.',
                    'tenaga_kerja_id.required' => 'Jenis tenaga kerja harus dipilih.'
                ]
            );

            $model = JenisTenagaKerja::create([
                'jenis_tenaga_kerja_nama' => $req->jenis_tenaga_kerja_nama,
                'tenaga_kerja_id' => $req->tenaga_kerja_id,
            ]);

            $data = collect([]);
            $data->push([
                'jenis_tenaga_kerja_nama' => $model->jenis_tenaga_kerja_nama,
                'tenaga_kerja_id' => $model->tenaga_kerja_id,
            ]);

            logAction('Tambah Jenis Tenaga Kerja', json_encode($data), $model->jenis_tenaga_kerja_id, Auth::user()->username, Auth::user()->peg_id);

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

            $model = JenisTenagaKerja::with('tenagaKerja')->where('jenis_tenaga_kerja_id', $id)
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

        if ($user->role_id == 1 || $user->role_id == 2) {
            // Validasi
            $this->validate(
                $req,
                [
                    'jenis_tenaga_kerja_nama' => ['required', Rule::unique('jenis_tenaga_kerja', 'jenis_tenaga_kerja_nama')->ignore($id, 'jenis_tenaga_kerja_id')],
                    'tenaga_kerja_id' => ['required']
                ],
                [
                    'jenis_tenaga_kerja_nama.required' => 'Nama tenaga kerja harus diisi.',
                    'jenis_tenaga_kerja_nama.unique' => 'Nama tenaga kerja sudah ada.',
                    'tenaga_kerja_id.required' => 'Jenis tenaga kerja harus dipilih.'
                ]
            );

            $model = JenisTenagaKerja::findOrFail($id);
            $model->update([
                'jenis_tenaga_kerja_nama' => $req->jenis_tenaga_kerja_nama,
                'tenaga_kerja_id' => $req->tenaga_kerja_id,
            ]);

            $data = collect([]);
            $data->push([
                'jenis_tenaga_kerja_nama' => $model->jenis_tenaga_kerja_nama,
                'tenaga_kerja_id' => $model->tenaga_kerja_id,
            ]);

            logAction('Update Jenis Tenaga Kerja', json_encode($data), $model->jenis_tenaga_kerja_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'model' => $model,
                'success' => true
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

            $model = JenisTenagaKerja::findOrFail($id);
            $model->delete();

            $data = collect([]);
            $data->push([
                'jenis_tenaga_kerja_nama' => $model->jenis_tenaga_kerja_nama,
                'tenaga_kerja_id' => $model->tenaga_kerja_id,
            ]);

            logAction('Delete Jenis Tenaga Kerja', json_encode($data), $model->jenis_tenaga_kerja_id, Auth::user()->username, Auth::user()->peg_id);

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
        $models = JenisTenagaKerja::selectRaw('jenis_tenaga_kerja_id as jenis_tenaga_kerja_id, jenis_tenaga_kerja_nama as jenis_tenaga_kerja_nama')->orderBy('jenis_tenaga_kerja_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
