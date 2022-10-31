<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JabatanFungsional extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'jabatan_fungsional';
    protected $guarded = ['jabatan_fungsional_id'];
    protected $primaryKey = 'jabatan_fungsional_id';

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'jabatan_fungsional_id');
    }
}
