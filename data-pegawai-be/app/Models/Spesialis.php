<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spesialis extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'spesialis';
    protected $guarded = ['spesialis_id'];
    protected $primaryKey = 'spesialis_id';

    public function subSpesialis()
    {
        return $this->hasMany(SubSpesialis::class, 'spesialis_id');
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
