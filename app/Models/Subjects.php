<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Grades;


class Subjects extends Model
{
    use  HasFactory;
    protected $appends = ['grade'];
    protected $guarded = [];
    protected $casts = [
        'id' => 'string'
    ];

    // Define accessor for the additional field
    public function getGradeAttribute()
    {
        $grade = Grades::where('id', $this->grade_id)->first();
        return $grade->grade;
    }
}
