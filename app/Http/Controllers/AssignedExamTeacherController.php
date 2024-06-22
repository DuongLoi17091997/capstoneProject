<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignedExamTeacher;
use App\Models\ExamationHistories;
use Illuminate\Support\Str;


class AssignedExamTeacherController extends Controller
{
    public function assignExamtoTeacher(Request $request){
        $examId = $request->exam_id;
        $teacherId = $request->teacher_id;
        $checkIsExamAvaible = AssignedExamTeacher::where('user_id', $teacherId)->where('exam_id', $examId)->first();
        if(empty($checkIsExamAvaible)){
            $newAssign = AssignedExamTeacher::create([
                'id' => Str::uuid()->toString(),
                'user_id' => $teacherId,
                'exam_id' => $examId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return response()->json(['code'=> '200', 'msg'=> 'Exam is assigned' , 'data' => $newAssign], 200);
        }else{
            return response()->json(['code'=> '400', 'msg'=> 'Exam is already assigned'], 400);
        }
    }
    public function getAssignExamByTeacherId($id){
        $listRelationShipExamtoTeacher = AssignedExamTeacher::where('user_id', $id)->get();
        if(!empty($listRelationShipExamtoTeacher)){
            $listExamHistories = array();
            foreach ($listRelationShipExamtoTeacher as $key => $value) {
                $findExam = ExamationHistories::where('exam_id', $value->exam_id)->first();
                if(!empty($findExam)){
                    array_push($listExamHistories, $findExam);
                }
            }
            if(!empty($listExamHistories)){
                return response()->json(['code'=> '200', 'data'=> $listExamHistories], 200);
            }
            return response()->json(['code'=> '400', 'msg'=> 'There are no exam assigning to you'], 400);
        }
        return response()->json(['code'=> '400', 'msg'=> 'There are no exam assigning to you'], 400);
    }
    // public function editAssignExamtoTeacher(Request $request){
    //     $examId = $request->examId;
    //     $teacherId = $request->teacherId;
    //     $checkIsExamAvaible = AssignedExamTeacher::where('user_id', $teacherId)->where('exam_id', $examId)->get();
    //     if(empty($checkIsExamAvaible)){
    //         $newAssign = AssignedExamTeacher::create([
    //             'id' => Str::uuid()->toString(),
    //             'user_id' => $teacherId,
    //             'exam_id' => $examId,
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);
    //         return response()->json(['code'=> '200', 'msg'=> 'Exam is assigned' , 'data' => $newAssign], 200);
    //     }else{
    //         return response()->json(['code'=> '400', 'msg'=> 'Exam is already assigned'], 400);
    //     }
    // }
}
