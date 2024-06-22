<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sessionTokenUser extends Model
{
    protected $guarded = [];
    protected $casts = [
        'id' => 'string'
    ];
}
