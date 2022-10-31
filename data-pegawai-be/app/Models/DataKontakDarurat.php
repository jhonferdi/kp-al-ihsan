<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataKontakDarurat extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'data_kontak_darurat';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
