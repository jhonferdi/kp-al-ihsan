<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterRkk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'master_rkk_spkk_jk';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
}
