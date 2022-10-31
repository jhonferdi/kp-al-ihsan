<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\DokumenDigital;
use App\Models\MasterDokumenDigital;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DokumenDigitalController extends Controller
{

    public function doUpload(Request $request, $peg_id, $master_dokumen_digital_id, $entity_id = null)
    {
        $this->validate(
            $request,
            [
                'file' => ['mimes:jpg,png,pdf']
            ],
            [
                'file.mimes' => 'File hanya boleh diisi dengan format jpg, png dan pdf'
            ]
        );
        $file = $request->file('file');
        $file_nama = $file->getClientOriginalName();
        $path = $file->store($peg_id . '/' . $master_dokumen_digital_id);
        if ($entity_id) {
            DokumenDigital::where('master_dokumen_digital_id', $master_dokumen_digital_id)
                ->where('peg_id', $peg_id)
                ->where('entity_id', $entity_id)
                ->delete();
            $file = DokumenDigital::create([
                'peg_id' => $peg_id,
                'master_dokumen_digital_id' => $master_dokumen_digital_id,
                'entity_id' => $entity_id,
                'file_nama' => $file_nama,
                'file_path' => $path,
                'file_url' => Storage::url($path),
            ]);
        } else {
            DokumenDigital::where('master_dokumen_digital_id', $master_dokumen_digital_id)
                ->where('peg_id', $peg_id)
                ->whereNull('entity_id')
                ->delete();
            $file = DokumenDigital::create([
                'peg_id' => $peg_id,
                'master_dokumen_digital_id' => $master_dokumen_digital_id,
                'file_nama' => $file_nama,
                'file_path' => $path,
                'file_url' => Storage::url($path),
            ]);
            if ($master_dokumen_digital_id == 24) {
                $pegawai = Pegawai::find($peg_id);
                $pegawai->update(
                    [
                        'peg_images' => Storage::url($path)
                    ]
                );
            }
        }
        $masterDokumen = MasterDokumenDigital::find($master_dokumen_digital_id);
        $data = collect([]);
        $data->push([
            'peg_id' => $file->peg_id,
            'master_dokumen_digital_id' => $file->master_dokumen_digital_id,
            'file_nama' => $file->file_nama,
        ]);
        logAction('Upload ' . $masterDokumen->file_nama, json_encode($data), $file->id, Auth::user()->username, Auth::user()->peg_id);
        return response()->json([
            'success' => true,
            'messsage' => "File berhasil diupload",
            'dokumen_digital' => $file,
        ]);
    }
}
