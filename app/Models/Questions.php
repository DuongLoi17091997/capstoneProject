<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subjects;


class Questions extends Model
{
    use  HasFactory;
    protected $appends = ['subject_name'];
    protected $guarded = [];
    protected $casts = [
        'id' => 'string'
    ];

    public function getSubjectNameAttribute()
    {
        $subjectList = Subjects::where('id', $this->subject_id)->first();
        $subjectName = $subjectList->name . " ". $subjectList->grade;
        return $subjectName;
    }
    
}
