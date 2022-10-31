<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterAsesmen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'master_asesmen_kompetensi';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
}
