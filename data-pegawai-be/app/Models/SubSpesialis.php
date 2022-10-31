<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubSpesialis extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sub_spesialis';
    protected $guarded = ['sub_spesialis_id'];
    protected $primaryKey = 'sub_spesialis_id';

    public function spesialis()
    {
        return $this->belongsTo(Spesialis::class, 'spesialis_id', 'spesialis_id');
    }
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'peg_id');
    }
}
