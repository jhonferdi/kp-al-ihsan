<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDiklat extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'master_diklat';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';

}
