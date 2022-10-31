<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleRuangan extends Model
{
    use HasFactory;
    protected $table = 'role_ruangan';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function pegawaiRuagan()
    {
        return $this->hasOne(PegawaiRuangan::class, 'role_ruangan_id');
    }
}
