<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\sessionTokenUser;
use App\Models\Questions;
use Illuminate\Support\Facades\Auth;




class UserAccountsController extends Controller
{
    public function getAllUserAccounts(Request $request){
        $paramValue = $request->input('accountType');
        $userList = array();
        if($paramValue == '1'){
            $userList = User::where('type', 'normal')->get();
        }else {
            $userList = User::where('type', 'admin')->get();
        }
        if(!empty($userList)){
            return response()->json(['code'=>'200','data'=> $userList], 200);
        }else{
            return response()->json(['code'=>'400','msg'=> 'None Users Available'], 400);
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
    public function getAccountByToken(Request $request){
        $token = $request->token;
        $findToken = sessionTokenUser::where('token', $token)->first();
        if(!empty($findToken)){
            $selectedAccount = User::where('id',$findToken->user_id)->first();
            return response()->json(['code'=>'200','msg' =>'Get User Success', 'data' => $selectedAccount], 200);
        }else{
            return response()->json(['code'=>'401','msg' => 'Invalid Token'], 401);
        }
        if(!empty($selectedAccount)){
            return response()->json(['code'=>'200','data'=>$selectedAccount], 200);
        }
        return response()->json(['code'=>'400','msg'=>'Invalid ID'], 400);
    }
    public function handleUpdateAccount(Request $request){
        $selectedAccount = User::where('id',$request->id)->first();
        if(!empty($selectedAccount)){
            $selectedAccount->first_Name = $request->first_Name;
            $selectedAccount->last_Name = $request->last_Name;
            $selectedAccount->email = $request->email;
            $selectedAccount->phone = $request->phone;
            $selectedAccount->address = $request->address;
            $selectedAccount->province = $request->province;
            $selectedAccount->country = $request->country;
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
}
