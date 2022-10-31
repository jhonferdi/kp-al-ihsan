<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDokumenDigital extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'master_dokumen_digital';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

    public function dokumen_digital()
    {
        return $this->hasMany(DokumenDigital::class);
    }
}
