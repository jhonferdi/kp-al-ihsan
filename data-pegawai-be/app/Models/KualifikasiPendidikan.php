<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KualifikasiPendidikan extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'kualifikasi_pendidikan';
    protected $guarded = ['kualifikasi_pendidikan_id'];
    protected $primaryKey = 'kualifikasi_pendidikan_id';

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
