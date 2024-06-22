<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grades;
use Illuminate\Support\Str;
use App\Models\sessionTokenUser;
use App\Models\Subjects;


class GradesController extends Controller
{
    public function getAllgrades(){
        $grades = Grades::All();
        return response()->json(['code'=>'200','data' => $grades], 200);
    }
    public function getGradeById($id){
        $findGrade = Grades::where('id','=', $id)->first();
        if(!empty($findGrade)){
            return response()->json(['code'=> '200', 'data'=> $findGrade], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function createGrade(Request $request){
        $uuid = Str::uuid()->toString();
        $gradeName = $request->grade;
        $gradeDesciption = $request->description;
        $newGrade = Grades::create([
            'id' => $uuid,
            'grade' => $gradeName,
            'status' => true,
            'description' => $gradeDesciption
        ]);
        if(!empty($newGrade)){
            return response()->json(['code' => '200', 'msg'=> 'New Grade has been added'], 200);
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Create new Subject Faild'], 400);
        }
    }
    public function handleEdit(Request $request, $id){
        $findGrade = Grades::where('id','=', $id)->first();
        if(!empty($findGrade)){
            $findGrade->grade = $request->grade;
            $findGrade->description = $request->description;
            $findGrade->save();
            return response()->json(['code'=> '200', 'msg'=> 'Update Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleDisableGrade($id){
        $findGrade = Grades::where('id','=', $id)->first();
        if(!empty($findGrade)){
            $findGrade->status = false;
            $findGrade->save();
            $listSubject = Subjects::where('grade_id','=', $id)->get();
            if(!empty($listSubject)){
                foreach($listSubject as $subject){
                    $subject->status = false;
                    $subject->save();
                }
            }
            return response()->json(['code'=> '200', 'msg'=> 'Disable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEnableGrade($id){
        $findGrade = Grades::where('id','=', $id)->first();
        if(!empty($findGrade)){
            $findGrade->status = true;
            $findGrade->save();
            $listSubject = Subjects::where('grade_id','=', $id)->get();
            if(!empty($listSubject)){
                foreach($listSubject as $subject){
                    $subject->status = true;
                    $subject->save();
                }
            }
            return response()->json(['code'=> '200', 'msg'=> 'Enable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
}
