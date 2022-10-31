<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Golongan extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'golongan';
    protected $guarded = ['golongan_id'];
    protected $primaryKey = 'golongan_id';

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
