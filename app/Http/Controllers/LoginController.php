<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\sessionTokenUser;
use App\Models\sessionTokenResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mail;
use URL;
use Carbon\Carbon;


class LoginController extends Controller
{
    public function login(Request $request){
        $uuid = Str::uuid()->toString();
        $loginData = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if(Auth::guard('web')->attempt($loginData)){
            $isTokenExits = sessionTokenUSer::where('user_id', auth()->id())->first();
            if(empty($isTokenExits)){
                $userToken = sessionTokenUser::create([
                    'id' => $uuid,
                    'token' => Str::random(40),
                    'refresh_token' => Str::random(40),
                    'token_expired' => date('Y-m-d H:i:s', strtotime('+1 day')),
                    'refresh_token_expired' =>  date('Y-m-d H:i:s', strtotime('+30 day')),
                    'user_id' => auth()->id()
                ]);
            }else{
                $userToken = $isTokenExits;
            }
            return response()->json(['code'=>'200','data' => $userToken, 'msg' => 'Login Success'], 200);
        }else{
            return response()->json(['code'=>'400','msg' => 'User Name or Password is incorrected'], 400);
        }
    }
    public function logout(Request $request){
        $token = $request->token;
        $findToken = sessionTokenUser::where('token', $token)->first();
        if(!empty($findToken)){
            $findToken->delete();
            Auth::logout();
            return response()->json(['code'=>'200','msg' => 'Account has been logout'], 200);
        }else{
            return response()->json(['code'=>'401','msg' => 'Invalid Token'], 401);
        }

    }
    public function sentMailtoResetPassword(Request $request){
        $user = User::where('email','=',$request->email)->get();

        if(count($user)>0){
            $radomStr = Str::random(40);
            $domain = URL::to('/');
            $url = 'http://localhost:3000/admin/resetpasswordform?token='.$radomStr;
            $data['token'] = $radomStr;
            $data['email'] = $user[0]->email;
            $data['title'] = 'Reset Password';
            $data['description'] = 'This is email from E-learning System, You Can Reset Your Password with The Link Below';
            $data['url'] = $url;

            Mail::send('resetPasswordMail',['data'=>$data] , function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            sessionTokenResetPassword::UpdateOrCreate(
                ['email_reset_password' => $user[0]->email],
                [
                    'email_reset_password' => $user[0]->email,
                    'token_reset_password' => $radomStr
                ]
            );
            return response()->json(['code'=>'200','msg' => 'Mail has been sent'], 200);
        }else{
            return response()->json(['code'=>'401','msg' => 'Invalid Email'], 401);
        }
    }
    public function sendTokenResetPassword(Request $request){
        $findTokenResetPassword = sessionTokenResetPassword::where('token_reset_password',$request->token)->first();
        if(!empty($findTokenResetPassword)){
            $currentTime = Carbon::now();
            $timeCreated = $findTokenResetPassword->created_at;
            $timeUpdated = $findTokenResetPassword->updated_at;

            //dd($findTokenResetPassword[0]->created_at . '------' . $currentTime);
            $checkValidTokenWithCreatedTime = $currentTime->diffInMinutes($timeCreated);
            $checkValidTokenWithUpdatedTime = $currentTime->diffInMinutes($timeUpdated);
            if($checkValidTokenWithCreatedTime < $checkValidTokenWithUpdatedTime){
                $checkValidToken = $checkValidTokenWithCreatedTime;
            }else{
                $checkValidToken = $checkValidTokenWithUpdatedTime;
            }
            if($checkValidToken < 15){
                $email = $findTokenResetPassword->email_reset_password;
                $findUser = User::where('email','=',$email)->first();
                if(!empty($findUser)){
                    $findUser->password =  bcrypt($request->newPassword);
                    $findUser->save();
                    $findTokenResetPassword->delete();
                    return response()->json(['code'=>'200','msg' => 'Your New Password has been applied'], 200);
                }
            }else{
                return response()->json(['code'=>'401','msg' => 'Token Time Limited'], 401);
            }
        }else{
            return response()->json(['code'=>'401','msg' => 'Invalid Token'], 401);
        }
    }
}
