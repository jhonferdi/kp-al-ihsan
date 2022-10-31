<?php

namespace App\Http\Controllers;

use App\Models\Nira;
use Illuminate\Http\Request;
use App\Models\DokumenDigital;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NiraController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Nira::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nilai) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'nomor_nira' => ['required'],
                'tanggal_terdaftar' => ['required'],
                'tanggal_terbit' => ['required'],
                'peg_id' => ['required'],
            ],
            [
                'nomor_nira.required' => 'No nira harus diisi.',
                'tanggal_terdaftar.required' => 'Tanggal terdaftar harus diisi.',
                'tanggal_terbit.required' => 'Tanggal terbit harus diisi.',
                'peg_id.required' => 'ID Pegawai harus diisi.',
            ]
        );

        $model = Nira::create([
            'peg_id' => $req->peg_id,
            'nomor_nira' => $req->nomor_nira,
            'tanggal_terdaftar' => $req->tanggal_terdaftar,
            'tanggal_terbit' => $req->tanggal_terbit,
        ]);

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'nomor_nira' => $model->nomor_nira,
                'tanggal_terdaftar' => $model->tanggal_terdaftar,
                'tanggal_terbit' => $model->tanggal_terbit,
            ]);

        logAction('Tambah NIRA', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = Nira::where('id', $id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'model' => $model,
        ]);
    }


    public function update(Request $req, $id)
    {
        // Validasi
        // $this->validate(
        //     $req,
        //     [
        //         'nomor_nira' => ['required'],
        //         'tanggal_terdaftar' => ['required'],
        //     ],
        //     [
        //         'nilai.required' => 'Nama nilai harus diisi.',
        //         'tahun.required' => 'Nama tahun harus diisi.',
        //     ]
        // );

        $model = Nira::findOrFail($id);
        $model->update([
            'nomor_nira' => $req->nomor_nira,
            'tanggal_terdaftar' => $req->tanggal_terdaftar,
            'tanggal_terbit' => $req->tanggal_terbit,
        ]);

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'nomor_nira' => $model->nomor_nira,
                'tanggal_terdaftar' => $model->tanggal_terdaftar,
                'tanggal_terbit' => $model->tanggal_terbit,
            ]);

        logAction('Update NIRA', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = Nira::findOrFail($id);
        $model->delete();

        $data = collect([]);
            $data->push([
                'peg_id' => $model->peg_id,
                'nomor_nira' => $model->nomor_nira,
                'tanggal_terdaftar' => $model->tanggal_terdaftar,
                'tanggal_terbit' => $model->tanggal_terbit,
            ]);

        logAction('Delete NIRA', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
