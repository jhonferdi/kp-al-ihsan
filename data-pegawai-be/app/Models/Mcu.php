<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mcu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'mcu';
    protected $guarded = ['id'];
    protected $append = ['files'];
    protected $primaryKey = 'id';
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function masterMcu()
    {
        return $this->belongsTo(MasterMcu::class);
    }
}
