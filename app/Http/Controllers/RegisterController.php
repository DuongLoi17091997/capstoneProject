<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request){
        $checkUser = User::where('email', $request->email)->first();
        $uuid = Str::uuid()->toString();
        if(empty($checkUser)){
            $newUser = User::create([
            'id' => $uuid,
            'first_Name' => $request->first_Name,
            'last_Name' => $request->last_Name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type' => $request->type === 1 ? "admin" : "normal",
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'address' => $request->address,
            ]);
            if(!empty($newUser)){
                return response()->json(["code"=>"200","msg"=>"Register Successful"], 200);
            }else{
                return response()->json(["code"=>"400","msg"=>"Register Faild"], 400);
            }
        }else{
            return response()->json(["code"=>"400","msg"=>"Username is not avaiable"], 400);
        }
        
    }
}
