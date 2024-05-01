<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subjects;


class Questions extends Model
{
    use  HasFactory;
    protected $appends = ['difficulty'];
    protected $guarded = [];

    // Define accessor for the additional field
    public function getDifficultyAttribute()
    {
        $subjectList = Subjects::where('id', $this->subjects_id)->first();
        return $subjectList->range;
    }
    
    public function setDifficultyFieldAttribute($value){
        $this->attributes['difficulty'] = (string) $value;
    }
}
