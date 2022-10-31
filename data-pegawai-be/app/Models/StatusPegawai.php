<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusPegawai extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'status_pegawai';
    protected $guarded = ['status_kepegawaian_id'];
    protected $primaryKey = 'status_kepegawaian_id';

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
