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

    // Define accessor for the additional field
    public function getGradeAttribute()
    {
        $grade = Grades::where('id', $this->grades_id)->first();
        return $grade->grade;
    }
}
