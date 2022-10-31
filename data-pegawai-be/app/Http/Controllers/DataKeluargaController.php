<?php

namespace App\Http\Controllers;

use App\Models\DataKeluarga;
use Carbon\Carbon;
use App\Models\DokumenDigital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataKeluargaController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = DataKeluarga::query();

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
                'no_nik' => ['required'],
                'jenis_hubungan' => ['required'],
                'status_pernikahan' => ['required'],
                'tempat_lahir' => ['required'],
                'tanggal_lahir' => ['required'],
                'alamat' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nama.required' => 'Nama harus diisi.',
                'no_nik.required' => 'NO NIK harus diisi.',
                'jenis_hubungan.required' => 'Jenis Hubungan harus diisi.',
                'status_pernikahan.required' => 'Status Pernikahan harus diisi.',
                'tempat_lahir.required' => 'Tempat Lahir harus diisi.',
                'tanggal_lahir.required' => 'Tanggal Lahir harus diisi.',
                'alamat.required' => 'Alamat harus diisi.',
            ]
        );
        $model = DataKeluarga::create([
            'peg_id' => $req->peg_id,
            'nama' => $req->nama,
            'no_nik' => $req->no_nik,
            'jenis_hubungan' => $req->jenis_hubungan,
            'status_pernikahan' => $req->status_pernikahan,
            'tempat_lahir' => $req->tempat_lahir,
            'tanggal_lahir' => $req->tanggal_lahir,
            'alamat' => $req->alamat,
            'pendidikan' => $req->pendidikan,
            'status' => $req->status,
            'tempat_bekerja' => $req->tempat_bekerja,
            'no_bpjs' => $req->no_bpjs,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'no_nik' => $model->no_nik,
            'nama' => $model->nama,
            'jenis_hubungan' => $model->jenis_hubungan,
            'status_pernikahan' => $model->status_pernikahan,
            'tempat_lahir' => $model->tempat_lahir,
            'tanggal_lahir' => $model->tanggal_lahir,
        ]);

        logAction('Tambah Data Keluarga', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function show($id)
    {
        $model = DataKeluarga::where('id', $id)
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
                'no_nik' => ['required'],
                'jenis_hubungan' => ['required'],
                'status_pernikahan' => ['required'],
                'tempat_lahir' => ['required'],
                'tanggal_lahir' => ['required'],
                'alamat' => ['required'],
            ],
            [
                'peg_id.required' => 'Id harus diisi.',
                'nama.required' => 'Nama harus diisi.',
                'no_nik.required' => 'NO NIK harus diisi.',
                'jenis_hubungan.required' => 'Jenis Hubungan harus diisi.',
                'status_pernikahan.required' => 'Status Pernikahan harus diisi.',
                'tempat_lahir.required' => 'Tempat Lahir harus diisi.',
                'tanggal_lahir.required' => 'Tanggal Lahir harus diisi.',
                'alamat.required' => 'Alamat harus diisi.',
            ]
        );

        $model = DataKeluarga::findOrFail($id);
        $model->update([
            'nama' => $req->nama,
            'no_nik' => $req->no_nik,
            'jenis_hubungan' => $req->jenis_hubungan,
            'status_pernikahan' => $req->status_pernikahan,
            'tempat_lahir' => $req->tempat_lahir,
            'tanggal_lahir' => $req->tanggal_lahir,
            'alamat' => $req->alamat,
            'pendidikan' => $req->pendidikan,
            'status' => $req->status,
            'tempat_bekerja' => $req->tempat_bekerja,
            'no_bpjs' => $req->no_bpjs,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'no_nik' => $model->no_nik,
            'nama' => $model->nama,
            'jenis_hubungan' => $model->jenis_hubungan,
            'status_pernikahan' => $model->status_pernikahan,
            'tempat_lahir' => $model->tempat_lahir,
            'tanggal_lahir' => $model->tanggal_lahir,
        ]);

        logAction('Update Data Keluarga', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function destroy($id)
    {
        $model = DataKeluarga::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'no_nik' => $model->no_nik,
            'nama' => $model->nama,
            'jenis_hubungan' => $model->jenis_hubungan,
            'status_pernikahan' => $model->status_pernikahan,
            'tempat_lahir' => $model->tempat_lahir,
            'tanggal_lahir' => $model->tanggal_lahir,
        ]);

        logAction('Delete Data Keluarga', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
