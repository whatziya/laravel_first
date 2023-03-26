<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Student;
use App\Models\PasswordReset;
use Validator;
use Illuminate\Support\Facades\Hash;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class StudentController extends Controller
{
    
    //add student api

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "firstname" => "required|string|min:2|max:100",
            "surname" => "required|string|min:2|max:100",
            "lastname" => "required|string|min:2|max:100",
            'birthdate' => 'required|date',
            "group" => "required|string|min:3|max:20"
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors());
        }

        $student = Student::create([
            "firstname"=>$request->firstname,
            "surname"=>$request->surname,
            "lastname"=>$request->lastname,
            "birthdate"=>$request->birthdate,
            "group"=>$request->group,
            "user_id"=>auth()->user()->id
        ]);
        return response()->json([
            "msg"=>"Student Created Successfully",
            "student"=>$student
        ]);
    }

    public function getData()
    {
               
        $students = DB::table('students')->select('id','firstname', 'surname', 'lastname', 'birthdate', 'group')
        ->where('user_id', auth()->user()->id)
        ->get();
        return response()->json(['students' => $students]);
    }

    public function getStudentData($id)
    {
        $student = DB::table('students')->select('id','firstname', 'surname', 'lastname', 'birthdate', 'group')
        ->where('id',$id)
        ->get();
        return view('students.edit-user',['student'=>$student]);
    }

    public function updateStudent(Request $request)
    {
        $student = Student::find($request->id);
        $student->firstname = $request->firstname;
        $student->surname = $request->surname;
        $student->lastname = $request->lastname;
        $student->birthdate = $request->birthdate;
        $student->group = $request->group;
        $student->save();

        return response()->json(["result"=>"Student Updated"]);
    }
    public function deleteData($id)
    {
        Student::where('id',$id)->delete();

        return response()->json(["result"=>"Student Deleted"]);
    }
    
}
