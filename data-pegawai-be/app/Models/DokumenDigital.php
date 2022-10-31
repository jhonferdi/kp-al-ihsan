<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenDigital extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'dokumen_digital';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function master_dokumen_digital()
    {
        return $this->belongsTo(MasterDokumenDigital::class);
    }

    public static function updateEntityId($files, $peg_id, $entity_id)
    {
        $ids = [];
        $master_ids = [];
        foreach ($files as $master_dokumen_digital_id => $file) {
            $ids[] = $file['id'];
            $master_ids[] = $master_dokumen_digital_id;
        }
        // hapus file lama
        static::where('peg_id', $peg_id)->whereIn('master_dokumen_digital_id', $master_ids)->where('entity_id', $entity_id)->delete();
        // remapping entity id
        static::withTrashed()->where('peg_id', $peg_id)->whereIn('id', $ids)->update([
            'entity_id' => $entity_id,
        ]);
    }
}
