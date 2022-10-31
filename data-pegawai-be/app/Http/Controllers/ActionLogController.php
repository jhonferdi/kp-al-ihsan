<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActionLogController extends Controller
{
    public function index(Request $req)
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
        $filter = $req->filter;
        $sortby = $req->sortby;
        $sortdesc = $req->sortdesc;
        $params = $req->params;

        $models = ActionLog::query();

        if (!empty($filter)) {
            $filter = addslashes($filter);
            $models = $models->where(function ($q) use ($filter) {
                $q->whereRaw('LOWER(aksi) like (?)', ['%' . (strtolower($filter)) . '%']);
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
        $models = $models->with('pegawai')->get();
    }
    else{
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }
    return response()->json([
        'success' => true,
        'data' => $models,
        'count' => $count
    ], 200);
    }
    public function getData()
    {
        $models = ActionLog::selectRaw('id as id, aksi as aksi')->orderBy('aksi', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $models,
        ], 200);
    }
}
