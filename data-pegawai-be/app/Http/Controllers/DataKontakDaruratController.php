<?php

namespace App\Http\Controllers;

use App\Models\DataKontakDarurat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataKontakDaruratController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = DataKontakDarurat::query();

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
                'nama' => ['required'],
                'hubungan_kerabat' => ['required'],
                'no_hp' => ['required'],
                'alamat' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nama.required' => 'Nama harus diisi.',
                'hubungan_kerabat.required' => 'Hubungan Kerabat harus diisi.',
                'no_hp.required' => 'No Handphone harus diisi.',
                'alamat.required' => 'Alamat harus diisi.',
            ]
        );
        $model = DataKontakDarurat::create([
            'peg_id' => $req->peg_id,
            'nama' => $req->nama,
            'hubungan_kerabat' => $req->hubungan_kerabat,
            'no_hp' => $req->no_hp,
            'alamat' => $req->alamat,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama' => $model->nama,
            'hubungan_kerabat' => $model->hubungan_kerabat,
            'no_hp' => $model->no_hp, 
        ]);

        logAction('Tambah Data Kontak Darurat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = DataKontakDarurat::where('id', $id)
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
                'nama' => ['required'],
                'hubungan_kerabat' => ['required'],
                'no_hp' => ['required'],
                'alamat' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nama.required' => 'Nama harus diisi.',
                'hubungan_kerabat.required' => 'Hubungan Kerabat harus diisi.',
                'no_hp.required' => 'No Handphone harus diisi.',
                'alamat.required' => 'Alamat harus diisi.',
            ]
        );

        $model = DataKontakDarurat::findOrFail($id);
        $model->update([
            'nama' => $req->nama,
            'hubungan_kerabat' => $req->hubungan_kerabat,
            'no_hp' => $req->no_hp,
            'alamat' => $req->alamat,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama' => $model->nama,
            'hubungan_kerabat' => $model->hubungan_kerabat,
            'no_hp' => $model->no_hp,
        ]);

        logAction('Update Data Kontak Darurat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = DataKontakDarurat::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama' => $model->nama,
            'hubungan_kerabat' => $model->hubungan_kerabat,
            'no_hp' => $model->no_hp,
        ]);

        logAction('Delete Data Kontak Darurat', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
