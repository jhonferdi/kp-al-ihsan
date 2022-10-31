<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sip extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sip';
    protected $guarded = ['sip_id'];
    protected $primaryKey = 'sip_id';

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'peg_id');
    }
}
