<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request){
        $newUser = User::create([
            'first_Name' => $request->first_Name,
            'last_Name' => $request->last_Name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        if(!empty($newUser)){
            return response()->json(["code"=>"200","msg"=>"Register Successful"], 200);
        }else{
            return response()->json(["code"=>"400","msg"=>"Register Faild"], 400);
        }
    }
}
