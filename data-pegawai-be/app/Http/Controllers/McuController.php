<?php

namespace App\Http\Controllers;

use App\Models\Mcu;
use App\Models\MasterMcu;
use App\Models\DokumenDigital;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class McuController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Mcu::query();

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
            ],
            [
                'peg_id.required' => 'ID harus diisi.',
            ]
        );
        if($req->tanggal_vaksin){
            $model = Mcu::create([
                'peg_id' => $req->peg_id,
                'tanggal_vaksin' => Carbon::parse($req->tanggal_vaksin)->toDateString(),
                'master_mcu_id' => $req->master_mcu_id,
            ]);
            DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);
        }else if($req->status) {
            $model = Mcu::create([
                'peg_id' => $req->peg_id,
                'status' => $req->status,
                'master_mcu_id' => $req->master_mcu_id,
            ]);
        }else{
            $model = Mcu::create([
                'peg_id' => $req->peg_id,
                'status' => 'Y',
                'keterangan' => $req->keterangan,
                'master_mcu_id' => $req->master_mcu_id,
            ]);
            DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);
        }

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'tanggal_vaksin' => $model->tanggal_vaksin,
                'status' => $model->status,
                'keterangan' => $model->keterangan,
                'master_mcu_id' => $model->master_mcu_id,
            ]);
        
        logAction('Tambah Riwayat Kesehatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = Mcu::where('id', $id)
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
            ],
            [
                'peg_id.required' => 'ID harus diisi.',
            ]
        );

        $model = Mcu::findOrFail($id);
        if($req->tanggal_vaksin){
            $model->update([
                'tanggal_vaksin' => Carbon::parse($req->tanggal_vaksin)->toDateString(),
            ]);
        }
        else{
            $model->update([
                'status' => $req->status,
                'keterangan' => $req->keterangan,
            ]);
        }

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'tanggal_vaksin' => $model->tanggal_vaksin,
                'status' => $model->status,
                'keterangan' => $model->keterangan,
                'master_mcu_id' => $model->master_mcu_id,
            ]);
        
        logAction('Update Riwayat Kesehatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = Mcu::findOrFail($id);
        $model->delete();

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'tanggal_vaksin' => $model->tanggal_vaksin,
                'status' => $model->status,
                'keterangan' => $model->keterangan,
                'master_mcu_id' => $model->master_mcu_id,
            ]);
        
        logAction('Delete Riwayat Kesehatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }

    public function saveMasterMcu(Request $req){
        $model = MasterMcu::create([
            'nama_penyakit' => $req->nama_penyakit,
            'kategori' => 'mcu',
        ]);

        $data = collect([]);
            $data->push([
                'nama_penyakit' => $model->nama_penyakit,
                'kategori' => $model->kategori,
            ]);
        
        logAction('Tambah Master Nama Penyakit', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }
}
