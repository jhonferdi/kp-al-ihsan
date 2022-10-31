<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAsesmen;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatAsesmenController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatAsesmen::query();

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
                'bulan' => ['required'],
                'tahun' => ['required'],
            ],
            [
                'peg_id.required' => 'ID harus diisi.',
                'bulan.required' => 'Bulan harus diisi.',
                'tahun.required' => 'Tahun harus diisi.',
            ]
        );

        $model = RiwayatAsesmen::create([
            'peg_id' => $req->peg_id,
            'bulan' => $req->bulan,
            'tahun' => $req->tahun,
            'master_asesmen_kompetensi_id' => $req->master_asesmen_kompetensi_id,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
        ]);

        logAction('Tambah Asesmen Kompetensi', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatAsesmen::where('id', $id)
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
                'bulan' => ['required'],
                'tahun' => ['required'],
            ],
            [
                'peg_id.required' => 'ID harus diisi.',
                'bulan.required' => 'Bulan harus diisi.',
                'tahun.required' => 'Tahun harus diisi.',
            ]
        );
        
        $model = RiwayatAsesmen::findOrFail($id);
        $model->update([
            'bulan' => $req->bulan,
            'tahun' => $req->tahun,
            'master_asesmen_kompetensi_id' => $req->master_asesmen_kompetensi_id,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
        ]);
        
        logAction('Update Asesmen Kompetensi', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatAsesmen::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'bulan' => $model->bulan,
            'tahun' => $model->tahun,
        ]);

        logAction('Delete Asesmen Kompetensi', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
