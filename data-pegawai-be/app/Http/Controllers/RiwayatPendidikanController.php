<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPendidikan;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPendidikanController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatPendidikan::query();

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
                'peg_id' => ['required'],
                'no_ijazah' => ['required'],
                'tanggal_ijazah' => ['required'],
                'nama_sekolah' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'no_ijazah.required' => 'No Ijazah harus diisi.',
                'tanggal_ijazah.required' => 'Tanggal Ijazah harus diisi.',
                'nama_sekolah.required' => 'Nama Sekolah harus diisi.',
            ]
        );

        $model = RiwayatPendidikan::create([
            'peg_id' => $req->peg_id,
            'no_ijazah' => $req->no_ijazah,
            'tanggal_ijazah' => $req->tanggal_ijazah,
            'nama_sekolah' => $req->nama_sekolah,
            'tanggal_lulus' => $req->tanggal_lulus,
            'pejabat_penandatangan_ijazah' => $req->pejabat_penandatangan_ijazah,
            'master_pendidikan_id' => $req->master_pendidikan_id,
            'surat_izin_belajar' => $req->surat_izin_belajar,
            'pejabat_penandatangan_izin_belajar' => $req->pejabat_penandatangan_izin_belajar,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'no_ijazah' => $model->no_ijazah,
            'nama_sekolah' => $model->nama_sekolah,
            'tanggal_lulus' => $model->tanggal_lulus,
            'surat_izin_belajar' => $model->surat_izin_belajar,
            'master_pendidikan_id' => $model->master_pendidikan_id,
        ]);

        logAction('Tambah Riwayat Pendidikan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatPendidikan::where('id', $id)
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
                'no_ijazah' => ['required'],
                'tanggal_ijazah' => ['required'],
                'nama_sekolah' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'no_ijazah.required' => 'No Ijazah harus diisi.',
                'tanggal_ijazah.required' => 'Tanggal Ijazah harus diisi.',
                'nama_sekolah.required' => 'Nama Sekolah harus diisi.',
            ]
        );

        $model = RiwayatPendidikan::findOrFail($id);
        $model->update([
            'no_ijazah' => $req->no_ijazah,
            'tanggal_ijazah' => $req->tanggal_ijazah,
            'nama_sekolah' => $req->nama_sekolah,
            'tanggal_lulus' => $req->tanggal_lulus,
            'pejabat_penandatangan_ijazah' => $req->pejabat_penandatangan_ijazah,
            'surat_izin_belajar' => $req->surat_izin_belajar,
            'pejabat_penandatangan_izin_belajar' => $req->pejabat_penandatangan_izin_belajar,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'no_ijazah' => $model->no_ijazah,
            'nama_sekolah' => $model->nama_sekolah,
            'tanggal_lulus' => $model->tanggal_lulus,
            'surat_izin_belajar' => $model->surat_izin_belajar,
            'master_pendidikan_id' => $model->master_pendidikan_id,
        ]);
        
        logAction('Update Riwayat Pendidikan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatPendidikan::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'no_ijazah' => $model->no_ijazah,
            'nama_sekolah' => $model->nama_sekolah,
            'tanggal_lulus' => $model->tanggal_lulus,
            'surat_izin_belajar' => $model->surat_izin_belajar,
            'master_pendidikan_id' => $model->master_pendidikan_id,
        ]);
        
        logAction('Delete Riwayat Pendidikan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
