<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use Illuminate\Support\Str;


class QuestionAnswerController extends Controller
{
    public function getAnwserById($id){
        $foundAnwser = QuestionAnswer::where('id','=', $id)->first();
        if(!empty($foundAnwser)){
            return response()->json(['code'=> '200', 'data'=> $foundAnwser], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);

    }
    public function saveAnwser(Request $request){
        $uuid = Str::uuid()->toString();
        $foundAnwser = QuestionAnswer::where('user_id','=', $request->user_id)
        ->where('exam_id','=', $request->exam_id)
        ->where('question_id','=', $request->question_id)
        ->first();
        if(empty($foundAnwser)){
            $newAnwser = QuestionAnswer::create([
                'id' => $uuid,
                'user_id' => $request->user_id,
                'exam_id' => $request->exam_id,
                'question_id' => $request->question_id,
                'answer' => $request->answer
            ]);
            return response()->json(['code'=>'200','msg'=>'Save Anwser Success', 'data' => $newAnwser], 200);
        }else{
            $foundAnwser->user_id = $request->user_id;
            $foundAnwser->exam_id = $request->exam_id;
            $foundAnwser->question_id = $request->question_id;
            $foundAnwser->answer = $request->answer;
            $foundAnwser->save();
            return response()->json(['code'=>'200','msg'=>'Save Anwser Success', 'data' => $foundAnwser], 200);
        }
    }
    public function handleEditAwnserById(Request $request, $id){
        $findQuestion = QuestionAnswer::where('id','=', $id)->first();
        $questionType = $findQuestion->question->question_type;
        $updatedAwnser = '';
        $isPassed = false;
        if(!empty($findQuestion)){
            switch ($questionType) {
                case "Multiple Choice":
                    $updatedAwnser = $request->updatedAwnser;
                    $array = json_decode($updatedAwnser, true);
                    $result = $findQuestion->question->multiple_selection_result;
                    if(in_array($result, $array)){
                        $isPassed = true;
                    }
                    break;
                case "Single Choice":
                    $updatedAwnser = $request->updatedAwnser;
                    if($updatedAwnser == $findQuestion->question->multiple_selection_result){
                        $isPassed = true;
                    }
                    break;
                case "Written":
                    $updatedAwnser = $request->updatedAwnser;
                    if($updatedAwnser == $findQuestion->question->writing_result){
                        $isPassed = true;
                    }
                    break;
                default:
                return response()->json(['code' => '400', 'msg'=> 'Update Faild'], 400);
            }
            $findQuestion->isPassed = $isPassed;
            $findQuestion->status = $request->status;
            $findQuestion->answer = $updatedAwnser;
            $findQuestion->save();
            return response()->json(['code'=> '200', 'msg'=> 'Save Successfull'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);

    }
}
