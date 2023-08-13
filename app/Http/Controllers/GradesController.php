<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grades;
use App\Models\sessionTokenUser;

class GradesController extends Controller
{
    public function getAllgrades(){
        $grades = Grades::All();
        return response()->json(['code'=>'200','data' => $grades], 200);
    }
    public function getGradeById(){
        $header = apache_request_headers();
        $gradeId = $header['id'];
        $findGrade = Grades::where('id','=', $gradeId)->first();
        if(!empty($findGrade)){
            return response()->json(['code'=> '200', 'data'=> $findGrade], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function createGrade(Request $request){
        $gradeName = $request->gradeName;
        $gradeStatus = $request->gradeStatus;
        $newGrade = Grades::create([
            'grade' => $gradeName,
            'status' => $gradeStatus
        ]);
        if(!empty($newGrade)){
            return response()->json(['code' => '200', 'msg'=> 'New Grade has been added'], 200);
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Create new Subject Faild'], 400);
        }
    }
    public function handleEdit(Request $request){
        $findGrade = Grades::where('id','=', $request->gradeId)->first();
        if(!empty($findGrade)){
            $findGrade->grade = $request->gradeName;
            $findGrade->status = $request->gradeStatus;
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
            return response()->json(['code'=> '200', 'msg'=> 'Disable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEnableGrade($id){
        $findGrade = Grades::where('id','=', $id)->first();
        if(!empty($findGrade)){
            $findGrade->status = true;
            $findGrade->save();
            return response()->json(['code'=> '200', 'msg'=> 'Enable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
}
