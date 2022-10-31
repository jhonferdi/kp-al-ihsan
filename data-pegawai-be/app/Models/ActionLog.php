<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    use HasFactory;
    protected $table = 'action_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'username', 'keterangan', 'aksi', 'entity_id', 'peg_id', 'created_at'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'peg_id', 'peg_id');
    }

    public function getCreatedAtAttribute()
    {
    return \Carbon\Carbon::parse($this->attributes['created_at'])
       ->format('d, M Y H:i');
    }
    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->time = date('Y-m-d H:i:s');
    //         $model->waktu = time();
    //     });
    // }
}
