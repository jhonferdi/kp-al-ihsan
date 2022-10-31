<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sertifikat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sertifikat';
    protected $guarded = ['sertifikat_id'];
    protected $primaryKey = 'sertifikat_id';
    protected $append = ['files'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'peg_id');
    }
}
