<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ijazah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class IjazahController extends Controller
{

    public function index(Request $req)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            $filter = $req->filter;
            $sortby = $req->sortby;
            $sortdesc = $req->sortdesc;
            $params = $req->params;

            $models = Ijazah::query();

            if (!empty($filter)) {
                $filter = addslashes($filter);
                $models = $models->where(function ($q) use ($filter) {
                    $q->whereRaw('LOWER(nomor_ijazah) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                'success' => true,
                'data' => $models,
                'count' => $count,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => '',
                'message' => 'Unauthorized'
            ], 403);
        }
    }


    public function store(Request $req)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            // Validasi
            $this->validate(
                $req,
                [
                    'nama_pegawai' => ['required'],
                    'nomor_ijazah' => ['required', 'unique:ijazah,nomor_ijazah'],
                    'tanggal_ijazah' => ['required'],
                    'image' => ['required', 'file', 'max:2048']
                ],
                [
                    'nama_pegawai.required' => 'Nama pegawai harus diisi.',
                    'nomor_ijazah.required' => 'Nomor ijazah harus diisi.',
                    'nomor_ijazah.unique' => 'Nomor ijazah sudah ada.',
                    'tanggal_ijazah.required' => 'Tanggal ijazah harus diisi.',
                    'image.required' => 'Ijazah harus diisi.',
                    'image.file' => 'Ijazah hanya bisa berisi file.',
                    'image.max:2048' => 'Ijazah maksimal berukuran 2048 Kb.',
                ]
            );

            $model = Ijazah::create([
                'nama_pegawai' => $req->nama_pegawai,
                'nomor_ijazah' => $req->nomor_ijazah,
                'tanggal_ijazah' => $req->tanggal_ijazah,
                'image' => $req->file('image')->store('image/ijazah', 'public'),
            ]);

            $data = collect([]);
            $data->push([
                'nama_pegawai' => $model->nama_pegawai,
                'nomor_ijazah' => $model->nomor_ijazah,
                'tanggal_ijazah' => $model->tanggal_ijazah,
            ]);

            logAction('Tambah Ijazah', json_encode($data), $model->ijazah_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true,
                'model' => $model
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            $model = Ijazah::where('ijazah_id', $id)
                ->firstOrFail();
            return response()->json([
                'success' => true,
                'model' => $model,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }


    public function update(Request $req, $id)
    {
        $user = Auth::user();
        if ($user->role_id == 1 || $user->role_id == 2) {
            // Validasi
            $this->validate(
                $req,
                [
                    'nama_pegawai' => ['required'],
                    'nomor_ijazah' => ['required', Rule::unique('ijazah', 'nomor_ijazah')->ignore($id, 'ijazah_id')],
                    'tanggal_ijazah' => ['required'],
                    'image' => ['max:2048']
                ],
                [
                    'nama_pegawai.required' => 'Nama pegawai harus diisi.',
                    'nomor_ijazah.required' => 'Nomor ijazah harus diisi.',
                    'nomor_ijazah.unique' => 'Nomor ijazah sudah ada.',
                    'tanggal_ijazah.required' => 'Tanggal ijazah harus diisi.',
                    'image.max:2048' => 'Ijazah maksimal berukuran 2048 Kb.',
                ]
            );

            $model = Ijazah::findOrFail($id);
            if (isset($req->image)) {
                if (isset($req->imageOld)) {
                    Storage::disk('public')->delete($req->imageOld);
                    $image = $req->image->store('image/ijazah', 'public');
                }
            } else {
                $image = $model->image;
            }
            $model->update([
                'nama_pegawai' => $req->nama_pegawai,
                'nomor_ijazah' => $req->nomor_ijazah,
                'tanggal_ijazah' => $req->tanggal_ijazah,
                'image' => $image,
            ]);

            $data = collect([]);
            $data->push([
                'nama_pegawai' => $model->nama_pegawai,
                'nomor_ijazah' => $model->nomor_ijazah,
                'tanggal_ijazah' => $model->tanggal_ijazah,
            ]);

            logAction('Update Ijazah', json_encode($data), $model->ijazah_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true,
                'model' => $model,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role_id == 1 || $user->role_id == 2) {
            $model = Ijazah::findOrFail($id);
            if ($model->image) {
                Storage::disk('public')->delete($model->image);
            }
            $model->delete();

            $data = collect([]);
            $data->push([
                'nama_pegawai' => $model->nama_pegawai,
                'nomor_ijazah' => $model->nomor_ijazah,
                'tanggal_ijazah' => $model->tanggal_ijazah,
            ]);

            logAction('Delete Ijazah', json_encode($data), $model->ijazah_id, Auth::user()->username, Auth::user()->peg_id);

            return response()->json([
                'success' => true,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
    }
}
