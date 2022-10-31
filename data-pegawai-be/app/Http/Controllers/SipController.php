<?php

namespace App\Http\Controllers;

use App\Models\Sip;
use App\Models\DokumenDigital;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SipController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Sip::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nomor_sip) like (?)', ['%' . (strtolower($filter)) . '%']);
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

        foreach ($models as $model) {
            $x = $model->tanggal_kadaluarsa_sip;
            $y = date('Y-m-d');
            if ($x > $y) {
                DB::table('sip')->where('sip_id', $model->sip_id)->update(['status' => 1]);
            } else {
                DB::table('sip')->where('sip_id', $model->sip_id)->update(['status' => 0]);
            }
        }

        return response()->json([
            'success', true,
            'data' => $models,
            'count' => $count
        ], 200);
    }


    public function store(Request $req)
    {
        //Validasi
        $this->validate(
            $req,
            [
                'peg_id' => ['required'],
                'nomor_sip' => ['required', 'unique:sip,nomor_sip'],
                'tanggal_penerbitan' => ['required'],
                'tanggal_kadaluarsa_sip' => ['required'],
            ],
            [
                'peg_id.required' => 'Pegawai harus dipilih.',
                'nomor_sip.required' => 'Nomor SIP harus diisi.',
                'nomor_sip.unique' => 'Nomor SIP sudah ada.',
                'tanggal_penerbitan.required' => 'Tanggal penerbitan SIP harus diisi.',
                'tanggal_kadaluarsa_sip.required' => 'Tanggal kadaluarsa SIP harus diisi.',
            ]
        );

        $model = Sip::create([
            // 'nama_pegawai' => $req->nama_pegawai,
            'peg_id' => $req->peg_id,
            'nomor_sip' => $req->nomor_sip,
            'tanggal_penerbitan' => Carbon::parse($req->tanggal_penerbitan)->format('Y-m-d'),
            'tanggal_kadaluarsa_sip' => Carbon::parse($req->tanggal_kadaluarsa_sip)->format('Y-m-d'),
            // 'image' => $req->image->store('image/sip', 'public')
            'jenis_sip' => $req->jenis_sip,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sip' => $model->nomor_sip,
            'tanggal_penerbitan' => $model->tanggal_penerbitan,
            'tanggal_kadaluarsa' => $model->tanggal_kadaluarsa_sip,
            'jenis_sip' => $model->jenis_sip,
        ]);

        logAction('Tambah SIP', json_encode($data), $model->sip_id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model
        ], 201);
    }

    public function show($id)
    {
        $model = Sip::where('sip_id', $id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function update(Request $req, $id)
    {
        //Validasi
        $this->validate(
            $req,
            [
                'peg_id' => ['required'],
                'nomor_sip' => ['required', Rule::unique('sip', 'nomor_sip')->ignore($id, 'sip_id')],
                'tanggal_penerbitan' => ['required'],
                'tanggal_kadaluarsa_sip' => ['required'],
            ],
            [
                'peg_id.required' => 'Pegawai harus dipilih.',
                'nomor_sip.required' => 'Nomor SIP harus diisi.',
                'nomor_sip.unique' => 'Nomor SIP sudah ada.',
                'tanggal_penerbitan.required' => 'Tanggal penerbitan SIP harus diisi.',
                'tanggal_kadaluarsa_sip.required' => 'Tanggal kadaluarsa SIP harus diisi.',
            ]
        );

        $model = Sip::findOrFail($id);
        // if (isset($req->image)) {
        //     if (isset($req->imageOld)) {
        //         Storage::disk('public')->delete($req->imageOld);
        //         $image = $req->image->store('image/sip', 'public');
        //     }
        // } else {
        //     $image = $model->image;
        // }
        $model->update([
            'peg_id' => $req->peg_id,
            // 'nama_pegawai' => $req->nama_pegawai,
            'nomor_sip' => $req->nomor_sip,
            'tanggal_penerbitan' => Carbon::parse($req->tanggal_penerbitan)->format('Y-m-d'),
            'tanggal_kadaluarsa_sip' => Carbon::parse($req->tanggal_kadaluarsa_sip)->format('Y-m-d'),
            // 'image' => $image
            'jenis_sip' => $req->jenis_sip,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sip' => $model->nomor_sip,
            'tanggal_penerbitan' => $model->tanggal_penerbitan,
            'tanggal_kadaluarsa' => $model->tanggal_kadaluarsa_sip,
            'jenis_sip' => $model->jenis_sip,
        ]);

        logAction('Update SIP', json_encode($data), $model->sip_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model
        ], 200);
    }

    public function destroy($id)
    {
        $model = Sip::findOrFail($id);
        // if ($model->image) {
        //     Storage::disk('public')->delete($model->image);
        // }
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_sip' => $model->nomor_sip,
            'tanggal_penerbitan' => $model->tanggal_penerbitan,
            'tanggal_kadaluarsa' => $model->tanggal_kadaluarsa_sip,
            'jenis_sip' => $model->jenis_sip,
        ]);

        logAction('Delete SIP', json_encode($data), $model->sip_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
