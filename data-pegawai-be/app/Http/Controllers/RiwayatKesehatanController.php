<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKesehatan;
use Illuminate\Http\Request;

class RiwayatKesehatanController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatKesehatan::query();

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
                'penyakit' => ['required'],
                'keterangan' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'penyakit.required' => 'Penyakit harus dipilih.',
                'keterangan.required' => 'Keterangan harus diisi.',
                'peg_id.required' => 'ID harus diisi.',
            ]
        );

        $model = RiwayatKesehatan::create([
            'peg_id' => $req->peg_id,
            'penyakit' => $req->penyakit,
            'keterangan' => $req->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatKesehatan::where('id', $id)
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
                'penyakit' => ['required'],
                'keterangan' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'penyakit.required' => 'Penyakit harus dipilih.',
                'keterangan.required' => 'Keterangan harus diisi.',
                'peg_id.required' => 'ID harus diisi.',
            ]
        );

        $model = RiwayatKesehatan::findOrFail($id);
        $model->update([
            'penyakit' => $req->penyakit,
            'keterangan' => $req->keterangan,
        ]);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatKesehatan::findOrFail($id);
        $model->delete();

        return response()->json([
            'success' => true
        ], 200);
    }
}
