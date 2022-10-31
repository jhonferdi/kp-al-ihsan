<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class UnitKerjaController extends Controller
{

    public function index(Request $req)
    {
        $filter = $req->filter;
        // $sortby = $req->sortby;
        // $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = UnitKerja::with(['ruangan', 'ruangan.parent']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(unit_kerja_nama) like (?)', ['%' . (strtolower($filter)) . '%']);
            });
        }

        // if (!empty($sortby)) {
        //     if ($sortdesc == 'true') {
        //         $models = $models->orderByDesc($sortby);
        //     } else {
        //         $models = $models->orderBy($sortby);
        //     }
        // }

        $page = $req->get('page', 1);
        $count = UnitKerja::where('unit_kerja_level', 2)->count();
        $perpage = $req->perpage == 'all' ? $count : $req->perpage ?? 20;
        $models->skip(($page - 1) * $perpage)->take($perpage);
        $models = $models->get();

        return response()->json([
            'success' => true,
            'data' => $models,
            'count' => $count
        ], 200);
    }

    public function getListUnitKerja(Request $request)
    {
        $models = UnitKerja::treeSort(UnitKerja::where('unit_kerja_id', '>=', '1000')->get());
        foreach ($models as &$model) {
            if ($model->unit_kerja_level) {
                $model->unit_kerja_nama = str_repeat('-', $model->unit_kerja_level) . ' ' . $model->unit_kerja_nama;
            }
        }
        return response()->json([
            'success' => true,
            'data' => $model,
        ], 200);
    }



    public function store(Request $req)
    {
        // $this->validate(
        //     $req,
        //     [
        //         'unit_kerja_nama' => ['required', 'unique:unit_kerja,unit_kerja_nama'],
        //         'unit_kerja_level' => ['required'],
        //         'unit_kerja_parent' => ['required']
        //     ],
        //     [
        //         'unit_kerja_nama.required' => 'Nama unit kerja harus diisi.',
        // 'unit_kerja_nama.unique' => 'Nama unit kerja sudah ada.',
        //         'unit_kerja_level.required' => 'Level unit kerja harus diisi!',
        //         'unit_kerja_parent.required' => 'Parent unit kerja harus diisi!',
        //     ]
        // );
        if ($req->save_unit_kerja) {
            $this->validate(
                $req,
                [
                    'wd_id' => ['required'],
                    'unit_kerja_nama' => ['required', 'unique:unit_kerja,unit_kerja_nama']
                ],
                [
                    'wd_id.required' => 'Wakil direktur harus dipilih',
                    'unit_kerja_nama.required' => 'Nama unit kerja harus diisi',
                    'unit_kerja_nama.unique' => 'Nama unit kerja sudah ada.',
                ]
            );
            $model = UnitKerja::create([
                'unit_kerja_nama' => $req->unit_kerja_nama,
                'unit_kerja_level' => 2,
                'unit_kerja_parent' => $req->wd_id,
            ]);
        }

        if ($req->save_instalasi) {
            $this->validate(
                $req,
                [
                    'unit_kerja_id' => ['required'],
                    'nama_instalasi' => ['required', 'unique:ruangan,nama_ruangan']
                ],
                [
                    'unit_kerja_id.required' => 'Unit kerja harus dipilih',
                    'nama_instalasi.required' => 'Nama instalasi harus diisi',
                    'nama_instalasi.unique' => 'Nama instalasi sudah ada.',
                ]
            );
            $model = Ruangan::create([
                'nama_ruangan' => $req->nama_instalasi,
                'ruangan_level' => 1,
                'ruangan_parent' => 0,
                'jenis' => 'instalasi',
                'unit_kerja_id' => $req->unit_kerja_id
            ]);
        }

        if ($req->save_ruangan) {
            $this->validate(
                $req,
                [
                    'instalasi_id' => ['required'],
                    'nama_ruangan' => ['required', 'unique:ruangan,nama_ruangan']
                ],
                [
                    'instalasi_id.required' => 'Instalasi harus dipilih',
                    'nama_ruangan.required' => 'Nama ruangan harus diisi',
                    'nama_ruangan.unique' => 'Nama ruangan sudah ada.',
                ]
            );
            $instalasi = Ruangan::find($req->instalasi_id);
            $model = Ruangan::create([
                'nama_ruangan' => $req->nama_ruangan,
                'ruangan_level' => 2,
                'ruangan_parent' => $req->instalasi_id,
                'jenis' => 'ruangan',
                'unit_kerja_id' => $instalasi->unit_kerja_id
            ]);
        }

        $data = collect([]);
        $data->push([
            'unit_kerja_nama' => $model->unit_kerja_nama,
            'unit_kerja_level' => $model->unit_kerja_level,
            'unit_kerja_parent' => $model->unit_kerja_parent,
        ]);

        logAction('Tambah Unit Kerja', json_encode($data), $model->unit_kerja_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 201);
    }

    public function show($id)
    {
        $model = UnitKerja::where('unit_kerja_id', $id)
            ->firstOrFail();
        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }


    public function update(Request $req, $id)
    {
        // $this->validate(
        //     $req,
        //     [
        //         'unit_kerja_nama' => ['required', 'unique:unit_kerja,unit_kerja_nama'],
        //         'unit_kerja_level' => ['required'],
        //         'unit_kerja_parent' => ['required']
        //     ],
        //     [
        //         'unit_kerja_nama.required' => 'Nama unit kerja harus diisi.',
        //         'unit_kerja_nama.unique' => 'Nama unit kerja sudah ada.',
        //         'unit_kerja_level.required' => 'Level unit kerja harus diisi!',
        //         'unit_kerja_parent.required' => 'Parent unit kerja harus diisi!',
        //     ]
        // );

        if ($req->save_unit_kerja) {
            $this->validate(
                $req,
                [
                    'wd_id' => ['required'],
                    'unit_kerja_nama' => ['required', Rule::unique('unit_kerja', 'unit_kerja_nama')->ignore($id, 'unit_kerja_id')]
                ],
                [
                    'wd_id.required' => 'Wakil direktur harus dipilih',
                    'unit_kerja_nama.required' => 'Nama unit kerja harus diisi',
                    'unit_kerja_nama.unique' => 'Nama unit kerja sudah ada.',
                ]
            );
            $model = UnitKerja::find($id);
            $model->update([
                'unit_kerja_nama' => $req->unit_kerja_nama,
                'unit_kerja_level' => 2,
                'unit_kerja_parent' => $req->wd_id,
            ]);
        }

        if ($req->save_instalasi) {
            $this->validate(
                $req,
                [
                    'unit_kerja_id' => ['required'],
                    'nama_ruangan' => ['required', Rule::unique('ruangan', 'nama_ruangan')->ignore($id, 'ruangan_id')]
                ],
                [
                    'unit_kerja_id.required' => 'Unit kerja harus dipilih',
                    'nama_ruangan.required' => 'Nama instalasi harus diisi',
                    'nama_ruangan.unique' => 'Nama instalasi sudah ada.',
                ]
            );
            $model = Ruangan::find($id);
            $model->update([
                'nama_ruangan' => $req->nama_ruangan,
                'unit_kerja_id' => $req->unit_kerja_id
            ]);
        }

        if ($req->save_ruangan) {
            $this->validate(
                $req,
                [
                    'instalasi_id' => ['required'],
                    'nama_ruangan' => ['required', Rule::unique('ruangan', 'nama_ruangan')->ignore($id, 'ruangan_id')]
                ],
                [
                    'instalasi_id.required' => 'Instalasi harus dipilih',
                    'nama_ruangan.required' => 'Nama ruangan harus diisi',
                    'nama_ruangan.unique' => 'Nama ruangan sudah ada.',
                ]
            );
            $model = Ruangan::find($id);
            $model->update([
                'nama_ruangan' => $req->nama_ruangan,
                'ruangan_parent' => $req->instalasi_id,
            ]);
        }

        $data = collect([]);
        $data->push([
            'unit_kerja_nama' => $model->unit_kerja_nama,
            'unit_kerja_level' => $model->unit_kerja_level,
            'unit_kerja_parent' => $model->unit_kerja_parent,
        ]);

        logAction('Update Unit Kerja', json_encode($data), $model->unit_kerja_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true,
            'model' => $model,
        ], 200);
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::where('unit_kerja_id', $id)->get();
        if ($ruangan) {
            foreach ($ruangan as $r) {
                $r->delete();
            }
        }
        $model = UnitKerja::find($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'unit_kerja_nama' => $model->unit_kerja_nama,
            'unit_kerja_level' => $model->unit_kerja_level,
            'unit_kerja_parent' => $model->unit_kerja_parent,
        ]);

        logAction('Delete Unit Kerja', json_encode($data), $model->unit_kerja_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function destroyInstalasi($id)
    {
        $ruangan = Ruangan::where('ruangan_parent', $id)->get();
        if ($ruangan) {
            foreach ($ruangan as $r) {
                $r->delete();
            }
        }
        $model = Ruangan::find($id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'ruangan_id' => $model->ruangan_id,
            'ruangan_nama' => $model->ruangan_nama,
            'jenis' => $model->jenis,
        ]);

        logAction('Delete Instalasi', json_encode($data), $model->ruangan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function destroyRuangan($id)
    {
        $model = Ruangan::where('ruangan_parent', $id)->first();
        $model->delete();

        $data = collect([]);
        $data->push([
            'ruangan_id' => $model->ruangan_id,
            'ruangan_nama' => $model->ruangan_nama,
            'jenis' => $model->jenis,
        ]);

        logAction('Delete Ruangan', json_encode($data), $model->ruangan_id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function getData()
    {
        $models = UnitKerja::selectRaw('unit_kerja_id as unit_kerja_id, unit_kerja_nama as unit_kerja_nama')->orderBy('unit_kerja_nama', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
