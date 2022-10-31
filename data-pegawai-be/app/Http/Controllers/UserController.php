<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $req){
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = User::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(nama) like (?)', ['%' . (strtolower($filter)) . '%']);
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
            'count' => $count
        ], 200);
    }

    public function countData()
    {
        $count = User::count();

        return response()->json([
            'count' => $count
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password' => 'required|min:4'
        ]);

        $model = new User;
        $model->name = $request->name;
        $model->email = $request->email;
        $model->password = bcrypt($request->password);
        $model->satuan_kerja_id = $request->satuan_kerja_id ?? null;
        $model->unit_kerja_id = $request->unit_kerja_id ?? null;
        $model->role_id = $request->role_id;
        $model->save();

        return response()->json([
            'model' => $model,
            'success' => true
        ]);
    }

    public function view(Request $request, $id)
    {
        $model = User::where('id', $id)
            ->firstOrFail();

        return response()->json([
            'data' => $model,
            'success' => true
        ]);
    }

    public function update(Request $request)
    {
        $model = User::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required'
        ]);

        $model->name = $request->name;
        $model->email = $request->email;
        $model->satuan_kerja_id = $request->satuan_kerja_id;
        $model->unit_kerja_id = $request->unit_kerja_id;
        $model->role_id = $request->role_id;

        if (!empty($request->password)) {
            $model->password = bcrypt($request->password);
        }

        $model->save();

        return response()->json([
            'model' => $model,
            'success' => true
        ]);
    }

    public function delete(Request $request)
    {
        $model = User::findOrFail($request->id);
        $model->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
