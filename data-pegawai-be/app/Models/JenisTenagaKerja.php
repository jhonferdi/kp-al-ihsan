<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisTenagaKerja extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'jenis_tenaga_kerja';
    protected $guarded = ['jenis_tenaga_kerja_id'];
    protected $primaryKey = 'jenis_tenaga_kerja_id';

    public function tenagaKerja()
    {
        return $this->belongsTo(TenagaKerja::class, 'tenaga_kerja_id');
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
