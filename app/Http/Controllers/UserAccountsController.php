<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\sessionTokenUser;
use App\Models\Questions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;




class UserAccountsController extends Controller
{
    public function getAllUserAccounts($accountType){
        $userList = array();
        if($accountType == 1){
            $userList = User::where('type', 'normal')->get();
        }else {
            $userList = User::where('type', 'admin')->orderBy('created_at', 'desc')->get();
        }
        if(!empty($userList)){
            return response()->json(['code'=>'200','data'=> $userList], 200);
        }else{
            return response()->json(['code'=>'400','msg'=> 'None Users Available'], 400);
        }
    }
    public function GetAllTeachers(){
        $userList = User::where('role', 'Teacher')->get();
        if(!empty($userList)){
            return response()->json(['code'=>'200','data'=> $userList], 200);
        }else{
            return response()->json(['code'=>'400','msg'=> 'None Teachers Available'], 400);
        }
    }
    public function handlerDisableAccount($id){
        $selectedAccount = User::where('id',$id)->first();
        if(!empty($selectedAccount)){
            $selectedAccount->status = false;
            $selectedAccount->save();
            return response()->json(['code'=>'200','msg'=>'Disable success'], 200);
        }
        return response()->json(['code'=>'400','msg'=>'Invalid ID'], 400);
    }
    public function handlerEnableAccount($id){
        $selectedAccount = User::where('id',$id)->first();
        if(!empty($selectedAccount)){
            $selectedAccount->status = true;
            $selectedAccount->save();
            return response()->json(['code'=>'200','msg'=>'Enable success'], 200);
        }
        return response()->json(['code'=>'400','msg'=>'Invalid ID'], 400);
    }
    public function getAccountByID($id){
        $selectedAccount = User::where('id',$id)->first();
        if(!empty($selectedAccount)){
            return response()->json(['code'=>'200','data'=>$selectedAccount], 200);
        }
        return response()->json(['code'=>'400','msg'=>'Invalid ID'], 400);
    }
    public function getAccountByToken($token){
        $findToken = sessionTokenUser::where('token', $token)->first();
        if(!empty($findToken)){
            $selectedAccount = User::where('id',$findToken->user_Id)->first();
            return response()->json(['code'=>'200','msg' =>'Get User Success', 'data' => $selectedAccount], 200);
        }else{
            return response()->json(['code'=>'401','msg' => 'Invalid Token'], 401);
        }
        if(!empty($selectedAccount)){
            return response()->json(['code'=>'200','data'=>$selectedAccount], 200);
        }
        return response()->json(['code'=>'400','msg'=>'Invalid ID'], 400);
    }
    public function handleUpdateAccount(Request $request, $id){
        $selectedAccount = User::where('id',$id)->first();
        if(!empty($selectedAccount)){
            $selectedAccount->first_Name = $request->first_Name;
            $selectedAccount->last_Name = $request->last_Name;
            $selectedAccount->email = $request->email;
            $selectedAccount->phone = $request->phone;
            $selectedAccount->address = $request->address;
            $selectedAccount->province = $request->province;
            $selectedAccount->district = $request->district;
            $selectedAccount->ward = $request->ward;
            $selectedAccount->hobby = $request->hobby;
            $selectedAccount->birthdate = $request->birthdate;
            $selectedAccount->save();
            return response()->json(['code'=>'200','msg'=>'Update Success'], 200);
        }
        return response()->json(['code'=>'400','msg'=>'Invalid ID'], 400);
    }
    public function changePassword(Request $request){
        $loginData = [
            'email' => $request->email,
            'password' => $request->currentPassword
        ];
        if(Auth::guard('web')->attempt($loginData)){
            $foundUser = User::where('id', auth()->id())->first();
            if(!empty($foundUser)){
                $foundUser->password = bcrypt($request->newPassword);
                $foundUser->save();
                return response()->json(['code'=>'200', 'msg' => 'Change Password Successfully'], 200);
            }else{
                return response()->json(['code'=>'400','msg'=>'Invalid ID'], 400);
            }
        }else{
            return response()->json(['code'=>'400','msg' => 'Current Password is incorrected'], 400);
        }
    }
    public function handleUpdateAvatar(Request $request){
        $foundUser = User::where('id', $request->id)->first();
        if(empty($foundUser)){
            return response()->json(['code'=>'400','msg'=>'Invalid Id'], 400);
        }
        $newFileName = '';
        $oldAvatar = $foundUser->avatar;
        if ($request->file('file')) {
            $file = $request->file('file');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $path = public_path('images/avatars/');

            // Check if the file exists and rename if necessary
            $newFileName = $fileName . '.' . $extension;
            while (File::exists($path . $oldAvatar)) {
                File::delete($path . $oldAvatar);
            }
            // Store the file
            $file->move($path, $newFileName);
        }
        $foundUser->avatar = $newFileName;
        $foundUser->save();
        return response()->json(['code'=>'200','msg'=>'Update Success'], 200);
    }
}
