<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examination;


class ExaminationController extends Controller
{
    public function getAllExamWithPagination(){
        $exams = Examination::paginate(16);
        if(!empty($exams)){
            return response()->json(['code'=>'200','data' => $exams], 200);
        }
        return response()->json(['code'=>'400','msg' => 'None Exame is Available'], 400);
    }
    public function getAllExam(){
        $exams = Examination::All();
        if(!empty($exams)){
            return response()->json(['code'=>'200','data' => $exams], 200);
        }
        return response()->json(['code'=>'400','msg' => 'None Exame is Available'], 400);
    }
    public function getExambyId($id){
        $exam = Examination::where('id','=', $id)->first();
        if(!empty($exam)){
            return response()->json(['code'=> '200', 'data'=> $exam], 200);
        }else{
            return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
        }
    }
    public function createExame(Request $request){
        $header = apache_request_headers();
        $subjectId = $header['subject_id'];
        $exameName = $header['exame_name'];
        $exameType = $header['exame_type'];
        $exameDescription = $header['exame_description'];
        $exameTimeLimited = $header['exame_time_limited'];
        $newExame = Examination::create([
            'name' => $exameName,
            'type' => $exameType,
            'description' => $exameDescription,
            'limitedTime' => $exameTimeLimited,
            'subjects_id' => $subjectId
        ]);
        if(!$newExame){
            return response()->json(['code' => '200', 'msg'=> 'New Exame has been added'], 200);
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Create new Exame Faild'], 400);
        }

    }
    public function getEdit(){
        $header = apache_request_headers();
        $exametId = $header['id'];
        $exameLst = Examination::where('id','=', $exametId)->first();
        if(!$exameLst){
            return response()->json(['code'=> '200', 'data'=> $exameLst], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEdit(){
        $header = apache_request_headers();
        $exameId = $header['id'];
        $subjectId = $header['subject_id'];
        $exameName = $header['exame_name'];
        $exameType = $header['exame_type'];
        $exameDescription = $header['exame_description'];
        $exameTimeLimited = $header['exame_time_limited'];
        $findExame = Examination::where('id', $exameId)->first();
        if(!empty($findExame)){
            $findExame-> name = $exameName;
            $findExame -> type = $exameType;
            $findExame -> description = $exameDescription;
            $findExame -> limitedTime = $exameTimeLimited;
            $findExame -> subjects_id = $subjectId;
            $findExame-> save();
            return response()->json(['code'=> '200', 'msg'=> 'Updated Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function getExamBySubject(){
        $header = apache_request_headers();
        $subjectId = $header['subject_id'];
        $exameLst = Examination::where('subjects_id','=', $subjectId)->get();
        if(!empty($exameLst)){
            return response()->json(['code'=> '200', 'data'=> $exameLst], 200);
        }else{
            return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
        }
    }
    public function handleDisableExame(){
        $header = apache_request_headers();
        $exameId = $header['id'];
        $findExame = Examination::where('id','=', $exameId)->first();
        if(!empty($findExame)){
            $findExame->status = false;
            $findExame->save();
            return response()->json(['code'=> '200', 'msg'=> 'Disable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEnableExame(){
        $header = apache_request_headers();
        $exameId = $header['id'];
        $findExame = Examination::where('id','=', $exameId)->first();
        if(!empty($findExame)){
            $findExame->status = true;
            $findExame->save();
            return response()->json(['code'=> '200', 'msg'=> 'Enable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
}
