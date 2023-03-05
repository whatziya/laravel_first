<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PasswordReset;
use Validator;
use Illuminate\Support\Facades\Hash;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    //register api

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "name" => "required|string|min:2|max:100",
            "email" => "required|string|email|min:10|max:30|unique:users",
            "password" => "required|string|min:6|confirmed"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request->password)
        ]);

        return response()->json([
            "msg"=>"User Created Successfully",
            "user"=>$user
        ]);
    }

    //login api

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "email" => "required|string|email",
            "password" => "required|string|min:6"
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        
        if (!$token = auth()->attempt($validator->validated())){
            return response()->json([
                "success"=>false,
                "msg"=>"Username & Password is incorrect"
            ]);
        }

        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        return response()->json([
            "success"=>true,
            "access_token"=>$token,
            "token_type"=>"bearer",
            "expires_in"=>auth()->factory()->getTTL()*60
        ]);
    }

    //logout api

    public function logout()
    {
        try {
            auth()->logout();

            return response()->json([
                "success"=>true,
                "msg"=>"User logged out!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success"=>false,
                "msg"=>$e->getMessage()
            ]);
        }
    }

    //profile api

    public function profile()
    {
        try {
            return response()->json([
                "success"=>true,
                "data"=>auth()->user()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success"=>false,
                "msg"=>$e->getMessage()
            ]);
        }
    }

    //profile-update api

    public function updateProfile(Request $request)
    {
        if (auth()->user()) {

            $validator = Validator::make($request->all(),[
                "id"=>"required",
                "name"=>"required|string",
                "email"=>"required|email|string"
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $user = User::find($request->id);
            $user->name = $request->name;
            if ($user->email != $request->email) {
                $user->email_verified_at=null;
            }
            $user->email = $request->email;
            $user->save();

            return response()->json([
                "success"=>true,
                "msg"=>"User Data",
                "data"=>$user
            ]);

        }
        else {
            return response()->json([
                "success"=>false,
                "msg"=>"User is not Authenticated."
            ]);
        }
    }

    //send verify mail api

    public function sendVerifyMail($email)
    {
        if (auth()->user()) {
            $user = User::where("email",$email)->get();

            if (count($user) > 0) {

                $random = Str::random(40);
                $domain = URL::to("/");
                $url = $domain."/verify-mail/".$random;



                $data["url"]=$url;
                $data["email"]=$email;
                $data["title"]="Email Verification";
                $data["body"]="Please, click here to verify your mail";

                Mail::send("verifyMail",["data"=>$data],function($message) use ($data){
                    $message->to($data["email"])->subject($data["title"]);
                });

                $user = User::find($user[0]["id"]);
                $user->remember_token = $random;
                $user->save();

                return response()->json([
                    "success"=>true,
                    "msg"=>"Mail sent successfully"
                ]);

            }
            else {
                return response()->json([
                    "success"=>false,
                    "msg"=>"User is not found."
                ]);
            }
        }
        else {
            return response()->json([
                "success"=>false,
                "msg"=>"User is not Verified."
            ]);
        }
    }


    public function verificationMail($token)
    {
        $user = User::where("remember_token",$token)->get();
        if (count($user) > 0) {
            $datetime = Carbon::now()->format("Y-m-d H:i:s");
            $user = User::find($user[0]["id"]);
            $user->remember_token = "";
            $user->email_verified_at = $datetime;
            $user->save();
            
            return "<h1>Email Verified Successfully";
        }
        else {
            return view("404");
        }
    }
    

    //refresh token api

    public function refreshToken()
    {
        if (auth()->user()) {
            return $this->respondWithToken(auth()->refresh());
        }
        else {
            return response([
                "success"=>false,
                "msg"=>"User is not Authenticated."
            ]);
        }
    }

    // //forget password api

    // public function forgetPassword(Request $request)
    // {
    //     try {
    //         $user = User::where("email",$request->email)->get();

    //         if (count($user) > 0) {
                
    //             $token = Str::random(40);
    //             $domain = URL::to("/");
    //             $url = $domain.'/reset-password?token='.$token;

    //             $data['url'] = $url;
    //             $data['email'] = $request->email;
    //             $data['title'] = "Password Reset";
    //             $data['body'] = "Please, click here to reset your password";

    //             Mail::send('forgetPasswordMail',['data'=>$data],function($message) use ($data){
    //                 $message->to($data['email'])->subject(data['title']);
    //             });


    //         } else {
    //             return response([
    //                 "success"=>false,
    //                 "msg"=>"User not Found."
    //             ]);
    //         }
            
    //     } catch (\Exception $e) {
    //         return response([
    //             "success"=>false,
    //             "msg"=>$e->getMessage()
    //         ]);
    //     }
    // }
}
