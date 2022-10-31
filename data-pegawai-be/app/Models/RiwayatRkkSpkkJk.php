<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatRkkSpkkJk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'riwayat_rkk_spkk_jk';
    protected $guarded = ['id'];
    protected $append = ['files'];
    protected $primaryKey = 'id';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function masterRkkSpkkJk()
    {
        return $this->belongsTo(MasterRkk::class);
    }
}
