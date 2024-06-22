<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examination;
use App\Models\Questions;
use App\Models\QuestiontoExame;
use App\Models\Subjects;
use Illuminate\Support\Str;
use App\Models\ExamationHistories;
use App\Models\sessionTokenUser;
use function PHPUnit\Framework\isEmpty;

class QuestiontoExameController extends Controller
{
    public function addQuestionToExame($id){
        $uuid = Str::uuid()->toString();
        $exameId = $id;
        $exam = Examination::where('id', $exameId)->first();
        $subject =  Subjects::where('id','=', $exam->subjects_id)->first();
        $listQuestions = Questions::where('subjects_id', $subject->id)->get();
        if(count($listQuestions) > 0){
            $listNewQuestiontoExame = array();
            $listAvailableExam = QuestiontoExame::where('examination_id', $exameId)->get();
            if(count($listAvailableExam) == 0){
                foreach($listQuestions as $question){
                    $new = QuestiontoExame::create([
                        'id' => $uuid,
                        'examination_id' => $exameId,
                        'questions_id' => $question->id
                    ]);
                    array_push($listNewQuestiontoExame, $new);
                }
                if(count($listNewQuestiontoExame)>0){
                    return response()->json([
                        'code' => 200,
                        'data' => $listNewQuestiontoExame,
                        'questions' =>$listQuestions,
                        'subjectInfor' =>$subject
                    ], 200);
                }else{
                    return response()->json([
                        'code' => '400',
                        'msg' => 'Create Faild'
                    ], 400);
                }
            }
            return response()->json([
                'code' => 200,
                'data' => $listAvailableExam,
                'questions' =>$listQuestions,
                'subjectInfor' =>$subject
            ], 200);
        }
        return response()->json([
            'code' => '400',
            'msg' => 'No Questions Available'
        ], 400);
       
        
    }
    public function saveAnwser(Request $request){
        $FoundRecord = QuestiontoExame::where('id','=', $request->recordId)->first();
        if(!empty($FoundRecord)){
            if($request->isDraft == 'true'){
                $FoundRecord->status = 'Draft';
                $FoundRecord->answer = $request->answer;
                $FoundRecord->save();
                return response()->json(['code'=>'200','msg'=>'Save Draft'], 200);
            }
            $FoundRecord->answer = $request->answer;
            $FoundRecord->status = 'Done';
            $FoundRecord->save();
            return response()->json(['code'=>'200','msg'=>'Save Answer'], 200);
        }
        return response()->json(['code'=>'400','msg'=>'Invalid Id'], 400);
       
    }
    public function saveExam (Request $request){
        $uuid = Str::uuid()->toString();
        $FoundRecord = QuestiontoExame::where('examination_id','=', $request->recordId)->get();
        $exam = Examination::where('id', $request->recordId)->first();
        $subject =  Subjects::where('id','=', $exam->subjects_id)->first();
        $listQuestions = Questions::where('subjects_id', $subject->id)->get();
        $user = sessionTokenUser::where('token', $request->token)->first();
        if(!empty($user)){
            $countCorrectedQuestions = 0;
            foreach($FoundRecord as $anwser){
                if($anwser->answer == null || $anwser->status = 'Draft'){
                    continue;
                }
                foreach($listQuestions as $question){
                if($anwser->questions_id == $question->id && $anwser->answer == $question->multiple_seletion_result){
                    $countCorrectedQuestions++;
                }
                }
            }
            $socre = ($countCorrectedQuestions/count($listQuestions)) * 100;
            $new = ExamationHistories::create([
                'id' => $uuid,
                'user_id' => $user->user_id,
                'result' => $socre>= 50 ? 'passed' : 'failed',
                'comments' => 'Comments',
                'time_for_completed' => 60,
                'examination_id' => $request->recordId,
                'score' => $socre,
            ]);
            if($new != null){
                return response()->json(['code'=>'200','msg'=>'Save Success'], 200);
            }
            return response()->json(['code'=>'400','msg'=>'Save Fail'], 400);
        }else{
            return response()->json(['code'=>'401','msg' => 'Invalid Token'], 401);
        }
       
    }
    public function getAllQuestionsByExam(Request $request){
        $listFindRecord = QuestiontoExame::where('examination_id','=', $request->exameId)->get();
            $listId = array();
            foreach ($listFindRecord as $key => $value) {
                array_push($listId, $value->questions_id);
            }
            $findQuestions = Questions::whereIn('id', $listId)->get();
            return response()->json([
                'code' => 200,
                'listQuestions' => $findQuestions,
                'listAnswer' => $listFindRecord
            ], 200);
        
       
    }
}
