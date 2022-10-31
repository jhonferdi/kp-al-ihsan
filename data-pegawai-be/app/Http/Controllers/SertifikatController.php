<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SertifikatController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Sertifikat::with(['pegawai']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(kategori) like (?)', ['%' . (strtolower($filter)) . '%'])
                    ->orWhereRaw('LOWER(judul_sertifikat) like (?)', ['%' . (strtolower($filter)) . '%']);
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
            'success' => true,
            'data' => $models,
            'success' => true
        ], 200);
    }


    public function store(Request $req)
    {
        // Validasi
        $this->validate(
            $req,
            [
                'peg_id' => ['required'],
                'kategori' => ['required'],
                'judul_sertifikat' => ['required'],
                'tanggal_aktif' => ['required'],
                'image_sertifikat' => ['required', 'file', 'max:2048']
            ],
            [
                'peg_id.required' => 'Pegawai harus dipilih.',
                'kategori.required' => 'Kategori harus diisi.',
                'judul_sertifikat.required' => 'Judul sertifikat harus diisi.',
                'tanggal_aktif.required' => 'Tanggal aktif harus diisi.',
                'image_sertifikat.required' => 'File sertifikat harus diisi.',
                'image_sertifikat.file' => 'File sertifikat hanya bisa berisi file.',
                'image_sertifikat.max:2048' => 'File sertifikat maksimal berukuran 2048 Kb.',
            ]
        );

        // $model =  new Sertifikat();
        // $model->peg_id = $req->peg_id;
        // $model->kategori = $req->kategori;
        // $model->judul_sertifikat = $req->judul_sertifikat;
        // $model->tanggal_aktif = $req->tanggal_aktif;
        // $model->image_sertifikat = $req->image_sertifikat->store('image/sertifikat', 'public');
        // $model->save();
        $model = Sertifikat::create([
            'kategori' => $req->kategori,
            'judul_sertifikat' => $req->judul_sertifikat,
            'tanggal_aktif' => Carbon::parse($req->tanggal_aktif)->format('Y-m-d'),
            'image_sertifikat' => $req->image_sertifikat->store('image/sertifikat', 'public'),
            'peg_id' => $req->peg_id
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'kategori' => $model->kategori,
            'judul_sertifikat' => $model->judul_sertifikat,
            'tanggal_aktif' => $model->tanggal_aktif,
        ]);

        logAction('Tambah Sertifikat', json_encode($data), $model->sertifikat_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = Sertifikat::with('pegawai')->where('sertifikat_id', $id)
            ->firstOrFail();
        return response()->json([
            'model' => $model,
            'success' => true
        ], 200);
    }


    public function update(Request $req, $id)
    {
        // Validasi
        $this->validate(
            $req,
            [
                'kategori' => ['required'],
                'judul_sertifikat' => ['required'],
                'tanggal_aktif' => ['required'],
                'image_sertifikat' => ['max:2048']
            ],
            [
                'kategori.required' => 'Kategori harus diisi.',
                'judul_sertifikat.required' => 'Judul sertifikat harus diisi.',
                'tanggal_aktif.required' => 'Tanggal aktif harus diisi.',
                // 'image_sertifikat.required' => 'Sertifikat harus diisi.',
                // 'image_sertifikat.file' => 'Sertifikat hanya bisa berisi file.',
                'image_sertifikat.max:2048' => 'Sertifikat maksimal berukuran 2048 Kb.',
            ]
        );

        $model = Sertifikat::findOrFail($id);
        if (isset($req->image_sertifikat)) {
            if (isset($req->sertifikatOld)) {
                Storage::disk('public')->delete($req->sertifikatOld);
                $image_sertifikat = $req->image_sertifikat->store('image/sertifikat', 'public');
            }
        } else {
            $image_sertifikat = $model->image_sertifikat;
        }
        $model->update([
            'kategori' => $req->kategori,
            'judul_sertifikat' => $req->judul_sertifikat,
            'tanggal_aktif' => Carbon::parse($req->tanggal_aktif)->format('Y-m-d'),
            'image_sertifikat' => $image_sertifikat,
            'peg_id' => $req->peg_id
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'kategori' => $model->kategori,
            'judul_sertifikat' => $model->judul_sertifikat,
            'tanggal_aktif' => $model->tanggal_aktif,
        ]);

        logAction('Update Sertifikat', json_encode($data), $model->sertifikat_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = Sertifikat::findOrFail($id);
        if ($model->image_sertifikat) {
            Storage::disk('public')->delete($model->image_sertifikat);
        }
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'kategori' => $model->kategori,
            'judul_sertifikat' => $model->judul_sertifikat,
            'tanggal_aktif' => $model->tanggal_aktif,
        ]);

        logAction('Delete Sertifikat', json_encode($data), $model->sertifikat_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
