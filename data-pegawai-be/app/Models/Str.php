<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Str extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'str';
    protected $guarded = ['str_id'];
    protected $primaryKey = 'str_id';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'peg_id');
    }
}
