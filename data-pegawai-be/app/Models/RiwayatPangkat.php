<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatPangkat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'riwayat_pangkat';
    protected $guarded = ['riw_pangkat_id'];
    protected $primaryKey = 'riw_pangkat_id';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'peg_id', 'peg_id');
    }
}
