<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPrestasi extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'riwayat_prestasi';
    protected $guarded = ['id'];
    protected $append = ['files'];
    protected $primaryKey = 'id';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
