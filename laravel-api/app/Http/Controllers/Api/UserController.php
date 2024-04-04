<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;
use Auth;
class UserController extends Controller
{
    
    public function createUser(Request $request){
        $validator = Validator::make($request->all(),[
        'name'=>'required|string',
        'email'=>'required|string|unique:users',
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

    public function getUser(){
        try{

        
        $users = User::all();
        $result = array('status'=>true,'message'=>count($users). "user(s) fetched ",'data'=>$users);
        $responseCode = 200;
        return response()->json($result,$responseCode);
    }catch(Exception $e){
        $result = array('status'=>false,'message'=>'API failed due to error',
        "error"=>$e->getMessage());
        return response()->json($result,500);
    }
    }

    public function getUserDetails($id){
        $user = User::find($id);
        if(!$user){
            $result = array('status'=>false,'message'=>'user not found');
            return response()->json($result,404);
        }
        else{
            return response(['status'=>true,'message'=>'user found','data'=>$user],200);
            
        }

    }
    public function updateUser(Request $request , $id){
        try{

        $user = User::find($id);
        if(!$user){
            $result = array('status'=>false,'message'=>'user not found');
            return response()->json($result,404);
        }
        

        // Validation
        $validator = Validator::make($request->all(),[
        'name'=>'required|string',
        'email'=>'required|string|unique:users | email,'.$id,
        'phone_number'=>'required|numeric',
        
        ]);
        if($validator->fails()){
            $result = array('status'=>false,'message'=>'validator error',
            'validation_error' => $validator->errors());;
        return response()->json($result,400);
        };

        // Update code
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
       $user->save();
        return response()->json(['status'=>true,'message'=>'User has been updated successfully','data'=>$user],200);

    }catch(Exception $e){
        $result = array('status'=>false,'message'=>'API failed due to error',
        "error"=>$e->getMessage());
        return response()->json($result,500);
    }
}
public function deleteUser($id){
    $user = User::find($id);
    if(!$user){
return response()->json(['status'=>true,'message'=>'User not found'],404);
    }
    $user->delete();
    $result = array('status'=>true,'message'=>'User deleted');
    return response($result,400);
}

// Login function
public function login(Request $request){
    $validator = Validator::make($request->all(),[
        'email'=>'required|string',
        'password'=>'required|min:6'
        ]);
        if($validator->fails()){
            $result = array('status'=>false,'message'=>'validator error',
            'validation_error' => $validator->errors());;
        return response()->json($result,400);
}
$credentials = $request->only("email","password");
if(Auth::attempt($credentials)){
    $user = Auth::user();
    // creating token
    $token = $user->createToken('MyApp')->accessToken;
    return response()->json(['status'=>true,"message"=>"Login Successful","data"=>$user,"token"=>$token],200);

}
return response()->json(['status'=>false,"message"=>"invalid credentials"],401);
}
public function unauthenticate(){
   
return response()->json(['status'=>false,"message"=>"only authorized user access","error"=>"unauthecated"],401);
}
public function logout(){
   $user = Auth::user();
//    $user->tokens->each(function($token,$key){
//     $token->delete();
//    });
return response()->json(['status'=>false,"message"=>"logout successfully",'data'=>$user],200);
}
}
