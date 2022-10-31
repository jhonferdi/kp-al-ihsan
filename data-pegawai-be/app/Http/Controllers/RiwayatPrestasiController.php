<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPrestasi;
use App\Models\DokumenDigital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RiwayatPrestasiController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatPrestasi::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nomor_sk) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'peg_id' => ['required'],
                'nama_prestasi' => ['required'],
                'pejabat_penandatangan' => ['required'],
                'tanggal' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nama_prestasi.required' => 'Nama Prestasi harus diisi.',
                'pejabat_penandatangan.required' => 'Pejabat Penandatangan harus diisi.',
                'tanggal.required' => 'Tanggal harus diisi.',
            ]
        );

        $model = RiwayatPrestasi::create([
            'peg_id' => $req->peg_id,
            'nama_prestasi' => $req->nama_prestasi,
            'pejabat_penandatangan' => $req->pejabat_penandatangan,
            'tanggal' => $req->tanggal,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_prestasi' => $model->nama_prestasi,
            'pejabat_penandatangan' => $model->pejabat_penandatangan,
            'tanggal' => $model->tanggal,
        ]);

        logAction('Tambah Riwayat Prestasi', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatPrestasi::where('id', $id)
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
                'peg_id' => ['required'],
                'nama_prestasi' => ['required'],
                'pejabat_penandatangan' => ['required'],
                'tanggal' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nama_prestasi.required' => 'Nama Prestasi harus diisi.',
                'pejabat_penandatangan.required' => 'Pejabat Penandatangan harus diisi.',
                'tanggal.required' => 'Tanggal harus diisi.',
            ]
        );

        $model = RiwayatPrestasi::findOrFail($id);
        $model->update([
            'nama_prestasi' => $req->nama_prestasi,
            'pejabat_penandatangan' => $req->pejabat_penandatangan,
            'tanggal' => $req->tanggal,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_prestasi' => $model->nama_prestasi,
            'pejabat_penandatangan' => $model->pejabat_penandatangan,
            'tanggal' => $model->tanggal,
        ]);

        logAction('Update Riwayat Prestasi', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatPrestasi::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_prestasi' => $model->nama_prestasi,
            'pejabat_penandatangan' => $model->pejabat_penandatangan,
            'tanggal' => $model->tanggal,
        ]);

        logAction('Delete Riwayat Prestasi', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
