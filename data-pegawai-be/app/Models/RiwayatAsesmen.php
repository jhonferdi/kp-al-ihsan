<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatAsesmen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'riwayat_asesmen_kompetensi';
    protected $guarded = ['id'];
    protected $append = ['files'];
    protected $primaryKey = 'id';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function masterAsesmenKompetensi()
    {
        return $this->belongsTo(MasterAsesmen::class, 'master_asesmen_kompetensi_id', 'id');
    }
}
