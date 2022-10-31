<?php

namespace App\Http\Controllers;

use App\Models\ManajemenKontrak;
use Illuminate\Support\Facades\Auth;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;

class ManajemenKontrakController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = ManajemenKontrak::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(tugas) like (?)', ['%' . (strtolower($filter)) . '%']);
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
        // Validasi
        $this->validate(
            $req,
            [
                'jenis_kontrak' => ['required'],
                'tanggal_mulai' => ['required'],
                'tanggal_habis_kontrak' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'jenis_kontrak.required' => 'Jenis kontrak harus dipilih.',
                'tanggal_mulai.required' => 'Tanggal mulai kontrak harus diisi.',
                'tanggal_habis_kontrak.required' => 'Tanggal habis kontrak harus diisi.',
                'peg_id.required' => 'Pegawai harus dipilih.',
            ]
        );

        $model = ManajemenKontrak::create([
            'peg_id' => $req->peg_id,
            'jenis_kontrak' => $req->jenis_kontrak,
            'tanggal_mulai' => $req->tanggal_mulai,
            'tanggal_habis_kontrak' => $req->tanggal_habis_kontrak,
        ]);

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'jenis_kontrak' => $model->jenis_kontrak,
                'tanggal_mulai' => $model->tanggal_mulai,
                'tanggal_habis_kontrak' => $model->tanggal_habis_kontrak,
            ]);

        logAction('Tambah Manajemen Kontrak', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = ManajemenKontrak::where('id', $id)
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
                'jenis_kontrak' => ['required'],
                'tanggal_mulai' => ['required'],
                'tanggal_habis_kontrak' => ['required'],
            ],
            [
                'jenis_kontrak.required' => 'Jenis kontrak harus dipilih.',
                'tanggal_mulai.required' => 'Tanggal mulai kontrak harus diisi.',
                'tanggal_habis_kontrak.required' => 'Tanggal habis kontrak harus diisi.',
            ]
        );

        $model = ManajemenKontrak::findOrFail($id);
        $model->update([
            'jenis_kontrak' => $req->jenis_kontrak,
            'tanggal_mulai' => $req->tanggal_mulai,
            'tanggal_habis_kontrak' => $req->tanggal_habis_kontrak,
        ]);

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'jenis_kontrak' => $model->jenis_kontrak,
                'tanggal_mulai' => $model->tanggal_mulai,
                'tanggal_habis_kontrak' => $model->tanggal_habis_kontrak,
            ]);

        logAction('Update Manajemen Kontrak', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = ManajemenKontrak::findOrFail($id);
        $model->delete();

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'jenis_kontrak' => $model->jenis_kontrak,
                'tanggal_mulai' => $model->tanggal_mulai,
                'tanggal_habis_kontrak' => $model->tanggal_habis_kontrak,
            ]);

        logAction('Delete Manajemen Kontrak', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
