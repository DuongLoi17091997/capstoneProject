<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examination;
use App\Models\Questions;
use App\Models\QuestiontoExame;


class QuestiontoExameController extends Controller
{
    public function addQuestionToExame(Request $request){
        $exameId = $request->exameID;
        $listQuestionId = $request->listQuestionId;
        $listNewQuestiontoExame = array();
        foreach($listQuestionId as $Id){
            $new = QuestiontoExame::create([
                'examination_id' => $exameId,
                'questions_id' => $Id
            ]);
            array_push($listNewQuestiontoExame, $new);
        }
        if(count($listNewQuestiontoExame)>0){
            return response()->json([
                'code' => 200,
                'data' => $listNewQuestiontoExame
            ], 200);
        }else{
            return response()->json([
                'code' => '400',
                'msg' => 'Create Faild'
            ], 400);
        }
    }
    public function getQuestionBelongsToExame(Request $request){
        $exameId = $request->exameId;
        $listFindRecord = QuestiontoExame::where('examination_id','=', $exameId)->get();
        $listQuestionId = array();
        if(count($listFindRecord) > 0) {
            foreach($listFindRecord as $recod){
                array_push($listQuestionId, $recod->questions_id);
            }
            if(count($listQuestionId) > 0){
                $listQuestion = Questions::where('id', 'IN', $listQuestionId).get();
                if(!empty($listQuestion)){
                    return response()->json([
                        'code' => 200,
                        'data' => $listQuestion
                    ], 200);
                }else{
                    return response()->json([
                        'code' => 400,
                        'msg' => 'Invalid Id'
                    ],400);
                }
            }else{
                return response()->json([
                    'code' => 400,
                    'msg' => 'Invalid Id'
                ],400);
            }
        }else{
            return response()->json([
                'code' => 400,
                'msg' => 'Invalid Id'
            ],400);
        }
    }
}
