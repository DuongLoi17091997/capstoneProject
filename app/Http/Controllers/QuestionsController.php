<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subjects;
use App\Models\Questions;

class QuestionsController extends Controller
{
    public function getAllQuestions(){
        $questList = Questions::orderBy('created_at', 'desc')->get();
        if(!empty($questList)){
            return response()->json(['code'=>'200','data' => $questList], 200);
        }else{
            return response()->json(['code'=>'400','msg' => 'None Question is Available'], 400);
        }
    }
    public function getQuestionByID($id){
        $questionLst = Questions::where('id','=', $id)->first();
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
    public function createQuestion(Request $request){
        $questionTitle = $request->titles;
        $questionType = $request->type;
        $subjectId = $request->subject_id;
        $newQuestion = Questions::create([
            'titles' => $questionTitle,
            'question_type' => $questionType,
            'subjects_id' => $subjectId,
            'status' => '1'
        ]);
        if($questionType == 'Multiple Choice'){
            $questionASelection = $request->a_selection;
            $questionBSelection = $request->b_selection;
            $questionCSelection = $request->c_selection;
            $questionDSelection = $request->d_selection;
            $multipleChoiceReult = $request->multiple_choice_result;
            $newQuestion -> a_seletion = $questionASelection;
            $newQuestion -> b_seletion = $questionBSelection;
            $newQuestion -> c_seletion = $questionCSelection;
            $newQuestion -> d_seletion = $questionDSelection;
            $newQuestion -> multiple_seletion_result = $multipleChoiceReult;
            $newQuestion -> writing_result = '';
        }else{
            $writingResult = $request->writing_result;
            $newQuestion -> writing_result = $writingResult;
        }
        $newQuestion->save();
        if(!empty($newQuestion)){
            return response()->json(['code' => '200', 'msg'=> 'New Question has been added'], 200);
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Create new Question Faild'], 400);
        }
    }
    public function handleEditQuestion(Request $request){
        $questionId = $request->question_id;
        $findQuestion = Questions::where('id','=', $questionId)->first();
        if(!empty($findQuestion)){
            $questionTitle = $request->title;
            $questionType = $request->type;
            $subjectId = $request->subject_id;
            $findQuestion -> titles = $questionTitle;
            $findQuestion -> question_type = $questionType;
            $findQuestion -> subjects_id = $subjectId;
            if($questionType == 'Multiple Choice'){
                $findQuestion -> a_seletion = $request->a_selection;
                $findQuestion -> b_seletion = $request->b_selection;
                $findQuestion -> c_seletion = $request->c_selection;
                $findQuestion -> d_seletion = $request->c_selection;
                $findQuestion -> multiple_seletion_result = $request->multiple_choice_result;
                $findQuestion -> writing_result = '';
            }else{
                $findQuestion -> writing_result = $request->writting_result;
            }
            $findQuestion -> save();
            return response()->json(['code'=> '200', 'msg'=> 'Updated Question Success'], 200);
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
    public function handleDisableQuestion($id){
        $findQuestion = Questions::where('id','=', $id)->first();
        if(!empty($findQuestion)){
            $findQuestion->status = '0';
            $findQuestion->save();
            return response()->json(['code'=> '200', 'msg'=> 'Disable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEnableQuestion($id){
        $findQuestion = Questions::where('id','=', $id)->first();
        if(!empty($findQuestion)){
            $findQuestion->status = '1';
            $findQuestion->save();
            return response()->json(['code'=> '200', 'msg'=> 'Enable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
}
