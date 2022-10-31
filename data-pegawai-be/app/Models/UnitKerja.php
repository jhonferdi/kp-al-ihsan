<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitKerja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'unit_kerja';
    protected $guarded = ['unit_kerja_id'];
    protected $primaryKey = 'unit_kerja_id';
    protected $fillable = [
        'unit_kerja_nama',
        'unit_kerja_level',
        'unit_kerja_parent'
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function parent()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_parent');
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'unit_kerja_id_struktural');
    }

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'unit_kerja_id');
    }

    public static function treeSort($models)
    {
        $byparents = $models->sortBy('unit_kerja_id')->groupBy('unit_kerja_parent');
        return static::_treeSort($byparents, 0);
    }

    private static function _treeSort($byparents, $unit_kerja_parent)
    {
        $res = [];
        if (isset($byparents[$unit_kerja_parent])) {
            foreach ($byparents[$unit_kerja_parent] as &$unit) {
                $res[] = $unit;
                $res = array_merge($res, static::_treeSort($byparents, $unit->unit_kerja_id));
            }
        }
        return $res;
    }
}
