<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Questions;
use App\Models\QuestiontoExame;
use App\Models\Subjects;

class Examination extends Model
{
    use  HasFactory;
    protected $appends = ['childrens'];
    protected $guarded = [];

    public function getChildrensAttribute()
    {
        $listId = array();
        $manyToManyExamToQuestion = QuestiontoExame::where('examination_id', $this->id)->get();
        foreach ($manyToManyExamToQuestion as $key => $value) {
            array_push($listId, $value->questions_id);
        }
        $findQuestions = Questions::whereIn('id', $listId)->get();

        return $findQuestions;
    }
}
