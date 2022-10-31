<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'role';
    protected $guarded = ['role_id'];
    protected $primaryKey = 'role_id';

    public function user()
    {
        return $this->hasOne(User::class, 'role_id');
    }
}
