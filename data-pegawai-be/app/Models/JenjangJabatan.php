<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenjangJabatan extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'jenjang_jabatan';
    protected $guarded = ['jenjang_jabatan_id'];
    protected $primaryKey = 'jenjang_jabatan_id';

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
