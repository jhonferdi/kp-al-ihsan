<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\RiwayatPangkat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RiwayatPangkatController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = RiwayatPangkat::with(['pegawai']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(tgl_pangkat) like (?)', ['%' . (strtolower($filter)) . '%'])
                    ->orWhereRaw('LOWER(nomor_sk) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'tgl_pangkat' => ['required'],
                'nomor_sk' => ['required', 'unique:riwayat_pangkat,nomor_sk'],
            ],
            [
                'peg_id.required' => 'Pegawai harus dipilih.',
                'tgl_pangkat.required' => 'Tanggal pangkat harus diisi.',
                'nomor_sk.required' => 'Nomor SK harus diisi.',
                'nomor_sk.unique' => 'Nomor SK sudah ada.',
            ]
        );
        $model = new RiwayatPangkat();
        $model->tgl_pangkat = Carbon::parse($req->tgl_pangkat)->format('Y-m-d');
        $model->nomor_sk = $req->nomor_sk;
        $model->peg_id = $req->peg_id;
        $model->save();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sk' => $model->nomor_sk,
            'tgl_pangkat' => $model->tgl_pangkat,
        ]);

        logAction('Tambah Riwayat Pangkat', json_encode($data), $model->riw_pangkat_id, Auth::user()->username, Auth::user()->peg_id);

        // $model = RiwayatPangkat::create([
        //     'peg_id' > $req->peg_id,
        //     'tgl_pangkat' => Carbon::parse($req->tgl_pangkat)->format('Y-m-d'),
        //     'nomor_sk' => $req->nomor_sk,
        // ]);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = RiwayatPangkat::with('pegawai')->where('riw_pangkat_id', $id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }


    public function update(Request $req, $id)
    {
        // Validasi
        $this->validate(
            $req,
            [
                'tgl_pangkat' => ['required'],
                'nomor_sk' => ['required', Rule::unique('riwayat_pangkat', 'nomor_sk')->ignore($id, 'riw_pangkat_id')],
            ],
            [
                'tgl_pangkat.required' => 'Tanggal pangkat harus diisi.',
                'nomor_sk.required' => 'Nomor SK harus diisi.',
                'nomor_sk.unique' => 'Nomor SK sudah ada.',
            ]
        );

        $model = RiwayatPangkat::findOrFail($id);
        $model->tgl_pangkat = Carbon::parse($req->tgl_pangkat)->format('Y-m-d');
        $model->nomor_sk = $req->nomor_sk;
        $model->peg_id = $req->peg_id;
        $model->save();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sk' => $model->nomor_sk,
            'tgl_pangkat' => $model->tgl_pangkat,
        ]);

        logAction('Update Riwayat Pangkat', json_encode($data), $model->riw_pangkat_id, Auth::user()->username, Auth::user()->peg_id);

        // $model->update([
        //     'tgl_pangkat' => Carbon::parse($req->tgl_pangkat)->format('Y-m-d'),
        //     'nomor_sk' => $req->nomor_sk,
        //     'peg_id' > $req->peg_id
        // ]);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = RiwayatPangkat::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sk' => $model->nomor_sk,
            'tgl_pangkat' => $model->tgl_pangkat,
        ]);

        logAction('Delete Riwayat Pangkat', json_encode($data), $model->riw_pangkat_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
