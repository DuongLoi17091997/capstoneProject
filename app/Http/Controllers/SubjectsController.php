<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grades;
use App\Models\Subjects;


class SubjectsController extends Controller
{
    public function getSubjectByClass(Request $request){
        $subjectLst = Subjects::where('grades_id','=', $request->gradeId)->get();
        return response()->json($subjectLst, 200);
    }
    public function createSubject(Request $request){
        $newSubject = Subjects::create([
            'name' => $request->name,
            'range' => $request->range,
            'grades_id' => $request->grades_id,
        ]);
        if(!empty($newSubject)){
            return response()->json(['code' => '200', 'msg'=> 'New Subject has been added'], 200);
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Create new Subject Faild'], 400);
        }
    }
    public function getAllSubjects(){
        $subjects = Subjects::All();
        if(!empty($subjects)){
            return response()->json(['code'=>'200','data' => $subjects], 200);
        }
        return response()->json(['code'=>'400','msg' => 'None Subject is Available'], 400);
    }
    public function getEdit($id){
        $subjectLst = Subjects::where('id','=', $id)->first();
        if(!empty($subjectLst)){
            return response()->json(['code'=> '200', 'data'=> $subjectLst], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEdit(Request $request){
        $findSubjet = Subjects::where('id', $request->subjectId)->first();
        if(!empty($findSubjet)){
            $findSubjet-> name = $request->name;
            $findSubjet -> range = $request->range;
            $findSubjet -> grades_id = $request->grades_id;
            $findSubjet-> save();
            return response()->json(['code'=> '200', 'msg'=> 'Updated Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleDelete(Request $request){
        $findSubjet = Subjects::where('id', $request->id)->first();
        if(!empty($findSubjet)){
            $findSubjet-> delete();
            return response()->json(['code'=> '200', 'msg'=> 'Deleted Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleDisableSubject($id){
        $findSubject = Subjects::where('id','=', $id)->first();
        if(!empty($findSubject)){
            $findSubject->status = 0;
            $findSubject->save();
            return response()->json(['code'=> '200', 'msg'=> 'Disable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEnableSubject($id){
        $findSubject = Subjects::where('id','=', $id)->first();
        if(!empty($findSubject)){
            $findSubject->status = 1;
            $findSubject->save();
            return response()->json(['code'=> '200', 'msg'=> 'Enable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
}
