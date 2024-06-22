<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subjects;
use App\Models\Questions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class QuestionsController extends Controller
{
    public function getAllQuestions(){
        $questList = Questions::orderBy('created_at', 'desc')->get();
        if(!empty($questList)){
            return response()->json(['code'=>'200','data' => $questList], 200);
        }else{
            return response()->json(['code'=>'400','msg' => 'None Question is Available'], 400);
        }
    }
    public function getQuestionByID($id){
        $questionLst = Questions::where('id','=', $id)->first();
        if(!empty($questionLst)){
            return response()->json(['code'=> '200', 'data'=> $questionLst], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function getQuestionByName(){
        $header = apache_request_headers();
        $questionTitle = $header['title'];
        $questionLst = Questions::where('title','like', $questionTitle.'%')->get();
        if(!$questionLst){
            return response()->json(['code'=> '200', 'data'=> $questionLst], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Name is invalid'], 400);
    }
    public function createQuestion(Request $request){
        $uuid = Str::uuid()->toString();
        $newFileName = '';
        if ($request->file('file')) {
            $file = $request->file('file');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $path = public_path('images/');

            // Check if the file exists and rename if necessary
            $counter = 1;
            $newFileName = $fileName . '.' . $extension;
            while (File::exists($path . $newFileName)) {
                $newFileName = $fileName . '(' . $counter . ').' . $extension;
                $counter++;
            }
            // Store the file
            $file->move($path, $newFileName);

        }

        $questionTitle = $request->titles;
        $questionType = $request->question_type;
        $subjectId = $request->subject_id;
        $difficulty = $request->difficulty;
        $newQuestion = Questions::create([
            'id' => $uuid,
            'titles' => $questionTitle,
            'question_type' => $questionType,
            'subject_id' => $subjectId,
            'status' => '1',
            'difficulty' => $difficulty,
            'image' => $newFileName,
        ]);
        $createdQuestion = Questions::where('id','=', $uuid)->first();
        if($questionType == 'Multiple Choice' || $request->question_type == 'Single Choice'){
            $questionASelection = $request->a_selection;
            $questionBSelection = $request->b_selection;
            $questionCSelection = $request->c_selection;
            $questionDSelection = $request->d_selection;
            $multipleChoiceReult = $request->multiple_selection_result;
            $createdQuestion -> a_selection = $questionASelection;
            $createdQuestion -> b_selection = $questionBSelection;
            $createdQuestion -> c_selection = $questionCSelection;
            $createdQuestion -> d_selection = $questionDSelection;
            $createdQuestion -> multiple_selection_result = $multipleChoiceReult;
            $createdQuestion -> writing_result = '';
        }else{
            $writingResult = $request->writing_result;
            $createdQuestion -> writing_result = $writingResult;
        }
        $createdQuestion->save();
        if(!empty($createdQuestion)){
            return response()->json(['code' => '200', 'msg'=> 'New Question has been added'], 200);
        }else{
            return response()->json(['code' => '400', 'msg'=> 'Create new Question Faild'], 400);
        }
    }
    public function handleEditQuestion(Request $request, $id){
        $findQuestion = Questions::where('id','=', $id)->first();
        if(!empty($findQuestion)){
            $findQuestion -> titles = $request->titles;
            $findQuestion -> question_type = $request->question_type;
            $findQuestion -> subject_id = $request->subject_id;
            if($request->question_type == 'Multiple Choice' || $request->question_type == 'Single Choice'){
                $findQuestion -> a_selection = $request->a_selection;
                $findQuestion -> b_selection = $request->b_selection;
                $findQuestion -> c_selection = $request->c_selection;
                $findQuestion -> d_selection = $request->c_selection;
                $findQuestion -> multiple_selection_result = $request->multiple_selection_result;
                $findQuestion -> writing_result = '';
            }else{
                $findQuestion -> writing_result = $request->writing_result;
            }
            $findQuestion -> save();
            return response()->json(['code'=> '200', 'msg'=> 'Updated Question Success'], 200);
        }else{
            return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
        }
    }
    public function getQuestionBySubjectId(){
        $header = apache_request_headers();
        $subjectId = $header['id'];
        $findQuestionsList = Questions::where('subject_id','=', $subjectId)->get();
        if(!empty($findQuestions)){
            return response()->json(['code'=> '200', 'data'=> $findQuestionsList], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleDisableQuestion($id){
        $findQuestion = Questions::where('id','=', $id)->first();
        if(!empty($findQuestion)){
            $findQuestion->status = '0';
            $findQuestion->save();
            return response()->json(['code'=> '200', 'msg'=> 'Disable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
    public function handleEnableQuestion($id){
        $findQuestion = Questions::where('id','=', $id)->first();
        if(!empty($findQuestion)){
            $findQuestion->status = '1';
            $findQuestion->save();
            return response()->json(['code'=> '200', 'msg'=> 'Enable Success'], 200);
        }
        return response()->json(['code'=> '400', 'msg'=> 'Id is invalid'], 400);
    }
}
