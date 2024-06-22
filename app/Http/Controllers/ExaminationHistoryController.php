<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamationHistories;
use App\Models\sessionTokenUser;
use App\Models\Examination;
use App\Models\Subjects;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\QuestionAnswer;
use Illuminate\Support\Arr;
use Carbon\Carbon;



class ExaminationHistoryController extends Controller
{
    public function getAllExameHistory(){
        $examsHistory = ExamationHistories::All();
        if(!$examsHistory){
            return response()->json(['code'=>'200','data' => $examsHistory], 200);
        }
        return response()->json(['code'=>'400','msg' => 'None Exame is Available'], 400);
    }
    public function getExamHistoryByExamId($id){
        $examsHistory = ExamationHistories::where('id','=', $id)->first();
        if(!empty($examsHistory)){
            return response()->json(['code'=>'200','data' => $examsHistory], 200);
        }
        return response()->json(['code'=>'400','msg' => 'None Exame is Available'], 400);    
    }
    public function createExamHistory(Request $request){
        $uuid = Str::uuid()->toString();
        $newHistory = ExamationHistories::create([
            'id' => $uuid,
            'user_id' => $request->user_id,
            'exam_id' => $request->exam_id,
        ]);
        foreach($newHistory->exam->childrens as $question){
            $newAnwser = QuestionAnswer::create([
                'id' => Str::uuid()->toString(),
                'user_id' => $request->user_id,
                'exam_id' => $uuid,
                'question_id' => $question->id,
            ]);
        }
    }
    public function updateExamHistoryResultById(Request $request, $id){
        $examsHistory = ExamationHistories::where('id','=', $id)->first();
        $datetime = Carbon::now();
        if(empty($examsHistory)){
            return response()->json(['code'=>'400','msg' => 'None Exame is Available'], 400);
        }
        $listQuestionAwnser = $examsHistory->childrens;
        $totalScore = 0;
        foreach($listQuestionAwnser as $awnser){
            if($awnser->isPassed){
                $totalScore += 10;
            }
        }
        $examsHistory->score = $totalScore;
        $examsHistory->result = $totalScore > 50 ? "Passed" : "Failed";
        $examsHistory->comments = $request->comment;
        $examsHistory->time_for_completed =  $datetime->diffInMinutes($examsHistory->created_at);
        $examsHistory->save();
        return response()->json(['code'=>'200','msg' => 'Save Exam Successfully'], 200);
    }
  
    public function createExameHistory(Request $request){
        $uuid = Str::uuid()->toString();

        $header = apache_request_headers();
        $token = $header['token'];
        $findToken = sessionTokenUser::where('token', $token)->first();
        if(!empty($findToken)){
            $userId = $findToken->user_id;
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Invalid Token'], 400);
        }
        $exameSocre = $header['exame_socre'];
        $exameResult = $header['exame_result'];
        $exameComment = $header['exame_comment'];
        $exameCompletedTime = $header['exame_completed_time'];
        $exameId = $header['exame_id'];
        $newExameHistory = ExamationHistories::create([
            'id' => $uuid,
            'score' => $exameSocre,
            'ressult' => $exameResult,
            'comments' => $exameComments,
            'time_for_completed' => $exameCompletedTime,
            'user_id' => $userId,
            'examination_id	' => $exameId
        ]);
        if(!$newExameHistory){
            return response()->json(['code' => '200', 'msg'=> 'New Exame has been added'], 200);
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Create new Exame Faild'], 400);
        }

    }
    public function addComments(Request $request, $id){
        $examsHistory = ExamationHistories::where('id','=', $id)->first();
        if(!empty($examsHistory)){
            $examsHistory->comments = $request->comment;
            $examsHistory->save();
            return response()->json(['code'=>'200','msg' => 'Send Comments Successfully'], 200);
        }
        return response()->json(['code'=>'401','msg' => 'Invalid Token'], 401);
    }
    public function getExameHistoryByUser($token){
        $findToken = sessionTokenUser::where('token', $token)->first();
        if(!empty($findToken)){
            $exameHistoryLst = ExamationHistories::where('user_id','=', $findToken->user_Id)
            ->orderBy('created_at', 'desc')
            ->get();
            if(!empty($exameHistoryLst)){
                return response()->json(['code'=>'200','data'=>$exameHistoryLst], 200);
            }
            return response()->json(['code'=>'401','msg' => 'Invalid Token'], 401);
        }else{
            return response()->json(['code'=>'401','msg' => 'Invalid Token'], 401);
        }
        
    }
    public function getExamHistoryByExameId(){
        $header = apache_request_headers();
        $exameId = $header['exame_id'];
        $exameHistoryLst = ExamationHistories::where('examination_id','=', $exameId)->get();
        if(!empty($exameHistoryLst)){
            return response()->json(['code'=> '200', 'data'=> $exameHistoryLst], 200);
        }else{
            return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
        }
    }
    public function handleDisableExameHistory(){
        $header = apache_request_headers();
        $exameHistoryId = $header['id'];
        $findExameHistory = ExamationHistories::where('id','=', $exameHistoryId)->first();
        if(!empty($findExameHistory)){
            $findExameHistory->status = false;
            $findExameHistory->save();
            return response()->json(['code'=> '200', 'msg'=> 'Disable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEnableExameHistory(){
        $header = apache_request_headers();
        $exameHistoryId = $header['id'];
        $findExameHistory = ExamationHistories::where('id','=', $exameHistoryId)->first();
        if(!empty($findExameHistory)){
            $findExameHistory->status = true;
            $findExameHistory->save();
            return response()->json(['code'=> '200', 'msg'=> 'Enable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
}
