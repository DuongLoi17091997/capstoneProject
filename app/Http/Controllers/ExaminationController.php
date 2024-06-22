<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\Examination;
use Ramsey\Uuid\Nonstandard\Uuid;
use App\Models\Questions;
use App\Models\QuestiontoExame;
use Illuminate\Support\Str;
use App\Models\QuestionAnswer;



class ExaminationController extends Controller
{
    public function getAllExamWithPagination(Request $request){
        $subjectId = $request->subjectId;
        $pageSize = $request->pageSize;
        $exams = Examination::paginate((int)$pageSize);
        if(empty($subjectId)){
            return response()->json(['code'=>'200','data' => $exams], 200);
        } else {
            $exams = Examination::paginate(16)->where('subject_id', $subjectId);
            return response()->json(['code'=>'200','data' => $exams], 200);
        }
        return response()->json(['code'=>'400','msg' => 'None Exame is Available'], 400);
    }
    public function getAllExam(Request $request){
        $exams = Examination::orderBy('created_at', 'desc')->get();
        
        if(empty($request->status) && empty($request->subject_id)){
            return response()->json(['code'=>'200','data' => $exams], 200);
        } 
        if(!empty($request->status) && !empty($request->subject_id)){
            $exams = Examination::where('type', $request->status)->where('subjects_id', $request->subject_id)->get();
            return response()->json(['code'=>'200','data' => $exams], 200);
        } else  {
            if(!empty($request->subject_id)){
                $exams = Examination::where('subjects_id', $request->subject_id)->get();
                return response()->json(['code'=>'200','data' => $exams], 200);            
            }
            if(!empty($request->status)){
                $exams = Examination::where('type', $request->status)->get();
                return response()->json(['code'=>'200','data' => $exams], 200);
            }
        }
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
        $uuid = Str::uuid()->toString();
        $subjectId = $request->subject;
        $exameName = $request->name;
        $exameType = 'public';
        $exameDescription = $request->description;
        $difficulty = $request->dificulty;
        $numberOfQuestions = $request->numberOfQuestions;
        switch ($difficulty) {
            case "Easy":
                $exameTimeLimited = 15;
                $easyQuestionPesentage = 0.7;
                $mediumQuestionPesentage = 0.2;
                break;
            case "Medium":
                $exameTimeLimited = 30;
                $easyQuestionPesentage = 0.3;
                $mediumQuestionPesentage = 0.6;
                break;
            case "Hard":
                $exameTimeLimited = 45;
                $easyQuestionPesentage = 0.3;
                $mediumQuestionPesentage = 0.4;
                break;
            default:
            return response()->json(['code' => '400', 'msg'=> 'Create new Exame Faild'], 400);
        }

        $newExam = Examination::create([
            'id' => $uuid,
            'name' => $exameName,
            'type' => $exameType,
            'description' => $exameDescription,
            'limitedTime' => $exameTimeLimited,
            'subject_id' => $subjectId,
            'difficulty' => $difficulty,
            'numberOfQuestions' => $numberOfQuestions,
            'author' => $request->user_id,
        ]);
        $numberOfEasyQuestions = $easyQuestionPesentage * $numberOfQuestions;
        $numberOfMediumQuestions = $mediumQuestionPesentage * $numberOfQuestions;
        $numberOfHardQuestions = $numberOfQuestions - ($numberOfEasyQuestions + $numberOfMediumQuestions);
        $listQuestions = Questions::where('subject_id', $subjectId)->get();
        $addedQuestions = array();
        $easyNum = 0;
        $mediumNum = 0;
        $hardNum = 0;

        foreach ($listQuestions as $question) {
            $uuidRelation = Str::uuid()->toString();
            if($easyNum < $numberOfEasyQuestions && $question->difficulty === 'Easy'){
                $new = QuestiontoExame::create([
                    'id' => $uuidRelation,
                    'exam_id' => $uuid,
                    'question_id' => $question->id
                ]);
                $easyNum++;
            }
            if($mediumNum < $numberOfMediumQuestions && $question->difficulty === 'Medium'){
                $new = QuestiontoExame::create([
                    'id' => $uuidRelation,
                    'exam_id' => $uuid,
                    'question_id' => $question->id
                ]);
                $mediumNum++;
            }
            if($hardNum < $numberOfHardQuestions && $question->difficulty === 'Hard'){
                $new = QuestiontoExame::create([
                    'id' => $uuidRelation,
                    'exam_id' => $uuid,
                    'question_id' => $question->id
                ]);
                $hardNum++;
            }
        }
        if(!empty($newExam)){
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
