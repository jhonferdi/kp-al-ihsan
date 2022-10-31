<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bidang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'bidang';
    protected $guarded = ['bidang_id'];
    protected $primaryKey = 'bidang_id';

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
    
}
