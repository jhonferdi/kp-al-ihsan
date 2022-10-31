<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendidikan extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'pendidikan';
    protected $guarded = ['pendidikan_id'];
    protected $primaryKey = 'pendidikan_id';

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
