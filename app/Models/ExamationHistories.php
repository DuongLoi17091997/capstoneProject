<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\QuestionAnswer;
use App\Models\Examination;

class ExamationHistories extends Model
{
    use  HasFactory;
    protected $guarded = [];
    protected $appends = ['childrens', 'exam'];
    protected $casts = [
        'id' => 'string'
    ];
    public function getChildrensAttribute()
    {
        return $manyToManyExamToQuestion = QuestionAnswer::where('exam_id', $this->id)->get();
    }
    public function getExamAttribute(){
        return $exam = Examination::where('id', $this->exam_id)->first();
    }
}
