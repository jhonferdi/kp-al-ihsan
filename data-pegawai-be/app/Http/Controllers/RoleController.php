<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Role::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nama_role) like (?)', ['%' . (strtolower($filter)) . '%']);
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
            'success' => 'true',
            'data' => $models,
            'count' => $count
        ], 200);
    }


    public function store(Request $req)
    {
        // // Validasi
        // $this->validate(
        //     $req,
        //     [
        //         'peg_id' => ['required'],
        //         'jabatan' => ['required'],
        //         'unit_kerja' => ['required'],
        //         'tugas_tambahan' => ['required'],
        //     ],
        //     [
        //         'peg_id.required' => 'Id harus diisi.',
        //         'jabatan.required' => 'Jabatan harus diisi.',
        //         'unit_kerja.required' => 'Unit Kerja harus diisi.',
        //         'tugas_tambahan.required' => 'Tugas Tambahan harus diisi.',
        //     ]
        // );

        $model = Role::create([
            'role_id' => $req->role_id,
            'nama_role' => $req->nama_role,
        ]);

        // DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = Role::where('id', $id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'model' => $model,
        ]);
    }


    public function update(Request $req, $id)
    {
        // // Validasi
        // $this->validate(
        //     $req,
        //     [
        //         'peg_id' => ['required'],
        //         'jabatan' => ['required'],
        //         'unit_kerja' => ['required'],
        //         'tugas_tambahan' => ['required'],
        //     ],
        //     [
        //         'peg_id.required' => 'Id harus diisi.',
        //         'jabatan.required' => 'Jabatan harus diisi.',
        //         'unit_kerja.required' => 'Unit Kerja harus diisi.',
        //         'tugas_tambahan.required' => 'Tugas Tambahan harus diisi.',
        //     ]
        // );

        $model = Role::findOrFail($id);
        $model->update([
            'nama_role' => $req->nama_role,
        ]);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = Role::findOrFail($id);
        $model->delete();

        return response()->json([
            'success' => true
        ], 200);
    }
}
