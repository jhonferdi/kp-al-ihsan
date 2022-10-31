<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\RiwayatMcu;
use Illuminate\Http\Request;
use App\Models\DokumenDigital;
use Illuminate\Support\Facades\Auth;

class RiwayatMcuController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatMcu::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(jenis_pemeriksaan) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'tahun_mcu' => ['required'],
                'jenis_pemeriksaan' => ['required'],
            ],
            [
                'peg_id.required' => 'ID harus diisi.',
                'tahun_mcu.required' => 'Tahun harus diisi.',
                'jenis_pemeriksaan.required' => 'Jenis pemeriksaan harus diisi.',
            ]
        );
        $model = RiwayatMcu::create([
            'peg_id' => $req->peg_id,
            'tahun_mcu' => $req->tahun_mcu,
            'jenis_pemeriksaan' => $req->jenis_pemeriksaan,
        ]);
        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tahun_mcu' => $model->tahun_mcu,
            'jenis_pemeriksaan' => $model->jenis_pemeriksaan,
        ]);

        logAction('Tambah Riwayat MCU', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatMcu::where('id', $id)
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
                'tahun_mcu' => ['required'],
                'jenis_pemeriksaan' => ['required'],
            ],
            [
                'peg_id.required' => 'ID harus diisi.',
                'tahun_mcu.required' => 'Tahun harus diisi.',
                'jenis_pemeriksaan.required' => 'Jenis pemeriksaan harus diisi.',
            ]
        );

        $model = RiwayatMcu::findOrFail($id);

        $model->update([
            'status' => 'Y',
            'keterangan' => $req->keterangan,
            'tahun_mcu' => $req->tahun_mcu,
            'jenis_pemeriksaan' => $req->jenis_pemeriksaan,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tahun_mcu' => $model->tahun_mcu,
            'jenis_pemeriksaan' => $model->jenis_pemeriksaan,
        ]);

        logAction('Update Riwayat MCU', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatMcu::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'tahun_mcu' => $model->tahun_mcu,
            'jenis_pemeriksaan' => $model->jenis_pemeriksaan,
        ]);

        logAction('Delete Riwayat Kesehatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
