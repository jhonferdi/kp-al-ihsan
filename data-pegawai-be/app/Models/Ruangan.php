<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ruangan';
    protected $guarded = ['ruangan_id'];
    protected $primaryKey = 'ruangan_id';

    public function pegawaiRuangan()
    {
        return $this->hasMany(PegawaiRuangan::class, 'ruangan_id');
    }
    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id', 'unit_kerja_id');
    }

    public function parent()
    {
        return $this->hasMany(Ruangan::class, 'ruangan_parent');
    }

    public static function treeSort($models)
    {
        $byparents = $models->sortBy(['unit_kerja_id', 'ruangan_id'])->groupBy('ruangan_parent');
        return static::_treeSort($byparents, 0);
    }

    private static function _treeSort($byparents, $ruangan_parent)
    {
        $res = [];
        if (isset($byparents[$ruangan_parent])) {
            foreach ($byparents[$ruangan_parent] as &$unit) {
                $res[] = $unit;
                $res = array_merge($res, static::_treeSort($byparents, $unit->ruangan_id));
            }
        }
        return $res;
    }
}
