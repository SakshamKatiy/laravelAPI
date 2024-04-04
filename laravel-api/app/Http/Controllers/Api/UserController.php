<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    
    public function createUser(Request $request){
        $validator = Validator::make($request->all(),[
'name'=>'required|string',
'email'=>'required|string',
'phone_number'=>'required|numeric',
'password'=>'required|min:6'
        ]);
        if($validator->fails()){
            $result = array('status'=>false,'message'=>'validator error',
            'validation_error' => $validator->errors());;
return response()->json($result,400);
        };
        $user = User::create([
'name'=>$request->name,
'email'=>$request->email,
'phone_number'=>$request->phone_number,
'password'=>bcrypt($request->password),
        ]);
        if($user->id){
            $result= array('status'=>true,'message'=>'user created','data'=>$user);
            $responseCode = 400;
        }
        else{
            $result = array('status'=>false,'message'=>'something went wrong');
            $responseCode = 200;
        }
        return response()->json($result,$responseCode);

    }
}
