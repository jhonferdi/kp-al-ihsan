<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PegawaiRuangan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pegawai_ruangan';
    protected $guarded = ['peg_ruangan_id'];
    protected $primaryKey = 'peg_ruangan_id';


    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'ruangan_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'peg_id', 'peg_id');
    }
    public function roleRuangan()
    {
        return $this->belongsTo(RoleRuangan::class, 'role_ruangan_id', 'id');
    }

    public static function roleToId($role)
    {
        switch ($role) {
            case 'pegawai':
                return 10;
            case 'kepala':
                return 8;
            case 'kepala tim 1':
            case 'kepala tim 2':
                return 9;
            case 'admin ruangan':
                return 2;
        }
    }
}
