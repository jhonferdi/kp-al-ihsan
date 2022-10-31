<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiAtasan extends Model
{
    use HasFactory;
    protected $table = 'pegawai_atasan';
    protected $guarded = ['atasan_id'];
    protected $primaryKey = 'atasan_id';

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'atasan_id');
    }
}
