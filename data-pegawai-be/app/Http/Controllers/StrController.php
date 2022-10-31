<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Str;
use Illuminate\Http\Request;
use App\Models\DokumenDigital;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StrController extends Controller
{
    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = Str::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nomor_str) like (?)', ['%' . (strtolower($filter)) . '%']);
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
            $x = $model->tanggal_kadaluarsa_str;
            $y = date('Y-m-d');
            if ($x > $y) {
                DB::table('str')->where('str_id', $model->str_id)->update(['status' => 1]);
            } else {
                DB::table('str')->where('str_id', $model->str_id)->update(['status' => 0]);
            }
        }

        return response()->json([
            'success' => true,
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
                'nomor_str' => ['required', 'unique:str,nomor_str'],
                'tanggal_penerbitan' => ['required'],
                'tanggal_kadaluarsa_str' => ['required'],
                'jenis_str' => ['required'],
            ],
            [
                'peg_id.required' => 'Pegawai harus dipilih.',
                'nomor_str.required' => 'Nomor STR harus diisi.',
                'nomor_str.unique' => 'Nomor STR sudah ada.',
                'tanggal_penerbitan.required' => 'Tanggal penerbitan STR harus diisi.',
                'tanggal_kadaluarsa_str.required' => 'Tanggal kadaluarsa STR harus diisi.',
                'jenis_str.required' => 'Jenis STR harus diisi.',
            ]
        );

        $model = Str::create([
            'peg_id' => $req->peg_id,
            // 'peg_id' => $req->peg_id,
            'nomor_str' => $req->nomor_str,
            'tanggal_penerbitan' => Carbon::parse($req->tanggal_penerbitan)->format('Y-m-d'),
            'tanggal_kadaluarsa_str' => Carbon::parse($req->tanggal_kadaluarsa_str)->format('Y-m-d'),
            'jenis_str' => $req->jenis_str
            // 'image' => $req->image->store('image/str', 'public')
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_str' => $model->nomor_str,
            'tanggal_kadaluarsa' => $model->tanggal_kadaluarsa_str,
            'jenis_str' => $model->jenis_str,
        ]);

        logAction('Tambah STR', json_encode($data), $model->str_id, Auth::user()->username, Auth::user()->peg_id);

        DokumenDigital::updateEntityId($req->json()->all()['files'], $req->peg_id, $model->id);

        return response()->json([
            'success' => true,
            'model' => $model
        ], 201);
    }

    public function show($id)
    {
        $model = Str::where('str_id', $id)
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
                'nomor_str' => ['required', Rule::unique('str', 'nomor_str')->ignore($id, 'str_id')],
                'tanggal_penerbitan' => ['required'],
                'tanggal_kadaluarsa_str' => ['required'],
                'jenis_str' => ['required'],
            ],
            [
                'peg_id.required' => 'Pegawai harus dipilih.',
                'nomor_str.required' => 'Nomor STR harus diisi.',
                'nomor_str.unique' => 'Nomor STR sudah ada.',
                'tanggal_penerbitan.required' => 'Tanggal penerbitan STR harus diisi.',
                'tanggal_kadaluarsa_str.required' => 'Tanggal kadaluarsa STR harus diisi.',
                'jenis_str.required' => 'Jenis STR harus diisi.',
            ]
        );

        $model = Str::findOrFail($id);
        // if (isset($req->image)) {
        //     if (isset($req->imageOld)) {
        //         Storage::disk('public')->delete($req->imageOld);
        //         $image = $req->image->store('image/str', 'public');
        //     }
        // } else {
        //     $image = $model->image;
        // }
        $model->update([
            // 'peg_id' => $req->peg_id,
            'nomor_str' => $req->nomor_str,
            'tanggal_penerbitan' => Carbon::parse($req->tanggal_penerbitan)->format('Y-m-d'),
            'tanggal_kadaluarsa_str' => Carbon::parse($req->tanggal_kadaluarsa_str)->format('Y-m-d'),
            'jenis_str' => $req->jenis_str
            // 'image' => $image
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_str' => $model->nomor_str,
            'tanggal_kadaluarsa' => $model->tanggal_kadaluarsa_str,
            'jenis_str' => $model->jenis_str,
        ]);

        logAction('Update STR', json_encode($data), $model->str_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model
        ], 200);
    }

    public function destroy($id)
    {
        $model = Str::findOrFail($id);
        // if ($model->image) {
        //     Storage::disk('public')->delete($model->image);
        // }
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nomor_str' => $model->nomor_str,
            'tanggal_kadaluarsa' => $model->tanggal_kadaluarsa_str,
            'jenis_str' => $model->jenis_str,
        ]);

        logAction('Delete STR', json_encode($data), $model->str_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
}
