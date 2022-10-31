<?php

namespace App\Http\Controllers;

use App\Models\RiwayatDiklat;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatDiklatController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatDiklat::query();

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
                'nama_pelatihan' => ['required'],
                'tanggal_pelaksanaan' => ['required'],
                'lama_pelatihan' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'nama_pelatihan.required' => 'Nama Pelatihan harus diisi.',
                'tanggal_pelaksanaan.required' => 'Tanggal pelaksanaan harus diisi.',
                'lama_pelatihan.required' => 'Lama pelatihan harus diisi.',
                'peg_id.required' => 'ID harus diisi.',
            ]
        );

        $model = RiwayatDiklat::create([
            'peg_id' => $req->peg_id,
            'nama_pelatihan' => $req->nama_pelatihan,
            'tanggal_pelaksanaan' => $req->tanggal_pelaksanaan,
            'lama_pelatihan' => $req->lama_pelatihan,
            'pejabat_penandatangan' => $req->pejabat_penandatangan,
            'nomor_sertifikat' => $req->nomor_sertifikat,
            'master_diklat_id' => $req->master_diklat_id,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_pelatihan' => $model->nama_pelatihan,
            'tanggal_pelaksanaan' => $model->tanggal_pelaksanaan,
            'lama_pelatihan' => $model->lama_pelatihan,
            'pejabat_penandatangan' => $model->pejabat_penandatangan,
            'master_diklat_id' => $model->master_diklat_id,
        ]);

        logAction('Tambah Riwayat Diklat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatDiklat::where('id', $id)
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
                'nama_pelatihan' => ['required'],
                'tanggal_pelaksanaan' => ['required'],
                'lama_pelatihan' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'nama_pelatihan.required' => 'Nama Pelatihan harus diisi.',
                'tanggal_pelaksanaan.required' => 'Tanggal pelaksanaan harus diisi.',
                'lama_pelatihan.required' => 'Lama pelatihan harus diisi.',
                'peg_id.required' => 'ID harus diisi.',
            ]
        );

        $model = RiwayatDiklat::findOrFail($id);
        $model->update([
            'nama_pelatihan' => $req->nama_pelatihan,
            'tanggal_pelaksanaan' => $req->tanggal_pelaksanaan,
            'lama_pelatihan' => $req->lama_pelatihan,
            'pejabat_penandatangan' => $req->pejabat_penandatangan,
            'nomor_sertifikat' => $req->nomor_sertifikat,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_pelatihan' => $model->nama_pelatihan,
            'tanggal_pelaksanaan' => $model->tanggal_pelaksanaan,
            'lama_pelatihan' => $model->lama_pelatihan,
            'pejabat_penandatangan' => $model->pejabat_penandatangan,
            'master_diklat_id' => $model->master_diklat_id,
        ]);

        logAction('Update Riwayat Diklat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatDiklat::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_pelatihan' => $model->nama_pelatihan,
            'tanggal_pelaksanaan' => $model->tanggal_pelaksanaan,
            'lama_pelatihan' => $model->lama_pelatihan,
            'pejabat_penandatangan' => $model->pejabat_penandatangan,
            'master_diklat_id' => $model->master_diklat_id,
        ]);
        
        logAction('Delete Riwayat Diklat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
