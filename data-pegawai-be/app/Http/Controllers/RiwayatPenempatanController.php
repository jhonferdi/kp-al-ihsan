<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPenempatan;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPenempatanController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatPenempatan::query();

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
                'jabatan' => ['required'],
                'unit_kerja' => ['required'],
                'instansi' => ['required'],
                'tanggal_penempatan' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'jabatan.required' => 'Jabatan harus diisi.',
                'unit_kerja.required' => 'Unit Kerja harus diisi.',
                'instansi.required' => 'Instansi harus diisi.',
                'tanggal_penempatan.required' => 'Tanggal Penempatan harus diisi.',
            ]
        );

        $model = RiwayatPenempatan::create([
            'peg_id' => $req->peg_id,
            'jabatan' => $req->jabatan,
            'unit_kerja' => $req->unit_kerja,
            'instansi' => $req->instansi,
            'tanggal_penempatan' => $req->tanggal_penempatan,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'jabatan' => $model->jabatan,
            'unit_kerja' => $model->unit_kerja,
            'instalasi' => $model->instansi,
            'tanggal_penempatan' => $model->tanggal_penempatan,
        ]);

        logAction('Tambah Riwayat Penempatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = RiwayatPenempatan::where('id', $id)
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
                'jabatan' => ['required'],
                'unit_kerja' => ['required'],
                'instansi' => ['required'],
                'tanggal_penempatan' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'jabatan.required' => 'Jabatan harus diisi.',
                'unit_kerja.required' => 'Unit Kerja harus diisi.',
                'instansi.required' => 'Instansi harus diisi.',
                'tanggal_penempatan.required' => 'Tanggal Penempatan harus diisi.',
            ]
        );

        $model = RiwayatPenempatan::findOrFail($id);
        $model->update([
            'jabatan' => $req->jabatan,
            'unit_kerja' => $req->unit_kerja,
            'instansi' => $req->instansi,
            'tanggal_penempatan' => $req->tanggal_penempatan,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'jabatan' => $model->jabatan,
            'unit_kerja' => $model->unit_kerja,
            'tanggal_penempatan' => $model->tanggal_penempatan,
        ]);
        
        logAction('Update Riwayat Penempatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatPenempatan::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'jabatan' => $model->jabatan,
            'unit_kerja' => $model->unit_kerja,
            'tanggal_penempatan' => $model->tanggal_penempatan,
        ]);
        
        logAction('Delete Riwayat Penempatan', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);
        
        return response()->json([
            'success' => true
        ], 200);
    }
}
