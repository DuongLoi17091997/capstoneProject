<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestiontoExame extends Model
{
    protected $guarded = [];
    protected $casts = [
        'id' => 'string'
    ];
}
