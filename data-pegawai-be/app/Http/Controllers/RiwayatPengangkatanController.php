<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPengangkatan;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPengangkatanController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatPengangkatan::query();

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
                'nomor_sk' => ['required'],
                'tanggal_sk' => ['required'],
                'pendidikan' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nomor_sk.required' => 'Nomor SK harus diisi.',
                'tanggal_sk.required' => 'Tanggal SK harus diisi.',
                'pendidikan.required' => 'Pendidikan harus diisi.',
            ]
        );

        $model = RiwayatPengangkatan::create([
            'peg_id' => $req->peg_id,
            'nomor_sk' => $req->nomor_sk,
            'tanggal_sk' => $req->tanggal_sk,
            'pendidikan' => $req->pendidikan,
            'pejabat_penandatangan' => $req->pejabat_penandatangan,
            'nama_jabatan' => $req->nama_jabatan,
            'instansi' => $req->instansi,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sk' => $model->nomor_sk,
            'tanggal_sk' => $model->tanggal_sk,
            'pendidikan' => $model->pendidikan,
            'nama_jabatan' => $model->nama_jabatan,
            'instalasi' => $model->instansi,
        ]);

        logAction('Tambah Riwayat Pengangkatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatPengangkatan::where('id', $id)
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
                'nomor_sk' => ['required'],
                'tanggal_sk' => ['required'],
                'pendidikan' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nomor_sk.required' => 'Nomor SK harus diisi.',
                'tanggal_sk.required' => 'Tanggal SK harus diisi.',
                'pendidikan.required' => 'Pendidikan harus diisi.',
            ]
        );

        $model = RiwayatPengangkatan::findOrFail($id);
        $model->update([
            'nomor_sk' => $req->nomor_sk,
            'tanggal_sk' => $req->tanggal_sk,
            'pendidikan' => $req->pendidikan,
            'pejabat_penandatangan' => $req->pejabat_penandatangan,
            'nama_jabatan' => $req->nama_jabatan,
            'instansi' => $req->instansi,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sk' => $model->nomor_sk,
            'tanggal_sk' => $model->tanggal_sk,
            'pendidikan' => $model->pendidikan,
            'nama_jabatan' => $model->nama_jabatan,
            'instalasi' => $model->instansi,
        ]);
        
        logAction('Update Riwayat Pengangkatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatPengangkatan::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sk' => $model->nomor_sk,
            'tanggal_sk' => $model->tanggal_sk,
            'pendidikan' => $model->pendidikan,
            'nama_jabatan' => $model->nama_jabatan,
            'instalasi' => $model->instansi,
        ]);

        logAction('Delete Riwayat Pengangkatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
