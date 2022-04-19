<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_role';

    protected $fillable = [
        'id_role',
        'nama_role'
    ];
}
