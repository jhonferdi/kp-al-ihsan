<?php

namespace App\Http\Controllers\Auth;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filter = $request->filter;
        $sortby = $request->sortby;
        $sortdesc = $request->sortdesc;
        $params = $request->params;

        $models = User::with(['pegawai', 'role']);

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nama) like (?)', ['%' . (strtolower($filter)) . '%'])
                    ->orWhereRaw('LOWER(username) like (?)', ['%' . (strtolower($filter)) . '%']);;
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
                    case 'role_id':
                        $models->whereHas('role', function ($q) use ($val) {
                            $q->where('role_id', $val);
                        });
                        break;
                }
            }
            if ($params['role_id'] === '0') {
                $models = $models->whereDoesntHave('role');
            }
        }

        $page = $request->get('page', 1);
        $count = $models->count();
        $perpage = $request->perpage == 'all' ? $count : $request->perpage ?? 20;
        $models->skip(($page - 1) * $perpage)->take($perpage);
        $models = $models->get();

        return response()->json([
            'success' => true,
            'data' => $models,
            'count' => $count
        ], 200);
    }

    public function getUser()
    {
        $user = Auth::user();
        $pegawai = Pegawai::where('peg_id', $user->peg_id)->first();
        if ($user->role_id == 1) {
            $user->user_permissions = ['edit-admin', 'riwayat-pelanggaran'];
        } else {
            $user->user_permissions = [];
        }
        $user->pegawai = $pegawai;

        return response()->json($user);
    }

    public function show($id)
    { {
            $user = Auth::user();

            if ($user->role_id == 1) {
                $model = User::with('pegawai')->where('id', $id)->first();
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
    }

    public function store(Request $request)
    {
        $pegawai = Pegawai::find($request->peg_id);
        $model = User::create([
            'peg_id' => $request->peg_id,
            'username' => trim($request->username),
            'status_aktif' => true,
            'password' => bcrypt('al1hs4n'),
            'role_id' => $request->role_id,
            'email' =>  $pegawai ? $pegawai->peg_email : null
        ]);

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'username' => $model->username,
            'email' => $model->email,
            'status_aktif' => $model->status_aktif,
            'role_id' => $model->role_id,
        ]);

        logAction('Tambah User', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'model' => $model,
            'success' => true
        ]);
    }

    public function update(Request $request)
    {
        $model = User::find($request->id);
        $pegawai = Pegawai::find($model->peg_id);
        $model->update([
            'peg_id' => $request->peg_id,
            'username' => trim($request->username),
            'status_aktif' => true,
            'password' => bcrypt('al1hs4n'),
            'role_id' => $request->role_id,
            'email' =>  $pegawai ? $pegawai->peg_email : null
        ]);

        if (!empty($request->password)) {
            $model->password = bcrypt($request->password);
        }

        $model->save();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'username' => $model->username,
            'email' => $model->email,
            'status_aktif' => $model->status_aktif,
            'role_id' => $model->role_id,
        ]);

        logAction('Update User', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'model' => $model,
            'success' => true
        ]);
    }

    public function destroy(Request $request)
    {
        $model = User::findOrFail($request->id);
        $model->delete();

        $data = collect([]);
        $data->push([
            'peg_id' => $model->peg_id,
            'username' => $model->username,
            'email' => $model->email,
            'status_aktif' => $model->status_aktif,
            'role_id' => $model->role_id,
        ]);

        logAction('Delete User', json_encode($data), $model->id, Auth::user()->username, Auth::user()->peg_id);

        return response()->json([
            'success' => true
        ]);
    }

    public function resetPassword($id)
    {
        $reset = User::find($id);
        $reset->update([
            'password' => Hash::make('al1hs4n')
        ]);
        return response()->json([
            'success' => true
        ]);
    }

    public function getOptions()
    {
        $pegawai = Pegawai::orderBy('peg_id', 'asc')->pluck('peg_id')->toArray();
        $user = User::orderBy('peg_id', 'asc')->pluck('peg_id')->toArray();
        $diff = array_diff($pegawai, $user);

        $pegawaiList = collect([]);
        foreach ($diff as $d) {
            $data = Pegawai::find($d);
            $pegawaiList->push([
                'peg_id' => $data->peg_id,
                'peg_nama_lengkap' => $data->peg_nama_lengkap,
            ]);
        }

        $role = Role::select('role_id', 'nama_role')->orderBy('nama_role', 'asc')->get();

        return response()->json([
            'data' => [
                'pegawai' => $pegawaiList,
                'role' => $role
            ]
        ]);
    }
}
