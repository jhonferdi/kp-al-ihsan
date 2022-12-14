<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPendidikan extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'master_pendidikan';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

}
