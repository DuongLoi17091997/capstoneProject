<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subjects;
use App\Models\Questions;

class QuestionsController extends Controller
{
    public function getAllQuestions(){
        $questList = Questions::All();
        if(!empty($questList)){
            return response()->json(['code'=>'200','data' => $questList], 200);
        }else{
            return response()->json(['code'=>'400','msg' => 'None Question is Available'], 400);
        }
    }
    public function getQuestionByID(){
        $header = apache_request_headers();
        $questionId = $header['id'];
        $questionLst = Questions::where('id','=', $questionId)->first();
        if(!$questionLst){
            return response()->json(['code'=> '200', 'data'=> $questionLst], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function getQuestionByName(){
        $header = apache_request_headers();
        $questionTitle = $header['title'];
        $questionLst = Questions::where('title','like', $questionTitle.'%')->get();
        if(!$questionLst){
            return response()->json(['code'=> '200', 'data'=> $questionLst], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Name is invalid'], 400);
    }
    public function createQuestion(){
        $header = apache_request_headers();
        $questionTitle = $header['title'];
        $questionType = $header['type'];
        $subjectId = header['subject_id'];
        $newQuestion = Questions::create([
            'title' => $questionTitle,
            'type' => $questionType,
            'subjects_id' => $subjectId,
            'status' => true
        ]);
        if($questionType == 'Multiple Choice'){
            $questionASelection = $header['a_selection'];
            $questionBSelection = $header['b_selection'];
            $questionCSelection = $header['c_selection'];
            $questionDSelection = $header['d_selection'];
            $multipleChoiceReult = $header['multiple_choice_result'];
            $newQuestion -> a_seletion = $questionASelection;
            $newQuestion -> b_seletion = $questionBSelection;
            $newQuestion -> c_seletion = $questionCSelection;
            $newQuestion -> d_seletion = $questionDSelection;
            $newQuestion -> multiple_seletion_result = $multipleChoiceReult;

        }else{
            $writingResult = header['writing_result'];
            $newQuestion -> writing_result = $writingResult;
        }
        if(!empty($newQuestion)){
            return response()->json(['code' => '200', 'msg'=> 'New Question has been added'], 200);
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Create new Question Faild'], 400);
        }
    }
    public function handleEdit(){
        $header = apache_request_headers();
        $questionId = $header['question_id'];
        $findQuestion = Questions::where('id','=', $questionId)->first();
        if(empty($findQuestion)){
            $questionTitle = $header['title'];
            $questionType = $header['type'];
            $subjectId = header['subject_id'];
            $findQuestion -> title = $questionTitle;
            $findQuestion -> type = $questionType;
            $findQuestion -> subject_id = $subjectId;
            if($questionType == 'Multiple Choice'){
                $questionASelection = $header['a_selection'];
                $questionBSelection = $header['b_selection'];
                $questionCSelection = $header['c_selection'];
                $questionDSelection = $header['d_selection'];
                $multipleChoiceReult = $header['multiple_choice_result'];
                $findQuestion -> a_seletion = $questionASelection;
                $findQuestion -> b_seletion = $questionBSelection;
                $findQuestion -> c_seletion = $questionCSelection;
                $findQuestion -> d_seletion = $questionDSelection;
                $findQuestion -> multiple_seletion_result = $multipleChoiceReult;
            }else{
                $writingResult = header['writing_result'];
                $findQuestion -> writing_result = $writingResult;
            }
            $findQuestion -> save();
            return response()->json(['code'=> '200', 'msg'=> 'Updated Success'], 200);
        }else{
            return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
        }
    }
    public function getQuestionBySubjectId(){
        $header = apache_request_headers();
        $subjectId = $header['id'];
        $findQuestionsList = Questions::where('subject_id','=', $subjectId)->get();
        if(!empty($findQuestions)){
            return response()->json(['code'=> '200', 'data'=> $findQuestionsList], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleDisableQuestion(){
        $header = apache_request_headers();
        $questionId = $header['id'];
        $findQuestion = Questions::where('id','=', $questionId)->first();
        if(!empty($findQuestion)){
            $findQuestion->status = false;
            $findQuestion->save();
            return response()->json(['code'=> '200', 'msg'=> 'Disable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEnableQuestion(){
        $header = apache_request_headers();
        $questionId = $header['id'];
        $findQuestion = Questions::where('id','=', $questionId)->first();
        if(!empty($findQuestion)){
            $findQuestion->status = true;
            $findQuestion->save();
            return response()->json(['code'=> '200', 'msg'=> 'Enable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
}
