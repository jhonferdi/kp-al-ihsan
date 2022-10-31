<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPendidikan extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'riwayat_pendidikan';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function masterPendidikan()
    {
        return $this->belongsTo(MasterPendidikan::class);
    }
}
