<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Questions;
use App\Models\QuestiontoExame;
use App\Models\Subjects;
use App\Models\User;


class Examination extends Model
{
    use  HasFactory;
    protected $appends = ['childrens', 'subject_name', 'author_name'];
    protected $guarded = [];
    protected $casts = [
        'id' => 'string'
    ];

    public function getChildrensAttribute()
    {
        $listId = array();
        $manyToManyExamToQuestion = QuestiontoExame::where('exam_id', $this->id)->get();
        foreach ($manyToManyExamToQuestion as $key => $value) {
            array_push($listId, $value->question_id);
        }
        $findQuestions = Questions::whereIn('id', $listId)->get();

        return $findQuestions;
    }
    public function getSubjectNameAttribute(){
        $findSubject = Subjects::where('id', $this->subject_id)->first();
        return $findSubject->name . " Grade ".     $findSubject->grade;
    }
    public function getAuthorNameAttribute(){
        $findUser = User::where('id', $this->author)->first();
        return $findUser->first_Name . " " . $findUser->last_Name;
    }
    // public function getIsAssignedAttribute(){
    //     $isAssigned = QuestiontoExame::where('exam_id', $this->id)->first();
    //     if(!empty($isAssigned)){
    //         return true;
    //     }
    //     return false;
    // }
}
