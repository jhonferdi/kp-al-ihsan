<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenagaKerja extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'tenaga_kerja';
    protected $guarded = ['tenaga_kerja_id'];
    protected $primaryKey = 'tenaga_kerja_id';

    public function jenisTenagaKerja()
    {
        return $this->hasMany(JenisTenagaKerja::class);
    }
}
