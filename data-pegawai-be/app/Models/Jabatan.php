<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'jabatan';
    protected $guarded = ['jabatan_id'];
    protected $primaryKey = 'jabatan_id';

    public function jabatanFungsional()
    {
        return $this->belongsTo(JabatanFungsional::class, 'jabatan_fungsional_id', 'jabatan_fungsional_id');
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class,'unit_kerja_id_struktural','unit_kerja_id');
    }
}
