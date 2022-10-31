<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BidangController extends Controller
{

    public function index(Request $req)
    {
        // $user = Auth::user();
        // if ($user->role_id == 1) {

        //     $filter = $req->filter;
        //     $sortby = $req->sortby;
        //     $sortdesc = $req->sortdesc;
        //     $params = $req->params;

        //     $models = Bidang::query();

        //     if (!empty($filter)) {
        //         $filter = addslashes($filter);
        //         $models = $models->where(function ($q) use ($filter) {
        //             $q->whereRaw('LOWER(bidang_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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

        $models = Bidang::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(bidang_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                    'bidang_nama' => ['required', 'unique:bidang,bidang_nama'],
                ],
                [
                    'bidang_nama.required' => 'Nama bidang harus diisi.',
                    'bidang_nama.unique' => 'Nama bidang sudah ada.',
                ]
            );

            $model = Bidang::create([
                'bidang_nama' => $req->bidang_nama
            ]);
            $data = collect([]);
            $data->push([
                'bidang_nama' => $model->bidang_nama,
            ]);

            logAction('Tambah Bidang', json_encode($data), $model->bidang_id, Auth::user()->username, Auth::user()->peg_id);

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
            $model = Bidang::where('bidang_id', $id)
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
                    'bidang_nama' => ['required', Rule::unique('bidang', 'bidang_nama')->ignore($id, 'bidang_id')],
                ],
                [
                    'bidang_nama.required' => 'Nama bidang harus diisi.',
                    'bidang_nama.unique' => 'Nama bidang sudah ada.',
                ]
            );

            $model = Bidang::findOrFail($id);
            $model->update([
                'bidang_nama' => $req->bidang_nama
            ]);
            $data = collect([]);
            $data->push([
                'bidang_nama' => $model->bidang_nama,
            ]);

            logAction('Update Bidang', json_encode($data), $model->bidang_id, Auth::user()->username, Auth::user()->peg_id);

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

            $model = Bidang::findOrFail($id);
            $model->delete();
            $data = collect([]);
            $data->push([
                'bidang_nama' => $model->bidang_nama,
            ]);
            logAction('Delete Bidang', json_encode($data), $model->bidang_id, Auth::user()->username, Auth::user()->peg_id);
            
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
        $models = Bidang::selectRaw('bidang_id as bidang_id, bidang_nama as bidang_nama')->orderBy('bidang_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
