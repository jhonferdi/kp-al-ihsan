<?php

use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

function logAction($aksi, $keterangan = '', $entity_id = null, $username = null, $peg_id = null)
{
    if (!$username) {
        $username = Auth::user() ? Auth::user()->username : 'Guest';
    }
    ActionLog::create([
        'username' => $username,
        'aksi' => $aksi,
        'keterangan' => $keterangan,
        'entity_id' => $entity_id,
        'peg_id' => $peg_id
    ]);
}
