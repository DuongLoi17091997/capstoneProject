<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamationHistories;

class ExaminationHistoryController extends Controller
{
    public function getAllExameHistory(){
        $examsHistory = ExamationHistories::All();
        if(!$examsHistory){
            return response()->json(['code'=>'200','data' => $examsHistory], 200);
        }
        return response()->json(['code'=>'400','msg' => 'None Exame is Available'], 400);
    }
    public function createExameHistory(Request $request){
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
    public function getEdit(){
        $header = apache_request_headers();
        $exameHistoryId = $header['id'];
        $exameHistoryLst = ExamationHistories::where('id','=', $exameHistoryId)->first();
        if(!$exameHistoryLst){
            return response()->json(['code'=> '200', 'data'=> $exameHistoryLst], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEdit(){
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
        $exameHistoryId = $header['exame_history_id'];
        $findExameHistory = ExamationHistories::where('id', $exameHistoryId)->first();
        if(!empty($findExame)){
            $findExameHistory-> score = $exameSocre;
            $findExameHistory -> ressult = $exameResult;
            $findExameHistory -> comments = $exameComment;
            $findExameHistory -> time_for_completed = $exameCompletedTime;
            $findExameHistory -> user_id = $userId;
            $findExameHistory -> examination_id = $exameId;
            $findExameHistory-> save();
            return response()->json(['code'=> '200', 'msg'=> 'Updated Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function getExameHistoryByUser(){
        $header = apache_request_headers();
        $token = $header['token'];
        $findToken = sessionTokenUser::where('token', $token)->first();
        if(!empty($findToken)){
            $userId = $findToken->user_id;
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Invalid Token'], 400);
        }
        $exameHistoryLst = ExamationHistories::where('user_id','=', $userId)->get();
        if(!empty($exameLst)){
            return response()->json(['code'=> '200', 'data'=> $exameHistoryLst], 200);
        }else{
            return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
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
