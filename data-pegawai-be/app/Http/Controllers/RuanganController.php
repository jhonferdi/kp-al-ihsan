<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RuanganController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Ruangan::with(['unitKerja']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nama_ruangan) like (?)', ['%' . (strtolower($filter)) . '%']);
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
        $perpage = $req->perpage == 'all' ? $count : $req->perpage ?? 100;
        $models->skip(($page - 1)* $perpage)->take($perpage);
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
                'nama_ruangan' => ['required', 'unique:ruangan,nama_ruangan'],
                'unit_kerja_id' => ['required'],
            ],
            [
                'nama_ruangan.required' => 'Nama ruangan harus diisi.',
                'nama_ruangan.unique' => 'Nama ruangan sudah ada.',
                'unit_kerja_id.required' => 'Unit Kerja Fungsional harus diisi.',
            ]
        );
        $unit_kerja = UnitKerja::find($req->unit_kerja_id);
        $model = Ruangan::create([
            'nama_ruangan' => $req->nama_ruangan,
            'unit_kerja_id' => $req->unit_kerja_id,
            'unit_kerja_nama' => $unit_kerja->unit_kerja_nama,
        ]);

        $data = collect([]);
        $data->push([
            'nama_ruangan' => $model->nama_ruangan,
            'unit_kerja_id' => $model->unit_kerja_id,
        ]);

        logAction('Tambah Ruangan', json_encode($data), $model->ruangan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function show($id)
    {
        $model = Ruangan::with('unitKerja')->where('ruangan_id', $id)
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
                'nama_ruangan' => ['required', Rule::unique('ruangan', 'nama_ruangan')->ignore($id, 'ruangan_id')],
                'unit_kerja_id' => ['required'],
            ],
            [
                'nama_ruangan.required' => 'Nama ruangan harus diisi.',
                'nama_ruangan.unique' => 'Nama ruangan sudah ada.',
                'unit_kerja_id.required' => 'Unit Kerja Fungsional harus diisi.',
            ]
        );
        $unit_kerja = UnitKerja::find($req->unit_kerja_id);
        $model = Ruangan::findOrFail($id);
        $model->update([
            'nama_ruangan' => $req->nama_ruangan,
            'unit_kerja_id' => $req->unit_kerja_id,
            'unit_kerja_nama' => $unit_kerja->unit_kerja_nama,
        ]);

        $data = collect([]);
        $data->push([
            'nama_ruangan' => $model->nama_ruangan,
            'unit_kerja_id' => $model->unit_kerja_id,
        ]);

        logAction('Update Ruangan', json_encode($data), $model->ruangan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = Ruangan::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'nama_ruangan' => $model->nama_ruangan,
            'unit_kerja_id' => $model->unit_kerja_id,
        ]);

        logAction('Delete Ruangan', json_encode($data), $model->ruangan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
