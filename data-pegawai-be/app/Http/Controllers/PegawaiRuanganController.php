<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Models\PegawaiRuangan;
use App\Models\Role;
use App\Models\RoleRuangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PegawaiRuanganController extends Controller
{

    public function index(Request $req)
    {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = PegawaiRuangan::with(['pegawai', 'ruangan','roleRuangan']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nama_pegawai) like (?)', ['%' . (strtolower($filter)) . '%'])
                    ->orWhereRaw('LOWER(role) like (?)', ['%' . (strtolower($filter)) . '%']);
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
                    case 'ruangan_id':
                        $models->whereHas('ruangan', function ($q) use ($val) {
                            $q->where('ruangan_id', $val);
                        });
                        break;
                }
            }
            if ($params['ruangan_id'] === '0') {
                $models = $models->whereDoesntHave('ruangan');
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
                'ruangan_id' => ['required'],
                'role_ruangan_id' => ['required'],
            ],
            [
                'peg_id.required' => 'Pegawai harus dipilih.',
                'ruangan_id.required' => 'Ruangan harus dipilih.',
                'role_ruangan_id.required' => 'Role harus diisi.',
            ]
        );
        $pegawai = Pegawai::find($req->peg_id);
        $model = PegawaiRuangan::create([
            'peg_id' => $req->peg_id,
            'nama_pegawai' => $pegawai->peg_nama_lengkap,
            'ruangan_id' => $req->ruangan_id,
            'role_ruangan_id' => $req->role_ruangan_id,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_pegawai' => $model->nama_pegawai,
            'ruangan_id' => $model->ruangan_id,
            'role_ruangan_id' => $model->role_ruangan_id,
        ]);

        logAction('Tambah Pegawai Ruangan', json_encode($data), $model->peg_ruangan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = PegawaiRuangan::with('pegawai', 'ruangan')->where('peg_ruangan_id', $id)
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
                'peg_id' => ['required'],
                'ruangan_id' => ['required'],
                'role_ruangan_id' => ['required'],
            ],
            [
                'peg_id.required' => 'Pegawai harus dipilih.',
                'ruangan_id.required' => 'Ruangan harus dipilih.',
                'role_ruangan_id.required' => 'Role harus diisi.',
            ]
        );

        $model = PegawaiRuangan::findOrFail($id);
        $pegawai = Pegawai::find($req->peg_id);
        $model->update([
            'peg_id' => $req->peg_id,
            'nama_pegawai' => $pegawai->peg_nama_lengkap,
            'ruangan_id' => $req->ruangan_id,
            'role_ruangan_id' => $req->role_ruangan_id,
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_pegawai' => $model->nama_pegawai,
            'ruangan_id' => $model->ruangan_id,
            'role_ruangan_id' => $model->role_ruangan_id,
        ]);

        logAction('Update Pegawai Ruangan', json_encode($data), $model->peg_ruangan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $model = PegawaiRuangan::findOrFail($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'nama_pegawai' => $model->nama_pegawai,
            'ruangan_id' => $model->ruangan_id,
            'role_ruangan_id' => $model->role_ruangan_id,
        ]);

        logAction('Delete Pegawai Ruangan', json_encode($data), $model->peg_ruangan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }
    public function getOption(){
        $pegawai = Pegawai::select('peg_id', 'peg_nama_lengkap')->orderBy('peg_nama_lengkap', 'asc')->get();
        $role_ruangan = RoleRuangan::select('id', 'role')->orderBy('role', 'asc')->get();
        $ruangan = Ruangan::treeSort(Ruangan::get());
        foreach ($ruangan as $model) {
            if ($model->ruangan_level) {
                $model->nama_ruangan = str_repeat('-', $model->ruangan_level) . ' ' . $model->nama_ruangan;
            }
        }
        return response()->json([
            'data' => [
                'pegawai' => $pegawai,
                'role' => $role_ruangan,
                'ruangan' => $ruangan,
            ]
        ]);
    }
}
