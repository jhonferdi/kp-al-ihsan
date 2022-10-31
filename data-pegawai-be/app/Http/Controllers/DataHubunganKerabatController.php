<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\DataHubunganKerabat;
use Illuminate\Support\Facades\Auth;

class DataHubunganKerabatController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = DataHubunganKerabat::query();

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

        if (is_array($params)) {
            foreach ($params as $key => $val) {
                if (empty($val) && $val !== 0 || $val == 'null') continue;
                switch ($key) {
                    case 'peg_id':
                        $models->whereHas('pegawai', function ($q) use ($val) {
                            $q->where('peg_id', $val);
                        });
                        break;
                }
            }
            if ($params['peg_id'] === '0') {
                $models = $models->whereDoesntHave('pegawai');
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
        $this->validate(
            $req,
            [
                'peg_id' => ['required'],
                'nama' => ['required'],
                // 'unit_kerja' => ['required'],
                'hubungan_kerabat' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nama.required' => 'Nama harus diisi.',
                // 'unit_kerja.required' => 'Unit Kerja harus diisi.',
                'hubungan_kerabat.required' => 'Hubungan Kerabat harus diisi.',
            ]
        );
        $model = DataHubunganKerabat::create([
            'peg_id' => $req->peg_id,
            'unit_kerja' => $req->unit_kerja,
            'nama' => $req->nama,
            'hubungan_kerabat' => $req->hubungan_kerabat,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'unit_kerja' => $model->unit_kerja,
            'nama' => $model->nama,
            'hubungan_kerabat' => $model->hubungan_kerabat,
        ]);

        logAction('Tambah Data Hubungan Kerabat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = DataHubunganKerabat::where('id', $id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'model' => $model,
        ]);
    }

    public function update(Request $req, $id)
    {
        $this->validate(
            $req,
            [
                'peg_id' => ['required'],
                'nama' => ['required'],
                // 'unit_kerja' => ['required'],
                'hubungan_kerabat' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nama.required' => 'Nama harus diisi.',
                // 'unit_kerja.required' => 'Unit Kerja harus diisi.',
                'hubungan_kerabat.required' => 'Hubungan Kerabat harus diisi.',
            ]
        );
        $model = DataHubunganKerabat::findOrFail($id);
        $model->update([
            'peg_id' => $req->peg_id,
            'unit_kerja' => $req->unit_kerja,
            'nama' => $req->nama,
            'hubungan_kerabat' => $req->hubungan_kerabat,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'unit_kerja' => $model->unit_kerja,
            'nama' => $model->nama,
            'hubungan_kerabat' => $model->hubungan_kerabat,
        ]);

        logAction('Update Data Hubungan Kerabat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = DataHubunganKerabat::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'unit_kerja' => $model->unit_kerja,
            'nama' => $model->nama,
            'hubungan_kerabat' => $model->hubungan_kerabat,
        ]);

        logAction('Delete Data Hubungan Kerabat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
