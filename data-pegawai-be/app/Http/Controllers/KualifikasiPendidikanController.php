<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\KualifikasiPendidikan;
use Illuminate\Support\Facades\Validator;

class KualifikasiPendidikanController extends Controller
{
    public function index(Request $req)
    {
        // $user = Auth::user();

        // if ($user->role_id == 1) {

        //     $filter = $req->filter;
        //     $sortby = $req->sortby;
        //     $sortdesc = $req->sortdesc;
        //     $params = $req->params;

        //     $models = KualifikasiPendidikan::query();

        //     if (!empty($filter)) {
        //         $filter = addslashes($filter);
        //         $models = $models->where(function ($q) use ($filter) {
        //             $q->whereRaw('LOWER(kualifikasi_pendidikan) like (?)', ['%' . (strtolower($filter)) . '%']);
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

        $models = KualifikasiPendidikan::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(kualifikasi_pendidikan) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                    'kualifikasi_pendidikan' => ['required', 'unique:kualifikasi_pendidikan,kualifikasi_pendidikan'],
                ],
                [
                    'kualifikasi_pendidikan.required' => 'Kualifikasi pendidikan harus diisi.',
                    'kualifikasi_pendidikan.unique' => 'Kualifikasi pendidikan sudah ada.',
                ]
            );

            $model = KualifikasiPendidikan::create([
                'kualifikasi_pendidikan' => $req->kualifikasi_pendidikan
            ]);

            $data = collect([]);
            $data->push([
                'kualifikasi_pendidikan' => $model->kualifikasi_pendidikan,
            ]);

            logAction('Tambah Kualifikasi Pendidikan', json_encode($data), $model->kualifikasi_pendidikan_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true,
                'model' => $model
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

            $model = KualifikasiPendidikan::where('kualifikasi_pendidikan_id', $id)
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
                    'kualifikasi_pendidikan' => ['required', Rule::unique('kualifikasi_pendidikan', 'kualifikasi_pendidikan')->ignore($id, 'kualifikasi_pendidikan_id')],
                ],
                [
                    'kualifikasi_pendidikan.required' => 'Kualifikasi pendidikan harus diisi.',
                    'kualifikasi_pendidikan.unique' => 'Kualifikasi pendidikan sudah ada.',
                ]
            );

            $model = KualifikasiPendidikan::findOrFail($id);
            $model->update([
                'kualifikasi_pendidikan' => $req->kualifikasi_pendidikan
            ]);

            $data = collect([]);
            $data->push([
                'kualifikasi_pendidikan' => $model->kualifikasi_pendidikan,
            ]);

            logAction('Update Kualifikasi Pendidikan', json_encode($data), $model->kualifikasi_pendidikan_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true,
                'model' => $model,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => '',
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role_id == 1) {

            $model = KualifikasiPendidikan::findOrFail($id);
            $model->delete();

            $data = collect([]);
            $data->push([
                'kualifikasi_pendidikan' => $model->kualifikasi_pendidikan,
            ]);

            logAction('Delete Kualifikasi Pendidikan', json_encode($data), $model->kualifikasi_pendidikan_id, Auth::user()->username, Auth::user()->peg_id);

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
        $models = KualifikasiPendidikan::selectRaw('kualifikasi_pendidikan_id as kualifikasi_pendidikan_id, kualifikasi_pendidikan as kualifikasi_pendidikan')->orderBy('kualifikasi_pendidikan', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
