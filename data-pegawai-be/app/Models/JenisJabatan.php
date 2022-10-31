<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisJabatan extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'jenis_jabatan';
    protected $guarded = ['jenis_jabatan_id'];
    protected $primaryKey = 'jenis_jabatan_id';

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
